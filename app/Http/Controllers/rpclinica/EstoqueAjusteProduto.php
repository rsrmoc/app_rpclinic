<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\AjusteEstoqueProduto;
use Exception;
use Illuminate\Http\Request;

class EstoqueAjusteProduto extends Controller
{
    public function destroy(AjusteEstoqueProduto $ajuste)
    {
        try {
            $ajuste->delete();
        } catch (Exception $e) {
            abort(500);
        }
    }
}
