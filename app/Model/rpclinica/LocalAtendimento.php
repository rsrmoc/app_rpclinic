<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocalAtendimento extends Model {
    use SoftDeletes;

    protected $table = 'local_atendimento';
    protected $primaryKey = 'cd_local';

    protected $fillable = [
        'nm_local',
        'cd_setor',
        'sn_ativo',
        'dt_cadastro',
        'cd_usuario',
        'up_cadastro'
    ];

    public function setor() {
        return $this->hasOne(Setores::class, 'cd_setor', 'cd_setor');
    }
}
