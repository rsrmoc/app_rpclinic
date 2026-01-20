<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendamentoWhast extends Model {


    protected $table ='agendamento_whast';
    protected $primaryKey = 'cd_agendamento_whast';

    protected $fillable = [
        'cd_agendamento_whast',
        'cd_usuario',
        'celular',
        'celular_envio',
        'dt_envio',
        'situacao',
        'dt_finalizacao',
        'retorno',
        'ds_retorno',
        'cd_agendamento',
        'cd_paciente',
        'created_at',
        'updated_at'
    ];

     
    public function tab_agendamento() {
        return $this->hasOne(Agendamento::class, 'cd_agendamento', 'cd_agendamento');
    }
}
