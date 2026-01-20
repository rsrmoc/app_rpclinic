<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class AgendaIntervalo extends Model
{ 

    protected $table = 'agenda_intervalo';
    protected $primaryKey = 'cd_intervalo';

    protected $fillable = [
        'cd_intervalo',
        'nm_intervalo', 
    ];

 
}
