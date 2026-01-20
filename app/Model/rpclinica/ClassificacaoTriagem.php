<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class ClassificacaoTriagem extends Model
{
    protected $table = "classificacao_triagem";
    protected $primaryKey = "cd_classificacao";

    protected $fillable = [
        "nm_classificacao",
        "cor",
        "sn_ativo",
        "ds_classificacao"
    ];
}
