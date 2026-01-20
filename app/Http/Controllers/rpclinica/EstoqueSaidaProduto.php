<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\SolicitacaoSaidaProd;
use Exception;

class EstoqueSaidaProduto extends Controller
{
    public function destroy(SolicitacaoSaidaProd $saida)
    {
        try {
            $saida->delete();
        } catch (Exception $e) {
            abort(500);
        }
    }
}
