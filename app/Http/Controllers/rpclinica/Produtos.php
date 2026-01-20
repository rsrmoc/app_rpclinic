<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Produto;
use Exception;
use Illuminate\Support\Facades\Validator;

class Produtos extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $produtos = Produto::where('cd_produto', $request->b)
                ->orWhere('nm_produto', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $produtos = Produto::all();
        }

        return view('rpclinica.produto.lista', compact('produtos'));
    }

    public function create()
    {
        $classificacoes = Classificacao::all();
        $mestre = Produto::whereRaw("ifnull(cd_mestre,'')<>''")->orderBy("nm_produto")->get();
        $proc = Procedimento::with(['grupo'])->orderBy("cod_proc")->get();

        return view('rpclinica.produto.add', compact('classificacoes','mestre','proc'));
    }

    public function store(Request $request) {
        $validator =  Validator::make($request->all(), [
            "nome" => "required|string",
            "classificacao" => "required|integer|exists:classificacao,cd_classificacao",
            "cd_mestre" => "nullable",
            "cd_proc" => "nullable",
            "xyz" => "required|in:X,Y,Z", 
            "abc" => "nullable|in:S",
            "opme" => "nullable|in:S",
            "medicamento" => "nullable|in:S",
            "lote" => "nullable|in:S,N",
            "bloqueia" => "nullable|in:S",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            Produto::create([
                'nm_produto' => $request->post('nome'),
                'cd_mestre' => $request->post('cd_mestre'),
                'cd_proc' => $request->post('cd_proc'),
                'cd_classificacao' => $request->post('classificacao'),
                'classificacao_xyz' =>  ($request->post('xyz')) ? $request->post('xyz') : 'N',
                'classificacao_abc' =>  ($request->post('abc')) ? $request->post('abc') : 'N',
                'sn_medicamento' => $request->post('medicamento'),
                'sn_opme' => ($request->post('opme')) ? $request->post('opme') : 'N',
                'sn_lote' =>  ($request->post('lote')) ? $request->post('lote') : 'N',
                'sn_ativo' =>  ($request->post('bloqueia')) ? $request->post('bloqueia') : 'N',
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('produto.listar')->with('success', 'Produto cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o produto. '.$e->getMessage()]);
        }
    }

    public function edit(Produto $produto)
    {
        $classificacoes = Classificacao::all();
        $mestre = Produto::whereRaw("ifnull(cd_mestre,'')<>''")->orderBy("nm_produto")->get();
        $proc = Procedimento::with(['grupo'])->orderBy("cod_proc")->get();
        return view('rpclinica.produto.edit', compact('classificacoes', 'produto','mestre','proc'));
    }

    public function update(Request $request, Produto $produto) {
        $validator =  Validator::make($request->all(), [
            "nome" => "required|string",
            "classificacao" => "required|integer|exists:classificacao,cd_classificacao",
            "cd_mestre" => "nullable",
            "cd_proc" => "nullable",
            "xyz" => "required|in:X,Y,Z", 
            "abc" => "nullable|in:S",
            "opme" => "nullable|in:S",
            "medicamento" => "nullable|in:S",
            "lote" => "nullable|in:S,N",
            "bloqueia" => "nullable|in:S",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $produto->update([
                'cd_mestre' => $request->post('cd_mestre'),
                'cd_proc' => $request->post('cd_proc'),
                'cd_classificacao' => $request->post('classificacao'),
                'classificacao_xyz' =>  ($request->post('xyz')) ? $request->post('xyz') : 'N',
                'classificacao_abc' =>  ($request->post('abc')) ? $request->post('abc') : 'N',
                'sn_medicamento' => $request->post('medicamento'),
                'sn_opme' => ($request->post('opme')) ? $request->post('opme') : 'N',
                'sn_lote' =>  ($request->post('lote')) ? $request->post('lote') : 'N',
                'sn_ativo' =>  ($request->post('bloqueia')) ? $request->post('bloqueia') : 'N', 
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('produto.listar')->with('success', 'Produto atualizado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o produto. '.$e->getMessage()]);
        }
    }

    public function delete(Produto $produto) {
        try {
            $produto->delete();
        }
        catch(Exception $e) {
            abort(500);
        }
    }
}
