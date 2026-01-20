<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Profissional_externo extends Model
{
    protected $table = "profissional_externo";
    protected $primaryKey = "cd_profissional_externo"; 

    protected $fillable = [
        "cd_profissional_externo",
        "nm_profissional_externo", 
        "cd_usuario",
        "created_at",
        "updated_at"
    ];
}
