<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\CBOs;
use App\Model\rpclinica\EscalaLocalidade;
use Illuminate\Support\Facades\Validator;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\Uf;
use Exception;

class Localidades extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $localidades = EscalaLocalidade::where('cd_escala_localidade', $request->b)
                ->orWhere('nm_localidade', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $localidades = EscalaLocalidade::all();
        }

        return view('rpclinica.localidade.lista', \compact('request', 'localidades'));
    }

    public function create(Request $request)
    {
        $ufs = Uf::orderBy('nm_uf')->get(); 
        return view('rpclinica.localidade.add', compact('ufs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'ativo' => 'required|in:S,N',
            'uf' => 'required|exists:uf,cd_uf',
            'cep' => 'required|string|max:50',
            'cidade' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
 

            EscalaLocalidade::create([
                'nm_localidade' => $request->post('nome'),
                'cd_uf' => $request->post('uf'),
                'ds_cidade' => $request->post('cidade'),
                'sn_ativo' => $request->post('ativo'),
                'cep' => $request->post('cep'),
                'cd_usuario' => $request->user()->cd_usuario, 
            ]);

            return redirect()->route('localidade.listar')->with('success', 'Localidade cadastrada.');

        } catch (Exception $exception) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar.']);
        }
    }

    public function edit(EscalaLocalidade $localidade) {

        $ufs = Uf::orderBy('nm_uf')->get();  
        return view('rpclinica.localidade.edit', compact('localidade', 'ufs'));

    }

    public function update(Request $request, EscalaLocalidade $localidade) {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'ativo' => 'required|in:S,N',
            'uf' => 'required|exists:uf,cd_uf',
            'cep' => 'required|string|max:50',
            'cidade' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

            $localidade->update([
                'nm_localidade' => $request->post('nome'),
                'cd_uf' => $request->post('uf'),
                'ds_cidade' => $request->post('cidade'),
                'sn_ativo' => $request->post('ativo'),
                'cep' => $request->post('cep'),
                'cd_usuario' => $request->user()->cd_usuario, 
            ]);

            return redirect()->route('localidade.listar')->with('success', 'Localidade atualizada.');

        } catch (Exception $exception) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a especialidade.']);
        }
    }

    public function delete(EscalaLocalidade $localidade)
    {
        try {
            //dd($localidade->toArray());
            $localidade->delete();
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
