<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class LogProblemasPaciente extends Model {
    protected $table = 'log_problemas_paciente';
    protected $primaryKey = 'cd_log';

    protected $fillable = [
        'cd_usuario',
        'cd_agendamento',
        'problemas'
    ];
}
