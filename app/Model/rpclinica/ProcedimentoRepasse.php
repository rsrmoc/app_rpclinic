<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class ProcedimentoRepasse extends Model
{
    //use SoftDeletes;

    protected $table = 'procedimento_repasse';
    protected $primaryKey = 'cd_procedimento_repasse';

    protected $fillable = [
        'cd_procedimento_repasse',
        'cd_convenio',
        'cd_profissional',
        'cd_procedimento',
        'tipo',
        'valor',
        'cd_usuario',
        'sn_ativo',
        'dt_desativacao',
        'created_at',
        'updated_at', 
    ];

    public function convenio() {
        return $this->hasOne(Convenio::class, 'cd_convenio', 'cd_convenio');
    }

    public function procedimento() {
        return $this->hasOne(Procedimento::class, 'cod_proc', 'cd_procedimento');
    }

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }

}
