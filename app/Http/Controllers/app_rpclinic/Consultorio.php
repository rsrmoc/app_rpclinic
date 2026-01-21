<?php


namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoDocumentos;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Formulario;
use App\Model\rpclinica\LogProblemasPaciente;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Especialidade;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Model\rpclinica\Profissional;

class Consultorio extends Controller
{

    public function index(Request $request)
    {
        $profissionais = Profissional::orderBy('nm_profissional')->get();
        $userProfissional = auth()->guard('rpclinica')->user()->cd_profissional ?? null;
        $cd_profissional = $request->input('cd_profissional', $userProfissional);

        return view('app_rpclinic.consultorio.inicial', compact('profissionais', 'cd_profissional'));
    }

    public function consulta(Request $request, $idAgendamento)
    {
        $agendamento = Agendamento::find($idAgendamento);

        if (!$agendamento) abort(404);

        $formularios = Formulario::where('cd_profissional', Auth::guard('rpclinica')->user()->cd_profissional)
            ->where('sn_ativo', 'S')
            ->get();
        
        $businessName = Crypt::encryptString(json_decode(Cookie::get('business'))->name);

        return view('app_rpclinic.consultorio.consulta', [
            'agendamento' => $agendamento->load('paciente', 'especialidade', 'convenio'),
            'formularios' => $formularios,
            'businessName' => $businessName
        ]);
    }

    public function assinar_doc(Request $request)
    {
        $businessName = Crypt::encryptString(json_decode(Cookie::get('business'))->name);

        return view('app_rpclinic.consultorio.assinar', compact('businessName'));
    }

    public function documentos(Request $request) {
        $request->validate([
            'cd_profissional' => 'required|integer',
            'data' => 'required|string|date_format:Y-m-d'
        ]);
       
        \Log::info('📅 Buscando documentos', [
            'data' => $request->data,
            'cd_profissional' => $request->cd_profissional
        ]);
       
        // Query base
        $queryBuilder = AgendamentoDocumentos::with(['agendamento.paciente', 'agendamento.especialidade', 'agendamento.profissional']);

        // Filtro de Profissional (pode estar no doc ou no agendamento)
        $queryBuilder->where(function($q) use ($request) {
            $q->where('cd_prof', $request->cd_profissional)
              ->orWhereHas('agendamento', function($subQ) use ($request) {
                  $subQ->where('cd_profissional', $request->cd_profissional);
              });
        });

        // Filtro de Data (pode ser data de criação ou data do agendamento)
        $queryBuilder->where(function($q) use ($request) {
            $q->whereDate('created_at', $request->data)
              ->orWhereHas('agendamento', function($subQ) use ($request) {
                  $subQ->whereDate('dt_agenda', $request->data);
              });
        });

        /* 
        \Log::info('🔍 SQL Query Documentos:', [
            'sql' => $queryBuilder->toSql(),
            'bindings' => $queryBuilder->getBindings()
        ]); 
        */

        $documentos = $queryBuilder->orderBy('created_at', 'desc')->get();
 
        \Log::info('📄 Documentos encontrados', [
            'total' => $documentos->count(),
            'data_buscada' => $request->data
        ]);
 
        foreach ($documentos as $documento) {
            // Se tiver agendamento, garante o load (caso o with tenha falhado em algum nível ou para consistência)
            if ($documento->agendamento) {
                // Relações já carregadas pelo with
            } else {
                // Se NÃO tem agendamento (doc avulso), monta estrutura para o front não quebrar
                $paciente = Paciente::find($documento->cd_pac);
                $profissional = Profissional::find($documento->cd_prof);
                
                $fakeAgendamento = new Agendamento();
                $fakeAgendamento->setRelation('paciente', $paciente);
                $fakeAgendamento->setRelation('profissional', $profissional);
                
                // Mock de especialidade
                $fakeEsp = new \App\Model\rpclinica\Especialidade();
                $fakeEsp->nm_especialidade = 'Documento Avulso';
                $fakeAgendamento->setRelation('especialidade', $fakeEsp);

                $documento->setRelation('agendamento', $fakeAgendamento);
            }
        }

        return response()->json(['documentos' => $documentos]);
    }

    public function consultaPacienteHistorico(Request $request) {
        $request->validate([
            'cd_paciente' => 'required|exists:paciente,cd_paciente'
        ]);

        try {
            $historicos = DB::select("select * from (select anamnese conteudo, 'Anamnese' as nm_formulario,agendamento.dt_anamnese,
                date_format(agendamento.dt_anamnese,'%d/%m/%Y %H:%i') data,
                profissional.nm_profissional,profissional.crm,usuarios.nm_usuario,
                datediff(curdate(), date(agendamento.dt_anamnese)) diferenca
                from agendamento
                left join usuarios on usuarios.cd_usuario=agendamento.usuario_anamnese
                left join profissional on profissional.cd_profissional=agendamento.cd_profissional
                where cd_paciente=".$request->cd_paciente." and ifnull(anamnese,'') <> ''
                and ifnull(agendamento.deleted_at,'')=''
                
                
                union all
                
                select agendamento_documentos.conteudo, nm_formulario,agendamento.dt_anamnese,
                date_format(agendamento_documentos.created_at,'%d/%m/%Y %H:%i') data,
                profissional.nm_profissional,profissional.crm,usuarios.nm_usuario,
                datediff(curdate(), date(agendamento_documentos.created_at)) diferenca
                from agendamento_documentos
                left join agendamento on agendamento_documentos.cd_agendamento=agendamento.cd_agendamento
                left join usuarios on usuarios.cd_usuario=agendamento_documentos.cd_usuario
                left join profissional on profissional.cd_profissional=agendamento.cd_profissional
                where ifnull(agendamento_documentos.cd_pac,agendamento.cd_paciente)=".$request->cd_paciente."
                
                ) xx
                order by dt_anamnese desc");

            return response()->json(['historico' => $historicos]);
        }
        catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function consultaPacienteAnamnese(Request $request) {
        $request->validate([
            'cd_agendamento' => 'required|exists:agendamento,cd_agendamento',
            'conteudo' => 'required|string'
        ]);

        try {
            $agendamento = Agendamento::find($request->cd_agendamento);
            $agendamento->update([
                'anamnese' => $request->conteudo,
                'usuario_anamnese' => Auth::guard('rpclinica')->user()->cd_usuario,
                'dt_anamnese' => now()
            ]);

            return response()->json([
                'message' => 'Anamnese atualizada!'
            ]);
        }
        catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function consultaPacienteAlertas(Request $request) {
        $request->validate([
            'cd_agendamento' => 'required|exists:agendamento,cd_agendamento',
            'cd_paciente' => 'required|exists:paciente,cd_paciente',
            'conteudo' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $paciente = Paciente::find($request->cd_paciente);
            $paciente->update(['historico_problemas' => $request->conteudo]);

            LogProblemasPaciente::create([
                'cd_usuario' => Auth::guard('rpclinica')->user()->cd_usuario,
                'cd_agendamento' => $request->cd_paciente,
                'problemas' => $request->cd_conteudo
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Alertas atualizado!'
            ]);
        }
        catch(Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function consultaPacienteDoc(Request $request) {
        $request->validate([
            'cd_agendamento' => 'required|exists:agendamento,cd_agendamento',
            'cd_paciente' => 'required|exists:paciente,cd_paciente',
            'cd_formulario' => 'required|exists:formulario,cd_formulario',
            'conteudo' => 'required|string'
        ]);

        try {
            $doc = AgendamentoDocumentos::create([
                'nm_formulario' => Formulario::find($request->cd_formulario)->nm_formulario,
                'conteudo' => $request->conteudo,
                'cd_agendamento' => $request->cd_agendamento,
                'cd_formulario' => $request->cd_formulario,
                'cd_pac' => $request->cd_paciente,
                'cd_usuario' => Auth::guard('rpclinica')->user()->cd_usuario,
                'cd_prof' => Auth::guard('rpclinica')->user()->cd_profissional,
            ]);

            return response()->json([
                'message' => 'Documento salvo!',
                'doc' => $doc
            ]);
        }
        catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function consultaDocumentosLista($cdAgendamento) {

        $documentos = AgendamentoDocumentos::where('cd_agendamento', $cdAgendamento)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['documentos' => $documentos]);
    }

    public function finalizarConsulta($idAgendamento) {
        try {
            $agendamento = Agendamento::find($idAgendamento);

            $agendamento->update([
                'usuario_finalizacao' => Auth::guard('rpclinica')->user()->cd_usuario,
                'sn_finalizado' => 'S',
                'situacao' => 'atendido',
                'dt_finalizacao' => now()
            ]);

            return response()->json([
                'message' => 'Consulta finalizada!'
            ]);
        }
        catch(Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadDoc(Request $request, $cdDoc) {
        if (!$request->token) abort(404);

        dd('1');

        $businessName = Crypt::decryptString($request->token);

        $business = DB::connection('master')->table('empresas_app')
            ->where('nm_empresa_app', $businessName)
            ->where('sn_ativo', 'S')
            ->first();
        
        if (!$business) abort(404);

        $database = DB::connection('master')->table('databases_clients')
            ->where('database', $business->banco_dados)
            ->first();
        
        if (!$database) 404;

        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.host' => $database->host]);
        config(['database.connections.mysql.username' => $database->username]);
        config(['database.connections.mysql.password' => $database->password]);
        config(['database.connections.mysql.database' => $database->database]);

        $doc = AgendamentoDocumentos::find($cdDoc);
        
        if (!$doc) abort(404);

        return Pdf::loadHTML($doc->conteudo)
            ->setPaper('a4', 'portrait')
            ->setWarnings(false)
            ->download("{$doc->nm_formulario}.pdf");
    }
}
