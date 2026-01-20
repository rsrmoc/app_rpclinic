<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class LogExamesPaciente extends Model {
    protected $table = 'log_exames_paciente';
    protected $primaryKey = 'cd_log';

    const UPDATED_AT = null;

    protected $fillable = [
        'cd_usuario',
        'cd_agendamento',
        'exames'
    ];
}
