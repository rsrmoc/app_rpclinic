<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendamentoBloqueio extends Model { 

    protected $table = 'agendamento_bloqueio';
    protected $primaryKey = 'cd_agenda_bloqueio';

    protected $fillable = [
        'cd_profissional',
        'dt_inicio',
        'dt_final', 
        'cd_usuario',
        'created_at',
        'updated_at'
    ];

    public function tab_profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }
}
