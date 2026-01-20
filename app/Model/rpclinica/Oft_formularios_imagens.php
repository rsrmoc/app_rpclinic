<?php

namespace App\Model\rpclinica; 
use Illuminate\Database\Eloquent\Model;  
use App\Model\Support\ModelPossuiLogs;

class Oft_formularios_imagens extends Model { 
 
    
    use ModelPossuiLogs;
    protected $table = 'oft_formularios_imagens';
    protected $primaryKey = 'cd_img_formulario';

    protected $fillable = [
        'cd_img_formulario',
        'cd_agendamento',
        'cd_profissional',
        'cd_agendamento_item',
        'dt_exame',
        'cd_usuario_exame',  
        'cd_formulario',
        'olho',
        'descricao',
        'caminho_nome',
        'caminho_img',  
        'sn_storage',
        'sn_file_valido',
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

    public function formulario() {
        return $this->hasOne(FormulariosOftalmo::class, 'cd_formulario', 'cd_formulario');
    }

    public function item() {
        return $this->hasOne(AgendamentoItens::class, "cd_agendamento_item", "cd_agendamento_item");
    }
    
    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario_exame');
    }

}
