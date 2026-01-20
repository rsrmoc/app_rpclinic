<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\AjusteEstoque;
use App\Model\rpclinica\AjusteEstoqueProduto;
use App\Model\rpclinica\Estoque;
use App\Model\rpclinica\Produto;
use App\Model\rpclinica\ProdutoLote;
use App\Model\rpclinica\Setores;
use App\Model\rpclinica\TipoAjuste;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class EstoqueAjuste extends Controller
{
    public function index(Request $request) {
        $estoques = Estoque::all();
        $tiposAjuste = TipoAjuste::all();
        $setores = Setores::all();

        $ajustes = AjusteEstoque::with('estoque', 'setor', 'tipoAjuste')->get();

        if (!empty($request->b)) {
            $ajustes = $ajustes->where('cd_ajuste', $request->b);
        }

        if (!empty($request->estoque)) {
            $ajustes = $ajustes->where('cd_cd_estoque', $request->estoque);
        }

        if (!empty($request->setor)) {
            $ajustes = $ajustes->where('cd_setor', $request->setor);
        }

        if (!empty($request->tipo_ajuste)) {
            $ajustes = $ajustes->where('cd_tipo_ajuste', $request->tipo_ajuste);
        }

        if (!empty($request->data)) {
            $ajustes = $ajustes->where('dt_ajuste', $request->data);
        }

        return view('rpclinica.estoqueAjuste.listar', compact('estoques', 'tiposAjuste', 'setores', 'ajustes'));
    }

    public function create() {
        $estoques = Estoque::all();
        $tiposAjuste = TipoAjuste::all();
        $setores = Setores::all();

        $produtos = Produto::all();
        $lotes = ProdutoLote::all();

        return view(
            'rpclinica.estoqueAjuste.add',
            compact('estoques', 'tiposAjuste', 'setores', 'produtos', 'lotes')
        );
    }

    public function store(Request $request) {
        $request->validate([
            'estoque' => 'required|integer|exists:estoque,cd_estoque',
            'tipo_de_ajuste' => 'required|integer|exists:tipo_ajuste,cd_tipo_ajuste',
            'setor' => 'required|integer|exists:setor,cd_setor',
            'numero_documento' => 'required|integer',
            'produtos' => 'required|array|min:1',
            'produtos.*.cd_produto' => 'required|integer|exists:produto,cd_produto',
            'produtos.*.cd_lote_produto' => 'sometimes|nullable|integer|exists:produto_lote,cd_lote',
            'produtos.*.qtde' => 'required|integer',
        ]);

        try {
            $ajuste = AjusteEstoque::create([
                'cd_estoque' => $request->estoque,
                'cd_tipo_ajuste' => $request->tipo_de_ajuste,
                'cd_setor' => $request->setor,
                'nr_doc' => $request->numero_documento,
                'dt_ajuste' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            foreach($request->produtos as $produto) {
                AjusteEstoqueProduto::create([
                    'cd_ajuste' => $ajuste->cd_ajuste,
                    'cd_produto' => $produto['cd_produto'],
                    'cd_lote_prod' => $produto['cd_lote_produto'],
                    'qtde' => $produto['qtde'],
                    'dt_lancamento' => date('Y-m-d H:i:s'),
                    'cd_usuario' => $request->user()->cd_usuario
                ]);
            }

            return redirect()->route('estoque.ajuste.listar')->with('success', 'Ajuste de estoque cadastrado com sucesso!');
        }
        catch(Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    public function edit(AjusteEstoque $ajuste) {
        $estoques = Estoque::all();
        $tiposAjuste = TipoAjuste::all();
        $setores = Setores::all();

        $produtos = Produto::all();
        $lotes = ProdutoLote::all();

        return view(
            'rpclinica.estoqueAjuste.edit',
            compact('estoques', 'tiposAjuste', 'setores', 'produtos', 'lotes', 'ajuste')
        );
    }

    public function update(Request $request, AjusteEstoque $ajuste) {
        // dd($request->post());

        $request->validate([
            'estoque' => 'required|integer|exists:estoque,cd_estoque',
            'tipo_de_ajuste' => 'required|integer|exists:tipo_ajuste,cd_tipo_ajuste',
            'setor' => 'required|integer|exists:setor,cd_setor',
            'numero_documento' => 'required|integer',
            'produtos' => 'sometimes|array|min:1',
            'produtos.*.cd_produto' => 'required|integer|exists:produto,cd_produto',
            'produtos.*.cd_lote_produto' => 'sometimes|nullable|integer|exists:produto_lote,cd_lote',
            'produtos.*.qtde' => 'required|integer',
        ]);

        try {
            $ajuste->update([
                'cd_estoque' => $request->estoque,
                'cd_tipo_ajuste' => $request->tipo_de_ajuste,
                'cd_setor' => $request->setor,
                'nr_doc' => $request->numero_documento,
            ]);

            if (!empty($request->produtos)) {
                foreach($request->produtos as $produto) {
                    AjusteEstoqueProduto::create([
                        'cd_ajuste' => $ajuste->cd_ajuste,
                        'cd_produto' => $produto['cd_produto'],
                        'cd_lote_prod' => $produto['cd_lote_produto'],
                        'qtde' => $produto['qtde'],
                        'dt_lancamento' => date('Y-m-d H:i:s'),
                        'cd_usuario' => $request->user()->cd_usuario
                    ]);
                }
            }

            return redirect()->route('estoque.ajuste.listar')->with('success', 'Ajuste de estoque atualizado com sucesso!');
        }
        catch(Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()])->withInput();
        }
    }

    public function destroy(AjusteEstoque $ajuste)
    {
        try {
            $ajuste->delete();
        } catch (Exception $e) {
            abort(500);
        }
    }
}
