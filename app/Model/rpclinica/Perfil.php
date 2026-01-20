<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perfil extends Model
{
    use SoftDeletes;

    protected $table = 'perfil';
    protected $primaryKey = 'cd_perfil';

    protected $fillable = [
        'nm_perfil',
        'sn_ativo',
        'ag_editar_horario',
        'dashboard_inicial',
        'tp_agenda',
    ];

    public function rotas() {
        return $this->hasMany(PerfilRota::class, 'cd_perfil', 'cd_perfil');
    }

    public function isRota(string $rota): bool {
        return $this->rotas->where('nm_rota', $rota)->first() ? true : false;
    }
    public function itens(string $rota) {
        return $this->hasMany(PerfilRota::class, 'cd_perfil', 'cd_perfil')
        ->where('nm_rota', $rota);
    }

}
