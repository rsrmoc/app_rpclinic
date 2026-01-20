<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class BloqueioAgendamento extends Model
{
    protected $table = 'bloqueio_agendamento';
    protected $primaryKey = 'cd_bloqueio';

    protected $fillable = [
        'cd_agenda',
        'data_horario'
    ];
}
