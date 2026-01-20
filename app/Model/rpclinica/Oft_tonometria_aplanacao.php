<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 
use App\Model\Support\ModelPossuiLogs;

class Oft_tonometria_aplanacao extends Model { 

    use ModelPossuiLogs;
    
    protected $table = 'oft_tonometria_aplanacao';
    protected $primaryKey = 'cd_tonometria_aplanacao';

    protected $fillable = [
        'cd_tonometria_aplanacao',
        'cd_agendamento',
        'cd_profissional',
        'dt_exame',
        'cd_usuario_exame',
        'dt_cad_exame',
        'cd_equipamento',
        'pressao_od',
        'pressao_oe',
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
