<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class Oft_pupilometria extends Model { 

    protected $table = 'oft_pupilometria';
    protected $primaryKey = 'cd_pupilometria';

    protected $fillable = [
        'cd_pupilometria',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'dt_liberacao',
        'cd_usuario_liberacao',
        'dt_cad_liberacao', 
        'od',
        'oe',
        'tamanho',
        'olho_dominante ',
        'obs',
        'created_at',
        'updated_at',
        'deleted_at',
        
    ];

    public function agendamento() {
        return $this->hasOne(Agendamento::class, 'cd_agendamento', 'cd_agendamento');
    }

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }

}
