<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class Oft_acuidade extends Model { 

    protected $table = 'oft_acuidade_visual';
    protected $primaryKey = 'cd_acuidade_visual';

    protected $fillable = [
        'cd_acuidade_visual',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'dt_liberacao',
        'cd_usuario_liberacao',
        'dt_cad_liberacao',  
        'tipo',
        'od_av_longe',
        'od_av_perto',
        'od_av_cores',
        'od_av_contraste',
        'oe_av_longe',
        'oe_av_perto',
        'oe_av_cores',
        'oe_av_contraste', 
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
