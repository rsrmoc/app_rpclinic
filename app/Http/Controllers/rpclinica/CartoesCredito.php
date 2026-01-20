<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\CartaoCredito;
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\ContaBancariaEmpresa;
use App\Model\rpclinica\Empresa;
use Exception;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class CartoesCredito extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $cartoes = ContaBancaria::where('cd_conta', $request->b)
                ->where('sn_cartao','S')
                ->orWhere('nm_conta', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $cartoes = ContaBancaria::where('sn_cartao','S')->orderBY("nm_conta")->get();
        }

        return view('rpclinica.cartao_de_credito.lista', \compact('cartoes'));
    }

    public function create(Request $request)
    {
        
        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }

        return view('rpclinica.cartao_de_credito.add' );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "numero" => "required",
            "dia_fechamento" => "required|integer",
            "dia_vencimento" => "required|integer",
            "valor_limite" => "required|currency", 
            "resumo" => "required|in:S,N",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

       
            $Codigo = ContaBancaria::create([
                'nm_conta' => $request->post('numero'),
                'tp_conta' => 'CA',
                'dia_fechamento' => $request->post('dia_fechamento'),
                'dia_vencimento' => $request->post('dia_vencimento'),
                'vl_limite' => formatCurrencyForDB( $request->post('valor_limite') ),
                'sn_exibir_resumo' => $request->post('resumo'), 
                'sn_ativo' => 'S',
                'sn_cartao' => 'S',
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario,
                'cd_empresa'=> $request->user()->cd_empresa,
            ]);
   
            return redirect()->route('cartao.credito.listar')->with('success', 'Novo cartão cadastrado com sucesso!');
        
        }
        
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o cartão.' ]);
        }
          
    }

    public function edit(Request $request, ContaBancaria $cartao) { 

        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }

        return view('rpclinica.cartao_de_credito.edit', compact('cartao' ));
    }

    public function update(Request $request, ContaBancaria $cartao) {
        $validator = Validator::make($request->all(), [
            "numero" => "required",
            "dia_fechamento" => "required|numeric",
            "dia_vencimento" => "required|numeric",
            "valor_limite" => "required|currency", 
            "resumo" => "required|in:S,N",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        //dd($request->post('valor_limite'));

        try {
            
            $cartao->update([ 

                'nm_conta' => $request->post('numero'),
                'tp_conta' => 'CA',
                'dia_fechamento' => $request->post('dia_fechamento'),
                'dia_vencimento' => $request->post('dia_vencimento'),
                'vl_limite' => formatCurrencyForDB( $request->post('valor_limite') ),
                'sn_exibir_resumo' => $request->post('resumo'), 
                'sn_ativo' => ($request->post('ativo')=='S') ? 'S' : 'N' ,
                'sn_cartao' => 'S',
                'up_cadastro' => date('Y-m-d H:i:s'),
                'up_usuario' => $request->user()->cd_usuario,
                'cd_empresa'=> $request->user()->cd_empresa,
            ]);

           

            return redirect()->route('cartao.credito.listar')->with('success', 'Cartão atualizado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o cartão.']);
        }
    }

    public function delete(CartaoCredito $cartao) {
        try {
            $cartao->delete();
        }
        catch (Exception $e) {
            abort(500);
        }
    }
}
