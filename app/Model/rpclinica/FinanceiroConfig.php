<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class FinanceiroConfig extends Model {

    protected $table = "financeiro_config"; 
    protected $primaryKey = "cd_config_finan";

    protected $fillable = [
        "cd_categoria_transf",
        "cd_categoria_cartao",
        "cd_usuario"
    ];
}
