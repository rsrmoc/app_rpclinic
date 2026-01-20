<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Estoque;
use App\Model\rpclinica\Produto;
use App\Model\rpclinica\ProdutoLote;
use App\Model\rpclinica\Setores;
use App\Model\rpclinica\SolicitacaoSaida;
use App\Model\rpclinica\SolicitacaoSaidaProd;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class EstoqueSaida extends Controller
{
    public function index(Request $request) {
        $setores = Setores::all();
        $estoques = Estoque::all();

        $saidas = SolicitacaoSaida::with('estoque', 'setor')
            ->orderBy('created_at', 'desc')->get();

        if (!empty($request->b)) {
            $saidas = $saidas->where('cd_solicitacao', $request->b);
        }

        if (!empty($request->setor)) {
            $saidas = $saidas->where('cd_setor', $request->setor);
        }

        if (!empty($request->estoque)) {
            $saidas = $saidas->where('cd_estoque', $request->estoque);
        }

        return view(
            'rpclinica.estoqueSaida.listar',
            compact('setores', 'estoques', 'saidas')
        );
    }

    public function create() {
        $estoques = Estoque::all();
        $produtos = Produto::all();
        $setores = Setores::all();
        $lotes = ProdutoLote::all();

        return view(
            'rpclinica.estoqueSaida.add',
            compact('estoques', 'produtos', 'setores', 'lotes')
        );
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'data' => 'required|date_format:Y-m-d',
            'estoque' => 'required|integer|exists:estoque,cd_estoque',
            'setor' => 'required|integer|exists:setor,cd_setor',
            'numero_documento' => 'required|integer',
            'produtos' => 'required|array|min:1',
            'produtos.*.cd_produto' => 'required|integer|exists:produto,cd_produto',
            'produtos.*.cd_lote_produto' => 'sometimes|nullable|integer|exists:produto_lote,cd_lote',
            'produtos.*.qtde' => 'required|integer',
        ]);

        try {
            $solicitacao = SolicitacaoSaida::create([
                'dt_saida' => $validated['data'],
                'cd_estoque' => $validated['estoque'],
                'cd_setor' => $validated['setor'],
                'nr_doc' => $validated['numero_documento'],
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            foreach ($request->produtos as $produto) {
                SolicitacaoSaidaProd::create([
                    'cd_solicitacao' => $solicitacao->cd_solicitacao,
                    'cd_produto' => $produto['cd_produto'],
                    'cd_lote_produto' => $produto['cd_lote_produto'],
                    'qtde' => $produto['qtde'],
                    'dt_lancamento' => $validated['data'],
                    'cd_usuario' => $request->user()->cd_usuario
                ]);
            }

            return redirect()->route('estoque.saida.listar')->with('success', 'Saída cadastrada com sucesso!');
        }
        catch(Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    public function edit(SolicitacaoSaida $saida) {
        $estoques = Estoque::all();
        $produtos = Produto::all();
        $setores = Setores::all();
        $lotes = ProdutoLote::all();

        return view(
            'rpclinica.estoqueSaida.edit',
            compact('estoques', 'produtos', 'setores', 'lotes', 'saida')
        );
    }

    public function update(Request $request, SolicitacaoSaida $saida) {
        $validated = $request->validate([
            'data' => 'required|date_format:Y-m-d',
            'estoque' => 'required|integer|exists:estoque,cd_estoque',
            'setor' => 'required|integer|exists:setor,cd_setor',
            'numero_documento' => 'required|integer',
            'produtos' => 'sometimes|array|min:1',
            'produtos.*.cd_produto' => 'required|integer|exists:produto,cd_produto',
            'produtos.*.cd_lote_produto' => 'sometimes|nullable|integer|exists:produto_lote,cd_lote',
            'produtos.*.qtde' => 'required|integer',
        ]);

        try {
            $saida->update([
                'dt_saida' => $validated['data'],
                'cd_estoque' => $validated['estoque'],
                'cd_setor' => $validated['setor'],
                'nr_doc' => $validated['numero_documento']
            ]);

            if (!empty($request->produtos)) {
                foreach ($request->produtos as $produto) {
                    SolicitacaoSaidaProd::create([
                        'cd_solicitacao' => $saida->cd_solicitacao,
                        'cd_produto' => $produto['cd_produto'],
                        'cd_lote_produto' => $produto['cd_lote_produto'],
                        'qtde' => $produto['qtde'],
                        'dt_lancamento' => $validated['data'],
                        'cd_usuario' => $request->user()->cd_usuario
                    ]);
                }
            }

            return redirect()->route('estoque.saida.listar')->with('success', 'Saída atualizada com sucesso!');
        }
        catch(Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    public function destroy(SolicitacaoSaida $saida)
    {
        try {
            $saida->delete();
        } catch (Exception $e) {
            abort(500);
        }
    }

    // json
    public function jsonShow($cd_solicitacao) {
        try {
            $saida = SolicitacaoSaida::with('saidaProdutos', 'estoque', 'setor')->findOrFail($cd_solicitacao);

            foreach($saida->saidaProdutos as $produto) {
                $produto->load('produto', 'lote');
            }

            return $saida;
        }
        catch(Throwable $th) {
            return response()->json(['message' => 'Não encontrado!', 'error' => $th->getMessage()], 404);
        }
    }
}
