<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerfilRota extends Model
{
    protected $table = 'perfil_rota';
    protected $primaryKey = 'cd_rota';

    protected $fillable = [
        'cd_perfil',
        'nm_rota'
    ];

    public function rotas_menu() {
        return $this->hasOne(Rota::class, 'grupo', 'nm_rota')
        ->selectRaw("distinct(menu) menu");
    }

    public function rotas_acessos() {
        return $this->hasOne(Rota::class, 'grupo', 'nm_rota');
    }

}
