<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfissionalEspecialidade extends Model {
    use SoftDeletes;

    protected $table = 'profissional_espec';
    protected $primaryKey = 'cd_prof_espec';

    protected $fillable = [
        'cd_especialidade',
        'cd_profissional',
        'sn_ativo',
        'sn_compartilha',
        'cd_usuario',
        'up_usuario'
    ];

    public function especialidade() {
        return $this->hasOne(Especialidade::class, 'cd_especialidade', 'cd_especialidade');
    }
}
