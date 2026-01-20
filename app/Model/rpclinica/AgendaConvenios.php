<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendaConvenios extends Model
{
    protected $table = 'agenda_convenios';
    protected $primaryKey = 'cd_agenda_conv';

    protected $fillable = [
        'cd_convenio',
        'cd_agenda'
    ];
}
