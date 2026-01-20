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
use App\Model\rpclinica\GrupoProcedimento;
use Illuminate\Support\Facades\Route;

class GrupoProcedimentos extends Controller
{

    public function index(Request $request)
    {
         
       // dd(cssRouteCurrent('procedimento.'),Route::currentRouteName(),isTabelaRotas());
  
        if ($request->query('b')) {
            $dados = GrupoProcedimento::orderBy('nm_grupo')
                ->where('cd_proc', $request->b)
                ->orWhere('nm_grupo', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $dados = GrupoProcedimento::orderBy('nm_grupo')->get();
        }

        return view('rpclinica.grupoProcedimento.lista', \compact('dados'));
    }

    public function create() {
        $tuss = Tuss::all();

        return view('rpclinica.grupoProcedimento.add', compact('tuss'));
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'nm_grupo' => 'required|string',
            'tp_grupo' => 'required|string',
            'sn_produto' => 'nullable|string',  
        ]);

        try {
            
            GrupoProcedimento::create([
                'nm_grupo' => $validated['nm_grupo'],
                'tp_grupo' => $validated['tp_grupo'],
                'sn_ativo' => 'S',
                'sn_produto' => ($request['sn_produto']) ? $request['sn_produto'] : null,
                'cd_usuario' => $request->user()->cd_usuario
            ]);
            
       
            return redirect()->route('grupo.procedimento.listar')->with('success', 'Grupo de Procedimento criado com sucesso!');
        }
        catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit(GrupoProcedimento $grupo) { 

        return view('rpclinica.grupoProcedimento.edit', compact('grupo' ));
    }

    public function update(Request $request, GrupoProcedimento $grupo) {
        $validated = $request->validate([
            'nm_grupo' => 'required|string',
            'tp_grupo' => 'required|string',
            'sn_produto' => 'nullable|string',  
            'ativo' => 'nullable|string', 
        ]);

        try {
          
            $grupo->update([
                'nm_grupo' => $request['nm_grupo'],
                'tp_grupo' => $request['tp_grupo'],
                'sn_ativo' =>  (isset($request['ativo'])) ? $request['ativo'] : null,
                'sn_produto' => (isset($request['sn_produto'])) ? $request['sn_produto'] : null,
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('grupo.procedimento.listar')->with('success', 'Alterações salvas com sucesso!');
        }
        catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function delete(Request $request, GrupoProcedimento $grupo)
    {
  
        try {
            $grupo->delete();
        } catch (\Exception $e) {
            abort(500);
        }
    }
 
    


}
