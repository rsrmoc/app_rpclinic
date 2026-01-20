<?php

namespace App\Model\laudos;

use App\Model\rpclinica\Agendamento;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; 
use Illuminate\Database\Eloquent\Model;  

class UsuarioLaudo extends Authenticatable
{
    //use SoftDeletes;
    use Notifiable;

    protected $table = 'usuarios_laudos';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'cd_usuario';
 
    protected $fillable = [
        'cd_usuario', 
        'password',
        'senha_pura',
        'cd_agendamento',
        'cd_paciente',
        'sn_ativo',
        'created_at',
        'updated_at', 
        'deleted_at'
    ];

 
    
    public function pacientes() {
        return $this->hasMany(Agendamento::class, 'cd_paciente', 'cd_paciente');
    }

    public function agendamentos() {
        return $this->hasOne(Agendamento::class, "cd_agendamento", "cd_agendamento");
    }

    
    
}
