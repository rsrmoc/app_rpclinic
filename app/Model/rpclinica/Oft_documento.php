<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 
use App\Model\Support\ModelPossuiLogs;

class Oft_documento extends Model { 

    use ModelPossuiLogs;
    protected $table = 'oft_documentos';
    protected $primaryKey = 'cd_documento';

    protected $fillable = [
        'cd_documento',
        'cd_agendamento',
        'cd_profissional',
        'cd_formulario',
        'descricao',
        // 'cd_usuario', 
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
