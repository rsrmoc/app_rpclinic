<?php

namespace App\Http\Requests;
namespace App\Http\Requests\rpclinica\usuarios;

use Illuminate\Foundation\Http\FormRequest;

class UsuariosRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {

        return [
            'funcionario' => ['required'],
            'perfil' => ['required','array','min:1'],
        ];

    }
}
