<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    protected $table = "app_config";
    protected $primaryKey = "id";
    protected $keyType = "string";

    protected $fillable = [
        "id",
        "app",
        "tela_menu_lateral",
        "tela_menu_inferior",
        "tela_menu_inicial", 
        'created_at',
        'updated_at',
        'deleted_at'
    ];

 
}
