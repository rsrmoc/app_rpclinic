<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menu";
    protected $primaryKey = "cd_opcao_menu";
    protected $keyType = "string";

    protected $fillable = [
        "cd_opcao_menu",
        "grupo",
        "sub_grupo" 
    ];
}
