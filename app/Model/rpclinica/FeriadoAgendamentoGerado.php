<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class FeriadoAgendamentoGerado extends Model
{
    protected $table = "feriado_agendamento_gerado";
    protected $primaryKey = "cd_feriado_agendamento";

    protected $fillable = [
        "cd_agenda",
        "cd_escala",
        "lista_datas"
    ];
}
