<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\DevolucaoEstoque;
use App\Model\rpclinica\DevolucaoEstoqueProduto;
use App\Model\rpclinica\Estoque;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Throwable;

class Devolucao extends Controller
{
    public function index(Request $request) {
        $estoques = Estoque::all();
        $devolucoes = DevolucaoEstoque::with('solicitacaoSaida')->get();

        if (!empty($request->estoque)) {
            $devolucoes = DevolucaoEstoque::whereHas('solicitacaoSaida', function (Builder $query) use ($request) {
                $query->where('cd_estoque', $request->estoque);
            })->get();
        }

        if (!empty($request->b)) {
            $devolucoes = $devolucoes->where('cd_devolucao', $request->b);
        }

        if (!empty($request->data)) {
            $devolucoes = $devolucoes->where('dt_devolucao', $request->data);
        }

        if (!empty($request->saida)) {
            $devolucoes = $devolucoes->where('cd_solicitacao_saida', $request->saida);
        }

        return view('rpclinica.devolucao.listar', compact('estoques', 'devolucoes'));
    }

    public function create() {
        return view('rpclinica.devolucao.add');
    }

    public function store(Request $request) {
        $request->validate([
            'cd_solicitacao' => 'required|integer|exists:solicitacao_saida,cd_solicitacao',
            'produtos' => 'required|array|min:1',
            'produtos.*.cd_produto' => 'required|integer|exists:produto,cd_produto',
            'produtos.*.cd_lote_produto' => 'sometimes|integer|exists:produto_lote,cd_lote',
            'produtos.*.qtde' => 'required|integer'
        ]);

        try {

            $lancamento = date('Y-m-d');

            $devolucao = DevolucaoEstoque::create([
                'dt_devolucao' => $lancamento,
                'cd_solicitacao_saida' => $request->cd_solicitacao,
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            foreach ($request->produtos as $produto) {
                DevolucaoEstoqueProduto::create([
                    'cd_devolucao' => $devolucao->cd_devolucao,
                    'cd_produto' => $produto['cd_produto'],
                    'cd_lote_produto' => $produto['cd_lote_produto'],
                    'qtde' => $produto['qtde'],
                    'cd_usuario' => $request->user()->cd_usuario,
                    'dt_lancamento' => $lancamento
                ]);
            }

            return redirect()->route('estoque.devolucao.listar')->with('success', 'Devolução cadastrada com sucesso!');
        }
        catch(Throwable $th) {
            return back()->withErrors($th->getMessage());
        }
    }

    public function edit(DevolucaoEstoque $devolucao) {
        $devolucao->load('solicitacaoSaida', 'devolucoesProdutos');
        $devolucao->solicitacaoSaida->load('estoque', 'setor', 'saidaProdutos');
        $devolucao->solicitacaoSaida->saidaProdutos->load('produto', 'lote');

        return view('rpclinica.devolucao.edit', compact('devolucao'));
    }

    public function update(Request $request) {
        // dd($request->post());

        $request->validate([
            'produtos' => 'required|array|min:1',
            'produtos.*.cd_devolucao_prod' => 'required|integer|exists:devolucao_estoque_prod,cd_devolucao_prod',
            'produtos.*.qtde' => 'required|integer'
        ]);

        try {
            foreach ($request->produtos as $produto) {
                DevolucaoEstoqueProduto::find($produto['cd_devolucao_prod'])->update(['qtde' => $produto['qtde']]);
            }

            return redirect()->route('estoque.devolucao.listar')->with('success', 'Devolução atualizada com sucesso!');
        }
        catch(Throwable $th) {
            return back()->withErrors($th->getMessage());
        }
    }

    public function destroy(DevolucaoEstoque $devolucao) {
        try {
            $devolucao->delete();
        }
        catch(Throwable $th) {
            abort(500);
        }
    }
}
