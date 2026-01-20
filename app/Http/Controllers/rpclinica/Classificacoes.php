<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Procedimento;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Classificacoes extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $classificacoes = Classificacao::where('cd_classificacao', $request->b)
                ->orWhere('nm_classificacao', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $classificacoes = Classificacao::all();
        }

        return view('rpclinica.classificacao.lista', compact('classificacoes'));
    }

    public function create()
    {
        return view('rpclinica.classificacao.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            Classificacao::create([
                'nm_classificacao' => $request->post('nome'),
                'sn_ativo' => $request->post('ativo'),
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('classificacao.listar')->with('success', 'Classificação cadastrada com sucesso.');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a classificação. '.$e->getMessage()]);
        }
    }

    public function edit(Classificacao $classificacao) {
        return view('rpclinica.classificacao.edit', compact('classificacao'));
    }

    public function update(Request $request, Classificacao $classificacao)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $classificacao->update([
                'nm_classificacao' => $request->post('nome'),
                'sn_ativo' => $request->post('ativo'),
                'up_cadastro' => date('Y-m-d H:i:s'),
                'up_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('classificacao.listar')->with('success', 'Classificação atualizada com sucesso.');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a classificação. '.$e->getMessage()]);
        }
    }

    public function delete(Classificacao $classificacao)
    {
        try {
            $classificacao->delete();
        }
        catch (Exception $e) {
            abort(500);
        }
    }

}
