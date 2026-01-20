<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 
use App\Model\Support\ModelPossuiLogs;

class Oft_anamnese extends Model { 
    
    use ModelPossuiLogs;

    protected $table = 'oft_anamnese';
    protected $primaryKey = 'cd_anamnese';

    protected $fillable = [
        'cd_anamnese',
        'cd_agendamento',
        'cd_profissional',
        'dt_anamnese',
        'cd_usuario_anamnese',
        'dt_cad_anamnese',
        'motivo',
        'historia',
        'medicamentos',
        'alergias',
        'conduta', 
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
