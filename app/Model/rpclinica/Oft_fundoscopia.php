<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 
use App\Model\Support\ModelPossuiLogs;

class Oft_fundoscopia extends Model { 

    use ModelPossuiLogs;
    
    protected $table = 'oft_fundoscopia';
    protected $primaryKey = 'cd_fundoscopia';

    protected $fillable = [
        'cd_fundoscopia',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'dt_liberacao',
        'cd_usuario_liberacao',
        'dt_cad_liberacao',  
        'midriase_od',
        'normal_od',
        'od',
        'midriase_oe',
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
