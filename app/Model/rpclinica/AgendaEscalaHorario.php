<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaEscalaHorario extends Model
{
    use SoftDeletes;
    protected $table = 'agenda_escala_horario';
    protected $primaryKey = 'cd_agenda_escala_horario';

    protected $fillable = [
        'cd_agenda_escala_horario',
        'cd_escala_agenda',
        'cd_agenda',
        'cd_horario',
        'cd_usuario', 
        'created_at',
        'updated_at',
        'deleted_at',
    ];
 
    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'usuario_geracao');
    } 
    
    public function escala() {
        return $this->hasOne(AgendaEscala::class, 'cd_escala_agenda', 'cd_escala_agenda');
    } 

    public function agenda() {
        return $this->hasOne(Agenda::class, "cd_agenda", "cd_agenda");
    }

    public function agendamento() {
        return $this->hasOne(Agendamento::class, "cd_agenda_escala_horario", "cd_agenda_escala_horario");
    }
}
