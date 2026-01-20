<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especialidade extends Model
{
    use SoftDeletes;

    protected $table = 'especialidade';
    protected $primaryKey = 'cd_especialidade';

    protected $fillable = [
        'cd_especialidade',
        'nm_especialidade',
        'sn_ativo',
        'cd_cbos',
        'cd_usuario',
        'dt_cadastro',
        'up_cadastro',
        'up_usuario'
    ];

    public function cbo() {
        return $this->hasOne(CBOs::class, 'cd_cbos', 'cd_cbos');
    }
}
