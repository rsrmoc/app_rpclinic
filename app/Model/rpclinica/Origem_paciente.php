<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Origem_paciente extends Model
{
    protected $table = "origem_paciente";
    protected $primaryKey = "cd_origem"; 

    protected $fillable = [
        "cd_origem",
        "nm_origem", 
        "cd_usuario",
        "created_at",
        "updated_at"
    ];
}
