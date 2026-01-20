<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfissionalProcedimento extends Model {
    use SoftDeletes;

    protected $table = 'profissional_proc';
    protected $primaryKey = 'cd_proc_prof';

    protected $fillable = [
        'cd_proc',
        'cd_profissional',
        'vl_proc',
        'porc_repasse',
        'cd_convenio',
        'sn_ativo',
        'cd_usuario',
        'up_usuario'
    ];

    public function procedimento() {
        return $this->hasOne(Procedimento::class, 'cd_proc', 'cd_proc');
    }

    public function convenio() {
        return $this->hasOne(Convenio::class, 'cd_convenio', 'cd_convenio');
    }
}
