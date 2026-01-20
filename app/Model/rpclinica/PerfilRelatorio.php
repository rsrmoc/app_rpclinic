<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class PerfilRelatorio extends Model
{
    protected $table = "perfil_relatorio";
    protected $primaryKey = "cd_perfil_relatorio"; 

    protected $fillable = [
        "cd_perfil_relatorio",
        "cd_perfil",
        "cd_relatorio", 
        'created_at',
        'updated_at',
    ];

    public function perfil() {
        return $this->hasOne(Perfil::class, 'cd_perfil', 'cd_perfil');
    }
    public function relatorio() {
        return $this->hasOne(Relatorio::class, 'id', 'cd_relatorio');
    }
}
