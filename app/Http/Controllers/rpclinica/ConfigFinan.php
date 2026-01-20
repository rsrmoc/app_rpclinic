<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Categoria;
use App\Model\rpclinica\CategoriaEmpresa;
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\ContaBancariaEmpresa;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\FinanceiroConfig;
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\Fornecedor;
use App\Model\rpclinica\Setores;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;

class ConfigFinan extends Controller
{




    public function create() {



        $categorias = Categoria::all();
        $Config = FinanceiroConfig::where('cd_config_finan','CF')->first();
        return view('rpclinica.financeiro.config', compact('categorias','Config'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->post(), [
            'cd_cateogria_cartao' => 'required|numeric',
            'cd_categoria_transf' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }


        try {


            $Config = FinanceiroConfig::where('cd_config_finan','CF')->first();
            if(empty($Config)){
                $Codigo=FinanceiroConfig::create([
                    'cd_config_finan' => 'CF',
                    'cd_cateogria_cartao' => $request['cd_cateogria_cartao'],
                    'cd_categoria_transf' => $request['cd_categoria_transf'],
                    'cd_usuario' => $request->user()->cd_usuario
                ]);
            }else{
                $Codigo=FinanceiroConfig::where('cd_config_finan','CF')->update([
                    'cd_cateogria_cartao' => $request['cd_cateogria_cartao'],
                    'cd_categoria_transf' => $request['cd_categoria_transf'],
                    'cd_usuario' => $request->user()->cd_usuario
                ]);
            }




            return redirect()->route('config.finan')->with('success', 'Categorias cadastrada com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a categoria. '.$e->getMessage()]);
        }
    }

}
