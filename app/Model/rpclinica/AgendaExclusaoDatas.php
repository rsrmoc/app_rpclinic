<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendaExclusaoDatas extends Model
{
    protected $table = "agenda_exclusao_datas";
    protected $primaryKey = "cd_exclusao";

    protected $fillable = [
        "cd_agenda",
        "cd_escala",
        "lista_datas",
        "cd_usuario"
    ];
}
