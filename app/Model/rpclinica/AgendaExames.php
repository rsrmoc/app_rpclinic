<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendaExames extends Model
{
    protected $table = 'agenda_exames';
    protected $primaryKey = 'cd_agenda_exame';

    protected $fillable = [
        'cd_agenda_exame',
        'cd_agenda',
        'cd_exame' 
    ];

    public function exames() {
        return $this->hasOne(Exame::class, "cd_exame", "cd_exame");
    }
}
