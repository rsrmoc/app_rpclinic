<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Categoria;
use App\Model\rpclinica\Empresa;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class Config extends Controller
{

    public function index(Request $request) {

        try {

            $empresa = Empresa::find($request->user()->cd_empresa);
            $dados['query']=DB::select("
                select * from 
                (select TABLES.TABLE_NAME,TABLES.ENGINE,TABLES.TABLE_COLLATION 
                 from INFORMATION_SCHEMA.TABLES
                 where TABLES.TABLE_SCHEMA = '" . $empresa->check_bd ."') viewTableBase
                left join (
                SELECT TABLE_NAME TABLE_NAME_BD,ENGINE ENGINE_BD,TABLE_COLLATION TABLE_COLLATION_BD
                FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_SCHEMA in (SELECT database())
                ) table_atual on table_atual.TABLE_NAME_BD=viewTableBase.TABLE_NAME
                order by 1
            ");
      
            $dados['colunas'] = DB::select("
            select * from (
            	select TABLE_PRINCIPAL.TABLE_SCHEMA ,TABLE_PRINCIPAL.TABLE_NAME ,TABLE_PRINCIPAL.COLUMN_NAME ,TABLE_PRINCIPAL.COLUMN_DEFAULT,
	            TABLE_PRINCIPAL.COLUMN_TYPE,TABLE_PRINCIPAL.COLUMN_KEY,TABLE_PRINCIPAL.EXTRA,
                concat(TABLE_PRINCIPAL.TABLE_NAME,TABLE_PRINCIPAL.COLUMN_NAME,TABLE_PRINCIPAL.COLUMN_DEFAULT,TABLE_PRINCIPAL.COLUMN_TYPE,TABLE_PRINCIPAL.COLUMN_KEY,TABLE_PRINCIPAL.EXTRA) AS CODIGO 
                from INFORMATION_SCHEMA.COLUMNS TABLE_PRINCIPAL where TABLE_PRINCIPAL.TABLE_SCHEMA = '" . $empresa->check_bd ."'
            ) viewColumnsBase
            where concat(viewColumnsBase.table_name,viewColumnsBase.column_name) not in
            (  SELECT concat(TABLE_NAME,COLUMN_NAME)
               FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ( SELECT database() )
            )
            order by 2,3
            ");

            $dados['itquery'] = DB::select("
            SELECT distinct(TABLE_SCHEMA) TABLE_SCHEMA,TABLE_SCHEMA_PRINC,TABLE_NAME,TABLE_NAME_PRINC,
            COLUMN_NAME,COLUMN_NAME_PRINC,COLUMN_DEFAULT,COLUMN_DEFAULT_PRINC,COLUMN_TYPE,COLUMN_TYPE_PRINC,
            COLUMN_KEY,COLUMN_KEY_PRINC,EXTRA, EXTRA_PRINC
            from 
            (
                select TABLE_PRINCIPAL.TABLE_SCHEMA ,TABLE_PRINCIPAL.TABLE_NAME ,TABLE_PRINCIPAL.COLUMN_NAME ,TABLE_PRINCIPAL.COLUMN_DEFAULT,
	            TABLE_PRINCIPAL.COLUMN_TYPE,TABLE_PRINCIPAL.COLUMN_KEY,TABLE_PRINCIPAL.EXTRA,
                concat(TABLE_PRINCIPAL.TABLE_NAME,TABLE_PRINCIPAL.COLUMN_NAME,TABLE_PRINCIPAL.COLUMN_DEFAULT,TABLE_PRINCIPAL.COLUMN_TYPE,TABLE_PRINCIPAL.COLUMN_KEY,TABLE_PRINCIPAL.EXTRA) AS CODIGO 
                from INFORMATION_SCHEMA.COLUMNS TABLE_PRINCIPAL where TABLE_PRINCIPAL.TABLE_SCHEMA = '" . $empresa->check_bd ."'
            ) viewColumnsBase
            INNER JOIN (
            SELECT TABLE_SCHEMA TABLE_SCHEMA_PRINC,TABLE_NAME TABLE_NAME_PRINC,COLUMN_NAME COLUMN_NAME_PRINC,COLUMN_DEFAULT COLUMN_DEFAULT_PRINC,
            COLUMN_TYPE COLUMN_TYPE_PRINC,COLUMN_KEY COLUMN_KEY_PRINC,EXTRA EXTRA_PRINC
            FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ( SELECT database() )
            AND CONCAT(TABLE_SCHEMA,TABLE_NAME,COLUMN_NAME,COLUMN_DEFAULT,COLUMN_TYPE,COLUMN_KEY,EXTRA) IS NOT NULL
            ) RPCLINIC ON RPCLINIC.COLUMN_NAME_PRINC = viewColumnsBase.COLUMN_NAME
                and viewColumnsBase.TABLE_NAME = RPCLINIC.TABLE_NAME_PRINC
            where CONCAT(TABLE_NAME,COLUMN_NAME,COLUMN_DEFAULT,COLUMN_TYPE,COLUMN_KEY,EXTRA) NOT IN
            (
            SELECT CONCAT(TABLE_NAME,COLUMN_NAME,COLUMN_DEFAULT,COLUMN_TYPE,COLUMN_KEY,EXTRA)
            FROM INFORMATION_SCHEMA.COLUMNS WHERE  TABLE_SCHEMA =  ( SELECT database() )
            AND CONCAT(TABLE_SCHEMA,TABLE_NAME,COLUMN_NAME,COLUMN_DEFAULT,COLUMN_TYPE,COLUMN_KEY,EXTRA) IS NOT NULL
            )
            order by 2,3
            ");

 
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel visualizar a pagina de configuração. '.$e->getMessage()]);
        }

        return view('rpclinica.config.lista', compact('dados'));
    }



    public function store(Request $request) {
        $validator = Validator::make($request->post(), [
            'nome' => 'required|string',
            'extrutura' => [
                'required',
                Rule::in(array_column(EXTRUTURAL_CATEGORIA, 'COD'))
            ],
            'lancamento' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        $operacao = array_column( array_filter(EXTRUTURAL_CATEGORIA, fn($val) => $val['COD']==$request->post('extrutura')), 'OPERACAO')[0];

        try {
            Categoria::create([
                'cod_extrutural' => $request->post('extrutura'),
                'nm_categoria' => $request->post('nome'),
                'op_categoria' => $operacao,
                'sn_lancamento' => $request->post('lancamento'),
                'sn_ativo' => 'S',
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('categoria.listar')->with('success', 'Categoria cadastrada com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a categoria. '.$e->getMessage()]);
        }
    }

    public function edit(Categoria $categoria) {
        return view('rpclinica.categoria.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria) {
        $validator = Validator::make($request->post(), [
            'nome' => 'required|string',
            'extrutura' => [
                'required',
                Rule::in(array_column(EXTRUTURAL_CATEGORIA, 'COD'))
            ],
            'lancamento' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        $operacao = array_column( array_filter(EXTRUTURAL_CATEGORIA, fn($val) => $val['COD']==$request->post('extrutura')), 'OPERACAO')[0];

        try {
            $categoria->update([
                'cod_extrutural' => $request->post('extrutura'),
                'nm_categoria' => $request->post('nome'),
                'op_categoria' => $operacao,
                'sn_lancamento' => $request->post('lancamento'),
                'up_cadastro' => date('Y-m-d H:i:s'),
                'up_usuario' => $request->user()->cd_usuario
            ]);

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
