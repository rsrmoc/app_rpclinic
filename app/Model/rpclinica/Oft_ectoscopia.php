<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class Oft_ectoscopia extends Model { 

    protected $table = 'oft_ectoscopia';
    protected $primaryKey = 'cd_ectoscopia';

    protected $fillable = [
        'cd_ectoscopia',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'dt_liberacao',
        'cd_usuario_liberacao',
        'dt_cad_liberacao',
        'normal_od',
        'exame_externo_od',
        'normal_oe',
        'exame_externo_oe',
        'normal_face',
        'exame_face',
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
