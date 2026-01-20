<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Tuss;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Bibliotecas\SimpleXLSX;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\GrupoProcedimento;
use App\Model\rpclinica\ProcedimentoConvenio;
use Illuminate\Support\Facades\Route;

class Procedimentos extends Controller
{

    public function index(Request $request)
    {
       // dd(cssRouteCurrent('procedimento.'),Route::currentRouteName());

        if ($request->query('b')) {
            $dados = Procedimento::orderBy('nm_proc')
                ->orWhere('cod_proc', 'LIKE', "%{$request->b}%")
                ->orWhere('nm_proc', 'LIKE', "%{$request->b}%")
                ->get();
            $dados->load('usuario');
        }
        else {
            $dados = Procedimento::orderBy('nm_proc')->get();
        }

        $dados->load('grupo');
 
        return view('rpclinica.procedimento.lista', \compact('dados'));
    }

    public function create() { 
        $grupo = GrupoProcedimento::where('sn_ativo','S')->orderBy('nm_grupo')->get();
        return view('rpclinica.procedimento.add', compact('grupo'));
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'descricao' => 'required|string',
            'cd_grupo' => 'required|string',
            'unidade' => 'required|string', 
            'ativo' => 'nullable|string', 
            'cod_proc' => 'required|string|unique:procedimento,cod_proc',
        ]);

        try {
            Procedimento::create([
                'nm_proc' => $validated['descricao'],
                'cod_proc' => $validated['cod_proc'],
                'sn_pacote' => ($request['sn_pacote']=='S') ? $request['sn_pacote'] : 'N',
                'cd_grupo' => $validated['cd_grupo'],
                'unidade' => $validated['unidade'],
                'sn_ativo' => 'S',
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('procedimento.listar')->with('success', 'Procedimento criado com sucesso!');
        }
        catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit(Procedimento $procedimento) { 

        $grupo = GrupoProcedimento::where('sn_ativo','S')->orderBy('nm_grupo')->get();
        return view('rpclinica.procedimento.edit', compact('procedimento', 'grupo'));
    }

    public function update(Request $request, Procedimento $procedimento) {
        $validated = $request->validate([
            'descricao' => 'required|string',
            'cd_grupo' => 'required|string',
            'unidade' => 'required|string', 
            'ativo' => 'nullable|string'  
        ]);

        try {
          
            $procedimento->update([
                'nm_proc' => $validated['descricao'],
                'cd_grupo' => $validated['cd_grupo'], 
                'unidade' => $validated['unidade'],
                'sn_pacote' => ($request['sn_pacote']=='S') ? $request['sn_pacote'] : 'N',
                'sn_ativo' => ($request['ativo']=='S') ? 'S' : 'N',
                'up_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('procedimento.listar')->with('success', 'Alterações salvas com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel salvar as alterações.']);
        }
    }

    public function delete(Procedimento $procedimento)
    {
        try {
            $Conv = ProcedimentoConvenio::where('cd_procedimento',$procedimento->cod_proc)->count();
            $Exame = Exame::where('cod_proc',$procedimento->cod_proc)->count();
            
            if(($Conv<=0) and ($Exame<=0)){
                $procedimento->delete();
            }else{
                $procedimento->update(['sn_ativo'=>'N']);
            }
            
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 404);
        }
    }
    public function import(Request $request)
    {
 
        $validated = $request->validate([
            'xls' => 'required|mimes:xlsx', 
        ]);

        try {
         
            $file = $request->file('xls'); 
            $Extensao=$file->getClientOriginalExtension(); 
            $path= $file->getRealPath();  
            if(trim($Extensao)=='xlsx'){ 

                $xlsx = new SimpleXLSX( $path );
                list($cols,) = $xlsx->dimension();
                $ERRO=FALSE;
                foreach( $xlsx->rows() as $key => $coluna) { 
                    if($key>0){
                        $select = DB::table("procedimento")->whereRaw("cod_proc=".$coluna[0])->first();
                        if(!empty($select->cod_proc)){ 
                            return redirect()->back()->withInput()->with('error', 'O procedimento <b>'.$coluna[0].'</b> já existe na base de dados!');  
                        }
                        $select = DB::table("grupo_procedimento")->whereRaw("cd_grupo=".$coluna[2])->first();
                        if(empty($select->cd_grupo)){ 
                            return redirect()->back()->withInput()->with('error', 'O grupo de procedimento <b>'.$coluna[0].'</b> não existe na base de dados!');  
                            
                        }
                    }
                }

                foreach( $xlsx->rows() as $key => $coluna) { 
                    if($key>0){
                        $dados['cod_proc'] =$coluna[0];
                        $dados['nm_proc'] =$coluna[1]; 
                        $dados['cd_grupo'] =$coluna[2]; 
                        $dados['unidade'] =$coluna[3]; 

                        $select = DB::table("procedimento")->whereRaw("cod_proc=".$coluna[0])->first();
                        if(empty($select->cod_proc)){ 
                            $dados['cd_usuario']=$request->user()->cd_usuario; 
                            $dados['sn_ativo']='S'; 
                            $dados['created_at'] =date('Y-m-d H:i'); 
                            $XX=DB::table("procedimento")->insert($dados);
                        } 
                    }
                }
                return redirect()->route('procedimento.listar')->with('success', 'Importado com sucesso!');
            }


        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e);
        }
    }
    
    public function searchProcedimento(Request $request) { 

        if ($request->q) {
            $procedimentos = Procedimento::selectRaw("cod_proc as id, concat(cod_proc,' | ',nm_proc) as text ")
                ->where('nm_proc', 'LIKE', '%' . $request->q . '%')
                ->orWhere('cod_proc', 'LIKE', '%' . $request->q . '%')
                ->paginate(20);
        } else {
            $procedimentos = Procedimento::selectRaw("cod_proc as id, concat(cod_proc,' | ',nm_proc) as text ")
                ->paginate(20);
        }
 
        return $procedimentos->items();
    }

}
