<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendamentoLog extends Model {


    protected $table ='agendamento_log';
    protected $primaryKey = 'cd_log';

    protected $fillable = [
        'cd_log',
        'cd_usuario',
        'chave',
        'tp_log',
        'dt_log',
        'dados',
        'modulo',
        'created_at',
        'updated_at'
    ];
}
