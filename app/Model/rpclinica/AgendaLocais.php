<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendaLocais extends Model
{
    protected $table = 'agenda_locais';
    protected $primaryKey = 'cd_agenda_local';

    protected $fillable = [
        'cd_local',
        'cd_agenda'
    ];
}
