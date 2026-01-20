<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PacienteDocumento extends Model { 

    protected $table = 'paciente_documentos';
    protected $primaryKey = 'cd_documento_paciente';

    protected $fillable = [
        'cd_documento_paciente',
        'cd_paciente',
        'cd_profissional',
        'cd_formulario',
        'conteudo',
        'titulo',
        'cd_usuario',
        'created_at',
        'updated_at',
        'deleted_at', 
    ];

    public function paciente() {
        return $this->hasOne(Paciente::class, 'cd_paciente', 'cd_paciente');
    }

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }
}
