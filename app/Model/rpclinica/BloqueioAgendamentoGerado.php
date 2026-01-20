<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class BloqueioAgendamentoGerado extends Model
{
    protected $table = "bloqueio_agendamento_gerado";
    protected $primaryKey = "cd_bloqueio";

    protected $fillable = [
        "cd_agenda",
        "cd_escala",
        "lista_horarios"
    ];
}
