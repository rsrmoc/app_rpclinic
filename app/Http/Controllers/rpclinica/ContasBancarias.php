<?php

namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\ContaBancariaEmpresa;
use App\Model\rpclinica\ContaTipo;
use App\Model\rpclinica\Empresa;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

class ContasBancarias extends Controller
{

    public function index(Request $request) {


        if ($request->query('b')) {
            $contas = ContaBancaria::where('cd_conta', $request->b)
            ->with('tab_tipo')
                ->orWhere('nm_conta', 'LIKE', "%{$request->b}%")
                ->whereRaw("ifnull(sn_cartao,'N')<>'S' ")->orderBy('nm_conta')->get();
        } else {
            $contas = ContaBancaria::whereRaw("ifnull(sn_cartao,'N')<>'S' ")->with('tab_tipo')->orderBy('nm_conta')->get();
        }
        return view('rpclinica.conta_bancaria.lista', compact('contas'));
    }

    public function create(Request $request) {
        $empresas = Empresa::all();
        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }
        $contaTipo=ContaTipo::orderBy("nm_tipo_conta")->get();
        return view('rpclinica.conta_bancaria.add', compact('empresas','contaTipo'));
    }

    public function store(Request $request) {

        $validator =  Validator::make($request->all(), [
            "numero_da_conta" => "required",
            "tp_conta" => "required", 
            "saldo_inicial" => "nullable|currency",
            "dt_saldo" => "nullable|date",
            "tp_saldo" => "nullable|nullable", 
        ]);

        
        $validator->sometimes(['saldo_inicial'], 'required', function($input) {
            return $input->tp_saldo <> null;
        });
        $validator->sometimes(['saldo_inicial'], 'required', function($input) {
            return $input->dt_saldo <> null;
        });

        $validator->sometimes(['dt_saldo'], 'required', function($input) {
            return $input->tp_saldo <> null;
        });
        $validator->sometimes(['dt_saldo'], 'required', function($input) {
            return $input->saldo_inicial <> null;
        });

        $validator->sometimes(['tp_saldo'], 'required', function($input) {
            return $input->saldo_inicial <> null;
        });
        $validator->sometimes(['tp_saldo'], 'required', function($input) {
            return $input->saldo_inicial <> null;
        });
        
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        } 
        try {
           $Codigo =  ContaBancaria::create([
                'nm_conta' => $request->post('numero_da_conta'),
                'sn_investimento' => ($request->post('investimento')=='S') ? 'S' : 'N' ,
                'sn_exibir_resumo' => ($request->post('exibir_resumo')=='S') ? 'S' : 'N' ,
                'saldo_inicial' => formatCurrencyForDB( $request->post('saldo_inicial') ),
                'sn_ativo' => 'S',
                'dt_saldo' => $request->post('dt_saldo'),
                'tp_saldo' => $request->post('tp_saldo'),
                'tp_conta' => $request->post('tp_conta'),
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_empresa'=> $request->user()->cd_empresa,
                'cd_usuario' => $request->user()->cd_usuario
            ]);
            $Codigo=$Codigo->cd_conta;
         
 
            return redirect()->route('conta.bancaria.listar')->with('success', 'Conta casdastrada com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a conta.']);
        }

    }

    public function edit(Request $request, ContaBancaria $conta) {
 
        $Emp = null;
        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }
 
        $contaTipo=ContaTipo::orderBy("nm_tipo_conta")->get();
 
        return view('rpclinica.conta_bancaria.edit', compact('conta','contaTipo'));
    }

    public function update(Request $request, ContaBancaria $conta) {
        $validator =  Validator::make($request->all(), [
            "numero_da_conta" => "required", 
            "saldo_inicial" => "required|currency", 
            "tp_conta" => "required", 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $conta->update([
                'nm_conta' => $request->post('numero_da_conta'),
                'sn_investimento' => ($request->post('investimento')=='S') ? 'S' : 'N' ,
                'sn_exibir_resumo' => ($request->post('exibir_resumo')=='S') ? 'S' : 'N' , 
                'saldo_inicial' => formatCurrencyForDB( $request->post('saldo_inicial') ),
                'tp_conta' => $request->post('tp_conta'),
                'sn_ativo' => ($request->post('ativo')=='S') ? 'S' : 'N' ,
                'tp_conta' => $request->post('tp_conta'),
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario,
                'cd_empresa'=> $request->user()->cd_empresa,
            ]);
       

            return redirect()->route('conta.bancaria.listar')->with('success', 'Conta atualizada com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a conta.']);
        }
    }

    public function delete(ContaBancaria $conta) {
        try {
            $conta->delete();
        }
        catch(Exception $e) {
            abort(500);
        }
    }
}
