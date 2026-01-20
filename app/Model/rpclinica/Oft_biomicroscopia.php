<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class Oft_biomicroscopia extends Model { 

    protected $table = 'oft_biomicroscopia';
    protected $primaryKey = 'cd_biomicroscopia';

    protected $fillable = [
        'cd_biomicroscopia',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'dt_liberacao',
        'cd_usuario_liberacao',
        'dt_cad_liberacao',
        'segmento',
        'normal_od',
        'od',
        'normal_oe',
        'oe',
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
