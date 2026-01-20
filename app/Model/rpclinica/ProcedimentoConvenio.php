<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class ProcedimentoConvenio extends Model
{
    protected $table = "procedimento_convenio";
    protected $primaryKey = "cd_procedimento_convenio";

    protected $fillable = [
        "cd_procedimento",
        "cd_convenio",
        "valor",
        "sn_ativo",
        "cd_usuario",
        "dt_vigencia"
    ];
}
