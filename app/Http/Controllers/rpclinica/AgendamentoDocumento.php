<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoDocumentos;
use App\Model\rpclinica\Formulario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\Request;
use Throwable;

class AgendamentoDocumento extends Controller {

    public function jsonStore(Request $request, Agendamento $agendamento) {
        $validator = Validator::make($request->post(), [
            'formulario' => 'sometimes|integer|exists:formulario,cd_formulario',
            'conteudo' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()]);
        }

        try {
            $formulario = Formulario::find($request->formulario);

            $documentoAgendamento = AgendamentoDocumentos::create([
                'nm_formulario' => $formulario->nm_formulario,
                'conteudo' => $request->conteudo,
                'cd_formulario' => $request->formulario,
                'cd_usuario' => $request->user()->cd_usuario,
                'cd_pac' =>  $agendamento->cd_paciente,
                'cd_prof' =>  $agendamento->cd_profissional,
                'cd_agendamento' => $agendamento->cd_agendamento
            ]);


            funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO SALVOU DOCUMENTO ( '.$request->formulario.' )');

            return response()->json(['message' => 'Documento cadastrado!', 'documento' => $documentoAgendamento]);
        }
        catch(Throwable $th) {
            return response()->json(['message' => $th->getMessage()]);
        }
    }

    public function jsonUpdate(Request $request, AgendamentoDocumentos $documento) {
        try {


            if($request->formulario){
                $formulario = Formulario::find($request->formulario);
                $documento->cd_formulario = $request->formulario;
                $documento->nm_formulario = $formulario->nm_formulario;
            }
            $documento->cd_usuario = $request->user()->cd_usuario;
            $documento->updated_at = date('Y-m-d H:i');
            $documento->conteudo = $request->conteudo;
            $documento->save();

            funcLogsAtendimentoHelpers($documento->cd_agendamento,'USUARIO ATUALIZOU DOCUMENTO ( '.$request->formulario.' )');

            return response()->json(['message' => 'Documento atualizado com sucesso!', 'documento' => $documento]);
        }
        catch(Throwable $th) {
            return response()->json([
                'message' => 'Erro ao atualizar o documento!',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function jsonDestroy(AgendamentoDocumentos $documento) {
        try {
            $documento->delete();

            funcLogsAtendimentoHelpers($documento->cd_agendamento,'USUARIO DELETOU DOCUMENTO ( '.$documento->cd_formulario.' )');

            return response()->json(['message' => 'Documento atualizado com sucesso!', 'documento' => $documento]);

            return response()->json(['message' => 'Documento excluído com sucesso!']);
        }
        catch(Throwable $th) {
            return response()->json([
                'message' => 'Erro ao excluir o documento!',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
