<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\CBOs;
use App\Model\rpclinica\EscalaLocalidade;
use App\Model\rpclinica\EscalaTipo;
use Illuminate\Support\Facades\Validator;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\Uf;
use Exception;

class EscalasTipos extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $Tipos = EscalaTipo::where('cd_escala_tipo', $request->b)
                ->orWhere('nm_tipo_escala', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $Tipos = EscalaTipo::all();
        }

        return view('rpclinica.escala_tipo.lista', \compact('request', 'Tipos'));
    }

    public function create(Request $request)
    { 
        return view('rpclinica.escala_tipo.add' );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'ativo' => 'required|in:S,N', 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
  
            EscalaTipo::create([
                'nm_tipo_escala' => $request->post('nome'), 
                'sn_ativo' => $request->post('ativo'), 
                'cd_usuario' => $request->user()->cd_usuario, 
            ]);

            return redirect()->route('escala-tipo.listar')->with('success', 'Tipo de Escala cadastrada.');

        } catch (Exception $exception) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar.']);
        }
    }

    public function edit(EscalaTipo $tipo) {
  
        return view('rpclinica.escala_tipo.edit', compact('tipo' ));

    }

    public function update(Request $request, EscalaTipo $tipo) {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'ativo' => 'required|in:S,N', 
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

            $tipo->update([
                'nm_tipo_escala' => $request->post('nome'), 
                'sn_ativo' => $request->post('ativo'), 
                'cd_usuario' => $request->user()->cd_usuario, 
            ]);

            return redirect()->route('escala-tipo.listar')->with('success', 'Tipo de Escala atualizada.');

        } catch (Exception $exception) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a especialidade.']);
        }
    }

    public function delete(EscalaTipo $tipo)
    {
        try {
            //dd($localidade->toArray());
            $tipo->delete();
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
