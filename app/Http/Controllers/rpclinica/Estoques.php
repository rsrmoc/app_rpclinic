<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Estoque;
use Exception;
use Illuminate\Support\Facades\Validator;

class Estoques extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $estoques = Estoque::where('cd_estoque', $request->b)
                ->orWhere('nm_estoque', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $estoques = Estoque::all();
        }

        return view('rpclinica.estoque.lista', compact('estoques'));
    }

    public function create()
    {
        return view('rpclinica.estoque.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            Estoque::create([
                'nm_estoque' => $request->post('descricao'),
                'sn_ativo' => $request->post('ativo'),
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('tab-estoque.listar')->with('success', 'Estoque cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o estoque. '.$e->getMessage()]);
        }
    }

    public function edit(Estoque $estoque)
    {
        return view('rpclinica.estoque.edit', compact('estoque'));
    }

    public function update(Request $request, Estoque $estoque)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'required|string',
            'ativo' => 'required|in:S,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $estoque->update([
                'nm_estoque' => $request->post('descricao'),
                'sn_ativo' => $request->post('ativo'),
                'up_cadastro' => date('Y-m-d H:i:s'),
                'up_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('tab-estoque.listar')->with('success', 'Estoque atualizado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o estoque. '.$e->getMessage()]);
        }
    }

    public function delete(Estoque $estoque)
    {
        try {
            $estoque->delete();
        }
        catch (\Exception $e) {
            abort(500);
        }
    }
}
