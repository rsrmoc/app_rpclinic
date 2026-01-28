<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AppTelas extends Model
{
    protected $table = "app_telas";
    protected $primaryKey = "cd_app_tela";
    protected $keyType = "string";

    protected $fillable = [
        "cd_app_tela",
        "app_consultorio",
        "app_adonhiran", 
        'created_at',
        'updated_at',
        'deleted_at'
    ];

 
}
