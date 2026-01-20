<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendaProfissionais extends Model
{
    protected $table = 'agenda_profissionais';
    protected $primaryKey = 'cd_agenda_prof';

    protected $fillable = [
        'cd_profissional',
        'cd_agenda',
        'cd_escala'
    ];
}
