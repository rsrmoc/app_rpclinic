<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendaEspecialidades extends Model
{
    protected $table = 'agenda_especialidades';
    protected $primaryKey = 'cd_agenda_espec';

    protected $fillable = [
        'cd_especialidade',
        'cd_agenda'
    ];
}
