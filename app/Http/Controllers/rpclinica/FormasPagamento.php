<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\FormaPagamento;
use Exception;
use Illuminate\Support\Facades\Validator;

class FormasPagamento extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $formas = FormaPagamento::where('cd_forma_pag', $request->b)
                ->orWhere('nm_forma_pag', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $formas = FormaPagamento::all();
        }

        return view('rpclinica.forma_de_pagamento.lista', \compact('formas'));
    }

    public function create(Request $request)
    {
        
        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }

        return view('rpclinica.forma_de_pagamento.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'tipo' => 'required|in:BO,CA,CH,PI,DI',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            FormaPagamento::create([
                'nm_forma_pag' => $request->post('descricao'),
                'sn_ativo' => $request->post('ativo'),
                'tipo' => $request->post('tipo'),
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario,
                'cd_empresa'=> $request->user()->cd_empresa,
            ]);

            return redirect()->route('forma.pag.listar')->with('success', 'Forma de pagamento cadastrada com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a forma de pagameto.']);
        }
    }

    public function edit(Request $request, FormaPagamento $forma) {

        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }

        return view('rpclinica.forma_de_pagamento.edit', compact('forma'));
    }

    public function update(Request $request, FormaPagamento $forma)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'tipo' => 'required|in:BO,CA,CH,PI,DI',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $forma->update([
                'nm_forma_pag' => $request->post('descricao'),
                'sn_ativo' => $request->post('ativo'),
                'tipo' => $request->post('tipo'),
                'up_cadastro' => date('Y-m-d H:i:s'),
                'up_usuario' => $request->user()->cd_usuario,
                'cd_empresa'=> $request->user()->cd_empresa,
            ]);

            return redirect()->route('forma.pag.listar')->with('success', 'Forma de pagamento atualizada com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a forma de pagameto.']);
        }
    }

    public function delete(FormaPagamento $forma) {
        try {
            $forma->delete();
        }
        catch (Exception $e) {
            abort(500);
        }
    }
}
