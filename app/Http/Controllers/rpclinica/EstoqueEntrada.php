<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Estoque;
use App\Model\rpclinica\Fornecedor;
use App\Model\rpclinica\Motivo;
use App\Model\rpclinica\Produto;
use App\Model\rpclinica\ProdutoLote;
use App\Model\rpclinica\SolicitacaoEntrada;
use App\Model\rpclinica\SolicitacaoEntradaProduto;
use Exception;
use Illuminate\Http\Request;

class EstoqueEntrada extends Controller
{
    public function index(Request $request)
    {
        $estoques = Estoque::all();
        $fornecedores = Fornecedor::all();

        $entradas = SolicitacaoEntrada::with('fornecedor', 'estoque')->get();

        if (!empty($request->b)) {
            $entradas = $entradas->where('cd_solicitacao', $request->b);
        }

        if (!empty($request->data)) {
            $entradas = $entradas->where('dt_solicitacao', $request->data);
        }

        if (!empty($request->fornecedor)) {
            $entradas = $entradas->where('cd_fornecedor', $request->fornecedor);
        }

        if (!empty($request->estoque)) {
            $entradas = $entradas->where('cd_estoque', $request->estoque);
        }
        
        return view('rpclinica.estoqueEntrada.listar', compact('fornecedores', 'estoques', 'entradas'));
    }

    public function create()
    {
        $estoques = Estoque::all();
        $produtos = Produto::all();
        $fornecedores = Fornecedor::all();
        $motivos = Motivo::all();
        $lotes = ProdutoLote::all();

        return view(
            'rpclinica.estoqueEntrada.add',
            compact('estoques', 'produtos', 'fornecedores', 'motivos', 'lotes')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data' => 'required|string|date_format:Y-m-d',
            'estoque' => 'required|integer|exists:estoque,cd_estoque',
            'motivo' => 'required|integer|exists:motivo,cd_motivo',
            'ordem_compras' => 'required|integer',
            'numero_documento' => 'required|integer',
            'fornecedor' => 'required|integer|exists:fornecedor,cd_fornecedor',
            'produtos' => 'required|array|min:1',
            'produtos.*.cd_produto' => 'required|integer|exists:produto,cd_produto',
            'produtos.*.cd_lote_produto' => 'sometimes|nullable|integer|exists:produto_lote,cd_lote',
            'produtos.*.qtde' => 'required|integer',
            'produtos.*.valor' => 'required|numeric',
        ]);

        try {
            $solicitacao = SolicitacaoEntrada::create([
                'dt_solicitacao' => $request->data,
                'cd_estoque' => $request->estoque,
                'cd_motivo' => $request->motivo,
                'cd_ord_Com' => $request->ordem_compras,
                'nr_doc' => $request->numero_documento,
                'cd_fornecedor' => $request->fornecedor,
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            foreach($request->produtos as $produto) {
                $produto['cd_solicitacao'] = $solicitacao->cd_solicitacao;
                $produto['dt_lancamento'] = $request->data;
                $produto['cd_usuario'] = $request->user()->cd_usuario;

                SolicitacaoEntradaProduto::create($produto);
            }

            return redirect()->route('estoque.entrada.listar')->with('success', 'Solicitação de entrada criada com sucesso!');
        }
        catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function edit(SolicitacaoEntrada $entrada)
    {
        $estoques = Estoque::all();
        $produtos = Produto::all();
        $fornecedores = Fornecedor::all();
        $motivos = Motivo::all();
        $lotes = ProdutoLote::all();

        return view(
            'rpclinica.estoqueEntrada.edit',
            compact('estoques', 'produtos', 'fornecedores', 'motivos', 'lotes', 'entrada')
        );
    }

    public function update(Request $request, SolicitacaoEntrada $entrada)
    {
        $validated = $request->validate([
            'data' => 'required|string|date_format:Y-m-d',
            'estoque' => 'required|integer|exists:estoque,cd_estoque',
            'motivo' => 'required|integer|exists:motivo,cd_motivo',
            'ordem_compras' => 'required|integer',
            'numero_documento' => 'required|integer',
            'fornecedor' => 'required|integer|exists:fornecedor,cd_fornecedor',
            'produtos' => 'sometimes|array|min:1',
            'produtos.*.cd_produto' => 'required|integer|exists:produto,cd_produto',
            'produtos.*.cd_lote_produto' => 'sometimes|nullable|integer|exists:produto_lote,cd_lote',
            'produtos.*.qtde' => 'required|integer',
            'produtos.*.valor' => 'required|numeric',
        ]);

        try {
            $entrada->update([
                'dt_solicitacao' => $request->data,
                'cd_estoque' => $request->estoque,
                'cd_motivo' => $request->motivo,
                'cd_ord_Com' => $request->ordem_compras,
                'nr_doc' => $request->numero_documento,
                'cd_fornecedor' => $request->fornecedor,
            ]);

            if (!empty($request->produtos)) {
                foreach($request->produtos as $produto) {
                    $produto['cd_solicitacao'] = $entrada->cd_solicitacao;
                    $produto['dt_lancamento'] = $request->data;
                    $produto['cd_usuario'] = $request->user()->cd_usuario;

                    SolicitacaoEntradaProduto::create($produto);
                }
            }

            return redirect()->route('estoque.entrada.listar')->with('success', 'Solicitação de entrada atualizada com sucesso!');
        }
        catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(SolicitacaoEntrada $entrada)
    {
        try {
            $entrada->delete();
        } catch (Exception $e) {
            abort(500);
        }
    }
}
