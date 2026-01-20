<?php

namespace App\Model\Support;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'usuario_id',
        'usuario_type',
        'descricao',
        'modulo',
        'agendamento_item',
        'exame_id',
        'paciente_id',
        'rotina',
        'agendamento_id', 
        'dados',
        'url'
    ];
      
    protected $casts = [
    'dados' => 'array',
    ];
    
    protected $hidden = [
    'dados',
    ];
      
    public function usuario() {
    return $this->morphTo();
    }
    
    public function registro() {
    return $this->morphTo();
    }
}
