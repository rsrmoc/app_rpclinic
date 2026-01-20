<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Fornecedor;
use App\Model\rpclinica\Procedimento;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Fornecedores extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $fornecedores = Fornecedor::where('cd_fornecedor', $request->b)
                ->orWhere('nm_fornecedor', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $fornecedores = Fornecedor::all();
        }

        return view('rpclinica.fornecedor_e_cliente.lista', \compact('fornecedores'));
    }

    public function create()
    {
        return view('rpclinica.fornecedor_e_cliente.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "fornecedor" => "required|string",
            "razao" => "nullable|string",
            "tipo" => "required|in:PF,PJ",
            "tipo_cadastro" => "required|in:F,C,A",
            "documento" => "nullable|string|cpf_ou_cnpj",
            "email" => "nullable|string|email",
            "contato" => "nullable|string",
            "telefone" => "nullable|string",
            "celular" => "nullable|string",
            "end" => "nullable|string",
            "conta_bancaria" => "nullable|string",
            "pix" => "nullable|string",
            "cbo" => "nullable|string" 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            Fornecedor::create([
                'nm_fornecedor' => $request->post('fornecedor'),
                'nm_razao' => $request->post('razao'),
                'tp_pessoa' => $request->post('tipo'),
                'tp_cadastro' => $request->post('tipo_cadastro'),
                'documento' => $request->post('documento'),
                'contato' => $request->post('contato'),
                'cbo' => $request->post('cbo'),
                'telefone' => $request->post('telefone'),
                'celular' => $request->post('celular'),
                'email' => $request->post('email'),
                'whast' => $request->post('whast'),
                'conta_bancaria' => $request->post('contabancaria'),
                'conta_bancaria' => $request->post('razao'),
                'banco' => $request->post('banco'),
                'agencia' => $request->post('agencia'),
                'tp_pix' => $request->post('tp_pix'),
                'pix' => $request->post('pix'),
                'numero' => $request->post('numero'),
                'cep' => $request->post('cep'),
                'end' => $request->post('end'),
                'bairro' => $request->post('bairro'),
                'cidade' => $request->post('cidade'),
                'uf' => $request->post('uf'),
                'obs' => $request->post('obs'),
                'sn_ativo' =>  'S',
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('fornecedor.listar')->with('success', 'Fornecedor cadastrado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o fornecedor. ' . $e->getMessage()]);
        }
    }

    public function edit(Fornecedor $fornecedor)
    {
        return view('rpclinica.fornecedor_e_cliente.edit', compact('fornecedor'));
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $validator = Validator::make($request->all(), [
            "fornecedor" => "required|string",
            "razao" => "nullable|string",
            "tipo" => "required|in:PF,PJ",
            "tipo_cadastro" => "required|in:F,C,A",
            "documento" => "nullable|string|cpf_ou_cnpj",
            "email" => "nullable|string|email",
            "contato" => "nullable|string",
            "telefone" => "nullable|string",
            "celular" => "nullable|string",
            "end" => "nullable|string",
            "conta_bancaria" => "nullable|string",
            "ativo" => "required|in:S,N"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $fornecedor->update([
                'nm_fornecedor' => $request->post('fornecedor'),
                'nm_razao' => $request->post('razao'),
                'tp_pessoa' => $request->post('tipo'),
                'tp_cadastro' => $request->post('tipo_cadastro'),
                'documento' => $request->post('documento'),
                'contato' => $request->post('contato'),
                'telefone' => $request->post('telefone'),
                'celular' => $request->post('celular'),
                'email' => $request->post('email'),
                'end' => $request->post('end'),
                'conta_bancaria' => $request->post('razao'),
                'sn_ativo' => $request->post('ativo'),
                'up_cadastro' => date('Y-m-d H:i:s'),
                'whast' => $request->post('whast'),
                'conta_bancaria' => $request->post('contabancaria'),
                'conta_bancaria' => $request->post('razao'),
                'banco' => $request->post('banco'),
                'agencia' => $request->post('agencia'),
                'tp_pix' => $request->post('tp_pix'),
                'pix' => $request->post('pix'),
                'numero' => $request->post('numero'),
                'cep' => $request->post('cep'),
                'end' => $request->post('end'),
                'bairro' => $request->post('bairro'),
                'cidade' => $request->post('cidade'),
                'uf' => $request->post('uf'),
                'obs' => $request->post('obs'),
                'cbo' => $request->post('cbo'),
                'up_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('fornecedor.listar')->with('success', 'Fornecedor atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o fornecedor. ' . $e->getMessage()]);
        }
    }

    public function delete(Fornecedor $fornecedor)
    {
        try {
            $fornecedor->delete();
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
