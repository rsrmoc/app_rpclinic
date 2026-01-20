<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendaProcedimentos extends Model
{
    protected $table = 'agenda_procedimentos';
    protected $primaryKey = 'cd_agenda_proc';

    protected $fillable = [
        'cd_proc',
        'cd_agenda'
    ];
}
