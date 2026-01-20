<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Feriado;
use App\Model\rpclinica\LocalAtendimento as RpclinicaLocalAtendimento;
use App\Model\rpclinica\Setores;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;

class Feriados extends Controller
{

    public function index(Request $request)
    {



        /*
        $dados = file_get_contents('https://api.invertexto.com/v1/holidays/2022?token=3027|K6Se4CbvDi3UgIROehQJlN5CCsFqK2P3');
        $dados=json_decode($dados);
        foreach ($dados as $key => $dado) {
            $Array=array(
                'nm_feriado'=> mb_strtoupper($dado->name),
                'dt_feriado'=> $dado->date,
                'ano'=> substr($dado->date,0,4),
                'tp_feriado'=> mb_strtoupper(substr($dado->type,0,2)),
                'nivel'=> mb_strtoupper($dado->level),
                'sn_bloqueado'=>( mb_strtoupper(substr($dado->type,0,2))=='FE' ) ? 'S' : 'N',
                'cd_usuario'=> $request->user()->cd_usuario,
                'created_at'=> date('Y-m-d H:i')
            );
            Feriado::create($Array);
        }
        */
        if ($request->query('b')) {
            $feriados = Feriado::where('cd_feriado', $request->b)
                ->orWhere('nm_feriado', 'LIKE', "%{$request->b}%")
                ->orWhere('ano', 'LIKE', "%{$request->b}%")
                ->orWhere('dt_feriado', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $feriados = Feriado::orderBy('dt_feriado')
            ->selectRaw("feriados.*, DATE_FORMAT(dt_feriado, '%d/%m/%Y') data_feriado")->get();
        }

        return view('rpclinica.feriados.lista', compact('feriados'));
    }

    public function create()
    {

        return view('rpclinica.feriados.add' );
    }

    public function api(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'ano' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

            $dados = file_get_contents('https://api.invertexto.com/v1/holidays/'.trim($request->ano).'?token=3027|K6Se4CbvDi3UgIROehQJlN5CCsFqK2P3');
            $dados=json_decode($dados);
            foreach ($dados as $key => $dado) {

                $Feriado = Feriado::where('dt_feriado', $dado->date)->first();

                if(empty($Feriado)){
                    $Array=array(
                        'nm_feriado'=> mb_strtoupper($dado->name),
                        'dt_feriado'=> $dado->date,
                        'ano'=> substr($dado->date,0,4),
                        'tp_feriado'=> mb_strtoupper(substr($dado->type,0,2)),
                        'nivel'=> mb_strtoupper($dado->level),
                        'sn_bloqueado'=>( mb_strtoupper(substr($dado->type,0,2))=='FE' ) ? 'S' : 'N',
                        'cd_usuario'=> $request->user()->cd_usuario,
                        'sn_api'=>'S',
                        'created_at'=> date('Y-m-d H:i')
                    );
                    Feriado::create($Array);
                }

            }

             return redirect()->route('feriados.listar')->with(['success' => 'API executada com sucesso!']);

        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel executar a API.']);
        }

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nm_feriado' => 'required|string',
            'dt_feriado' => 'required|date',
            'tp_feriado' => 'nullable|max:2',
            'nivel' => 'nullable|max:20',
            'sn_bloqueado' => 'required|max:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

            Feriado::create([
                'nm_feriado'=> mb_strtoupper($request->nm_feriado),
                'dt_feriado'=> $request->dt_feriado,
                'ano'=> substr($request->dt_feriado,0,4),
                'tp_feriado'=> $request->tp_feriado,
                'nivel'=> $request->nivel,
                'sn_bloqueado'=>$request->sn_bloqueado,
                'cd_usuario'=> $request->user()->cd_usuario,
                'created_at'=> date('Y-m-d H:i')
            ]);

            return redirect()->route('feriados.listar')->with(['success' => 'Feriado cadastrado com sucesso!']);
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o Feriado.']);
        }
    }

    public function edit(Feriado $feriado) {

        return view('rpclinica.feriados.edit', compact('feriado'));
    }

    public function update(Request $request, Feriado $feriado) {
        $validator = Validator::make($request->all(), [
            'nm_feriado' => 'required|string',
            'dt_feriado' => 'required|date',
            'tp_feriado' => 'nullable|max:2',
            'nivel' => 'nullable|max:20',
            'sn_bloqueado' => 'required|max:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $feriado->update([
                'nm_feriado'=> mb_strtoupper($request->nm_feriado),
                'dt_feriado'=> $request->dt_feriado,
                'ano'=> substr($request->dt_feriado,0,4),
                'tp_feriado'=> $request->tp_feriado,
                'nivel'=> $request->nivel,
                'sn_bloqueado'=>$request->sn_bloqueado,
                'cd_usuario'=> $request->user()->cd_usuario
            ]);

            return redirect()->route('feriados.listar')->with(['success' => 'Feriado atualizado com sucesso!']);
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o Feriado.']);
        }
    }

    public function delete(Feriado $feriado)
    {
        try {
            $feriado->delete();
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
