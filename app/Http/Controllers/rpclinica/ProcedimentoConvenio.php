<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\ProcedimentoConvenio as RpclinicaProcedimentoConvenio;
use Exception;
use Illuminate\Http\Request;

class ProcedimentoConvenio extends Controller
{
    public function destroy(RpclinicaProcedimentoConvenio $procedimento) {
        try {
            $procedimento->update(['sn_ativo'=>'N']);
        }
        catch(Exception $e) {
            abort(500);
        }
    }
}
