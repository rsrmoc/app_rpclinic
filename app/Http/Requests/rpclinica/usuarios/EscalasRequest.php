<?php

namespace App\Http\Requests;
namespace App\Http\Requests\rpclinica\usuarios;

use App\Model\rpclinica\AgendaEscala;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EscalasRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        $rules=array(
            "cd_agenda" => "required|integer|exists:agenda,cd_agenda",
            "semana" => [
                "required",
                "array",
                Rule::in(['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'])
            ],
            "semana" => "required|array|min:1",
            "cd_escala" => "nullable|integer",
            "data_inicial" => "nullable|date",
            "data_final" => "nullable|date",
            "hora_inicial" => "required",
            "hora_final" => "required",
            "intervalo" => "required|integer",
            "qtde_sessao" => "nullable|integer",
            "qtde_encaixe" => "nullable|integer",
            'particular' => "sometimes|boolean",
            'convenio' => "sometimes|boolean",
            'sus' => "sometimes|boolean",
            'sn_sessao' => "sometimes|boolean",

        );

        if(!$this->cd_escala){

            if($this->hora_inicial > $this->hora_final){
                $rules['hr_erro'] ="required";
            }



            /*
            $dti = ($this->data_inicial) ? $this->data_inicial : '2000-01-01';
            $Dti=AgendaEscala::whereRaw("'".$dti."' between ifnull(dt_inicial,'2000-01-01') and  ifnull(dt_fim,'2050-12-31') ")->whereRaw("sn_ativo='S'")
            ->whereRaw("cd_agenda=".$this->cd_agenda)->whereIn("cd_dia",$this->semana)->count();
            if($Dti>0){
                $rules['dti_erro'] ="required";
            }

            $dtf = ($this->data_final) ? $this->data_final : '2050-12-31';
            $Dtf=AgendaEscala::whereRaw("'".$dtf."' between ifnull(dt_inicial,'2000-01-01') and  ifnull(dt_fim,'2050-12-31') ")->whereRaw("sn_ativo='S'")
            ->whereRaw("cd_agenda=".$this->cd_agenda)->whereIn("cd_dia",$this->semana)->count();
            if($Dtf>0){
                $rules['dtf_erro'] ="required";
            }
            */

        }

        return $rules;

    }

    public function messages()
    {
        return [
            'datai_erro.required' => 'A data inicial é obrigatorio',
            'dataf_erro.required' => 'A data final é obrigatorio',
            'dt_erro.required' => 'A data inicial não pode ser maior que a data final.',
            'hr_erro.required' => 'A hora inicial não pode ser maior que a hora final.',
            'dti_erro.required' => 'Conflito de Data!!! Existe escala gerada entre a data inicial.',
            'dtf_erro.required' => 'Conflito de Data!!! Existe escala gerada entre a data final.',
            'semana.required' => 'É obrigatorio informar pelo menos dia da semana para gravar a escala.'
        ];
    }
}
