<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class Oft_dp extends Model { 

    protected $table = 'oft_dp';
    protected $primaryKey = 'cd_dp';

    protected $fillable = [
        'cd_dp',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'dt_liberacao',
        'cd_usuario_liberacao',
        'dt_cad_liberacao',   
        'od_longe',
        'oe_longe',
        'od_perto',
        'oe_perto',
        'dp_longe',
        'dp_perto',  
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
