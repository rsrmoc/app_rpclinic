<?php

namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Formulario;
use Exception;
use Illuminate\Support\Facades\Validator;

class Formularios extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $formularios = Formulario::where('cd_formulario', $request->b)
                ->orWhere('nm_formulario', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $formularios = Formulario::all();
        }

        return view('rpclinica.formulario.lista', \compact('formularios'));
    }

    public function create()
    {
        return view('rpclinica.formulario.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'conteudo' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            Formulario::create([
                'nm_formulario' => $request->post('nome'),
                'conteudo' => $request->post('conteudo'),
                'sn_ativo' => 'S',
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('formulario.listar')->with('success', 'Formulario cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o formulario.']);
        }
    }

    public function edit(Formulario $formulario) {
        return view('rpclinica.formulario.edit', compact('formulario'));
    }

    public function update(Request $request, Formulario $formulario) {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'conteudo' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $formulario->update([
                'nm_formulario' => $request->post('nome'),
                'conteudo' => $request->post('conteudo'),
                'up_cadastro' => date('Y-m-d H:i:s')
            ]);

            return redirect()->route('formulario.listar')->with('success', 'Formulario atualizado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o formulario.']);
        }
    }

    public function delete(Formulario $formulario)
    {
        try {
            $formulario->delete();
        }
        catch (\Exception $e) {
            abort(500);
        }
    }
}
