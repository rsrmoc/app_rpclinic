<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PacienteEnvio extends Model { 

    protected $table = 'paciente_envios';
    protected $primaryKey = 'cd_paciente_envio';

    protected $fillable = [
        'cd_paciente_envio',
        'cd_paciente',
        'cd_documento_paciente',
        'msg',
        'cd_usuario',
        'celular',
        'id_msg',
        'created_at',
        'updated_at',
        'deleted_at', 
    ];

    public function paciente() {
        return $this->hasOne(Paciente::class, 'cd_paciente', 'cd_paciente');
    }

    public function paciente_documento() {
        return $this->hasOne(PacienteDocumento::class, 'cd_documento_paciente', 'cd_documento_paciente');
    }

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }
}
