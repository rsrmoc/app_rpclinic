<?php


namespace App\Http\Controllers\rpclinica\consultorio\paciente;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Paciente as RpclinicaPaciente;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Paciente extends Controller
{

    public function index(Request $request, Agendamento $agendamento)
    {

        try {

            $agendamento->load('paciente');
            $hist = Agendamento::where('cd_paciente', $agendamento->cd_paciente)
                ->join("profissional", 'profissional.cd_profissional', 'agendamento.cd_profissional')
                ->selectRaw("motivo_consulta,hist_oft,medicamentos,alergias,conduta,date_format(agendamento.data_horario, '%d/%m/%Y') data_horario,nm_profissional")
                ->where('agendamento.cd_agendamento', '!=', $agendamento->cd_agendamento)
                ->orderByRaw('data_horario desc')->limit('1')->get();
            if (isset($hist[0])) {
                $retorno['hist'] = $hist[0];
            } else {
                $retorno['hist'] = null;
            }
            $retorno['agendamento'] = $agendamento;
            return response()->json($retorno);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['message' => 'Não foi possivel cadastrar a classificação. ' . $e->getMessage()]);
        }
    }
    public function storeObs(Request $request, RpclinicaPaciente $paciente)
    {
        try {
            DB::beginTransaction();
            $paciente->update(['historico_problemas'=>$request['obs']]);
            DB::commit();
            return $request;
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors(['message' => 'Não foi possivel atualizar o paciente. ' . $e->getMessage()]);
        }

    }
     
    public function store(Request $request, RpclinicaPaciente $paciente)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                "nm_paciente" => "required|string|max:255",
                "nome_social" => "nullable|string|max:255",
                "dt_nasc" => "required|date",
                "sexo" => 'nullable|in:H,M',
                "estado_civil" => "nullable|in:S,C,D,V",
                "rg" => "nullable",
                "cpf" => "nullable",
                "cartao" => "nullable",
                "cartao_sus" => "nullable|string|max:100",
                "nm_mae" => "nullable|string|max:255",
                "nm_pai" => "nullable|string|max:255",
                "cd_categoria" => "nullable|integer|exists:convenio,cd_convenio",
                "logradouro" => "nullable|string|max:255",
                "numero" => "nullable",
                "complemento" => "nullable|string|max:50",
                "nm_bairro" => "nullable|string|max:50",
                "cidade" => "nullable|string|max:50",
                "uf" => "nullable|string|uf",
                "cep" => "nullable",
                "vip" => "nullable",
                "telefone" => "nullable",
                "celular" => "nullable",
                "nm_responsavel" => "nullable",
                "cpf_responsavel" => "nullable",
            ]);


            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $paciente->update($request->all());
            DB::commit();


            return $request;
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors(['message' => 'Não foi possivel atualizar o paciente. ' . $e->getMessage()]);
        }
    }
    
    public function updateComentario(Request $request)
    {
        // Validação do ID e comentário
        $validator = Validator::make($request->all(), [
            'cd_paciente' => 'required|integer|exists:pacientes,id',
            'comentario' => 'nullable|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }
    
        try {
            $id = $request->input('cd_paciente');
            $paciente = RpclinicaPaciente::findOrFail($id);
    
            // Atualiza o comentário mantendo os demais campos
            $paciente->comentario = $request->input('comentario', $paciente->comentario);
            $paciente->save();
    
            return response()->json(['success' => true, 'message' => 'Comentário atualizado com sucesso.']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar comentário: ' . $e->getMessage()], 500);
        }
    }
    
    
}
