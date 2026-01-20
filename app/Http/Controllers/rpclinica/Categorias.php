<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Categoria;
use App\Model\rpclinica\CategoriaEmpresa;
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\ContaBancariaEmpresa;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\Fornecedor;
use App\Model\rpclinica\Setores;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

class Categorias extends Controller
{

    public function func_estrutural($Codigo = null)
    {
        if($Codigo){

            $NumeroEstrutural = Categoria::whereRaw(" cd_categoria = $Codigo ")->first();
            if(isset($NumeroEstrutural->cod_estrutural)){

                 $Numero=$NumeroEstrutural->cod_estrutural;
                $Numeros[]=0;
                $query = Categoria::whereRaw(" cod_estrutural like '".$Numero.".%' ")->get();
                foreach($query as $val){
                    if( (count(explode('.',$Numero))+1) == count(explode('.',$val->cod_estrutural)) ){
                        $stringCorrigida = str_replace($Numero.'.', '', $val->cod_estrutural);
                        $Numeros[]=$stringCorrigida;
                    }
                }
                $Max = (int)max($Numeros);
                $Pontos = explode('.',$Numero);
                $Espacos = (count($Pontos));

                if( $Espacos <= 1 ){
                    if(strlen(($Max+1)) > 2 ){
                        return false;
                    }else{
                        return $Numero.'.'.str_pad(($Max+1) , 2 , '0' , STR_PAD_LEFT);
                    }

                }else{
                    if(strlen(($Max+1)) > 3 ){
                        return false;
                    }else{
                        return $Numero.'.'.str_pad(($Max+1) , 3 , '0' , STR_PAD_LEFT);
                    }
                }
                //dd($Numero.' || '.$Max);

            }else{
                return false;
            }

        }else{
            $NumeroEstrutural = Categoria::whereRaw(" ifnull(cd_categoria_pai,'') = '' ")->selectRaw(" max(CAST(cod_estrutural AS INT)) cod ")->first();
            $Valor = ($NumeroEstrutural->cod) ? $NumeroEstrutural->cod : 0;
            if(strlen(($Valor+1)) > 1 ){
                return false;
            }else{
                return ($Valor+1);
            }

        }
    }

    public function index(Request $request) {

        //dd(Route::currentRouteName());

        if ($request->query('b')) {
            $categorias = Categoria::where(function ($query) use ($request) {
                    $query->where('cd_categoria', $request->b)
                        ->orWhere('nm_categoria', 'LIKE', "%{$request->b}%");
                })->orderByRaw("
                RPAD( cast(replace(cod_estrutural, '.' , '') as unsigned integer),20,'0'),
                RPAD( cast(replace(cod_estrutural, '.' , '') as unsigned integer),20,'0')
                ")
                ->get();
        }
        else {
            $categorias = Categoria::orderByRaw("
            RPAD( cast(replace(cod_estrutural, '.' , '') as unsigned integer),20,'0'),
            RPAD( cast(replace(cod_estrutural, '.' , '') as unsigned integer),20,'0')
            ")->get();
        }

        return view('rpclinica.categoria.lista', compact('categorias'));
    }

    public function create(Request $request) {
        $categorias = Categoria::all();
        $setor = Setores::all();
        $conta = ContaBancaria::all();
        $forma = FormaPagamento::all();
        $fornecedor = Fornecedor::all();
        $empresa = Empresa::all();

        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }

        return view('rpclinica.categoria.add', compact('categorias','setor','conta','forma','fornecedor','empresa'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->post(), [
            'nm_categoria' => 'required|string',
            'cd_categoria_pai' => 'nullable|exists:categoria,cd_categoria',
            'sn_lancamento' => 'required|in:S,N',
            'tp_lancamento' => 'nullable|string',
            'descricao' => 'nullable|string',
            'cd_conta' => 'nullable',
            'cd_forma' => 'nullable',
            'cd_fornecedor' => 'nullable|exists:fornecedor,cd_fornecedor', 

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        $CodEstrutural = ''; $Espacos = 0;
        $request['cd_categoria_pai'];
        $CodEstrutural = $this->func_estrutural($request['cd_categoria_pai']);
        if($CodEstrutural==false){
            return redirect()->back()->withInput()->withErrors(['error' => 'Erro na estrutura do codigo!']);
        }
        $Pontos = explode('.',$CodEstrutural);
        $Espacos= (count($Pontos)-1);

        try {

            Categoria::create([
                'cd_categoria_pai' => $request['cd_categoria_pai'],
                'cod_estrutural' => $CodEstrutural,
                'espacos' => $Espacos,
                'nm_categoria' => $request['nm_categoria'],
                'sn_lancamento' => $request['sn_lancamento'],
                'tp_lancamento' => $request['tp_lancamento'],
                'descricao' => $request['descricao'],
                'cd_conta' => $request['cd_conta'],
                'cd_forma' => $request['cd_forma'],
                'cd_fornecedor' => $request['cd_fornecedor'],
                'sn_ativo' => 'S',
                'cd_usuario' => $request->user()->cd_usuario,
                'cd_empresa' => $request->user()->cd_empresa
            ]);
 
            return redirect()->route('categoria.listar')->with('success', 'Categoria cadastrada com sucesso!');
            
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a categoria. '.$e->getMessage()]);
        }
       
    }

    public function edit(Request $request, Categoria $categoria) {

        $categorias = Categoria::all();
        $setor = Setores::all();
        $conta = ContaBancaria::all();
        $forma = FormaPagamento::all();
        $fornecedor = Fornecedor::all();
        $empresas = Empresa::all();
        $Emp = null;
        $cd_Set = null;

        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }

    
        return view('rpclinica.categoria.edit', compact('categorias','setor','conta','forma','fornecedor','empresas','categoria' ));

    }

    public function update(Request $request, Categoria $categoria) {
        $validator = Validator::make($request->post(), [
            'nm_categoria' => 'required|string',
            'cd_categoria_pai' => 'nullable|exists:categoria,cd_categoria',
            'sn_lancamento' => 'required|in:S,N',
            'tp_lancamento' => 'nullable|string',
            'descricao' => 'nullable|string',
            'cd_conta' => 'nullable',
            'cd_forma' => 'nullable', 
            'cd_fornecedor' => 'nullable|exists:fornecedor,cd_fornecedor', 
        ]);
 
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        $Update = array(
            'cd_categoria_pai' => $request['cd_categoria_pai'],
            'nm_categoria' => $request['nm_categoria'],
            'sn_lancamento' => $request['sn_lancamento'],
            'tp_lancamento' => $request['tp_lancamento'],
            'descricao' => $request['descricao'],
            'cd_conta' => $request['cd_conta'],
            'cd_forma' => $request['cd_forma'],
            'cd_fornecedor' => $request['cd_fornecedor'],
            'cd_usuario' => $request->user()->cd_usuario
        );

        if($request['cd_categoria_pai']<>$request['categoria_pai']){
            $CodEstrutural = ''; $Espacos = 0;
            $request['cd_categoria_pai'];
            $CodEstrutural = $this->func_estrutural($request['cd_categoria_pai']);
            $Pontos = explode('.',$CodEstrutural);
            $Espacos= (count($Pontos)-1);

            $Update['espacos'] = $Espacos;
            $Update['cod_estrutural'] = $CodEstrutural;

        }
        try {
            $categoria->update($Update);

  
            return redirect()->route('categoria.listar')->with('success', 'Categoria atualizada com sucesso!');

        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a categoria. '.$e->getMessage()]);
        }
    }

    public function delete(Categoria $categoria) {
        try {
            $categoria->delete();
        }
        catch(Exception $e) {
            abort(500);
        }
    }
}
