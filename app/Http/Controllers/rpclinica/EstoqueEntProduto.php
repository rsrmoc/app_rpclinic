<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\SolicitacaoEntradaProduto;
use Exception;
use Illuminate\Http\Request;

class EstoqueEntProduto extends Controller
{
    public function destroy(SolicitacaoEntradaProduto $entrada)
    {
        try {
            $entrada->delete();
        } catch (Exception $e) {
            abort(500);
        }
    }
}
