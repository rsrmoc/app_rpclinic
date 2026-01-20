<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class ConfigGeral extends Model
{
    protected $table = "config_geral";
    protected $primaryKey = "cd_config_geral";

    protected $fillable = [
        'cd_config_geral',
        'compartilha' 
    ];
}
