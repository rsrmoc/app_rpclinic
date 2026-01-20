<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Setores as RpclinicaSetores;
use Exception;
use Illuminate\Support\Facades\Validator;

class Setores extends Controller
{

    public function index(Request $request)
    {
        if ($request->query('b')) {
            $setores = RpclinicaSetores::where('cd_setor', $request->b)
                ->orWhere('nm_setor', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $setores = RpclinicaSetores::all();
        }

        return view('rpclinica.setor.lista', \compact('setores'));
    }

    public function create(Request $request)
    {
        $empresas = Empresa::all();
        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }
        
        return view('rpclinica.setor.add', compact('empresas'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'nome' => 'required|string', 
            'grupo' => 'required|in:A,P,R,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            RpclinicaSetores::create([
                'nm_setor' => $request->post('nome'),
                'cd_empresa' => $request->user()->cd_empresa,
                'sn_ativo' => 'S', 
                'grupo' => $request->post('grupo'),
                'dt_cadastro' => date('Y-m-d H:i:s'),
                'cd_usuario' => $request->user()->cd_usuario
            ]);

            return redirect()->route('setor.listar')->with('success', 'Setor cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o setor.']);
        }
    }

    public function edit(Request $request, RpclinicaSetores $setor) {
        $empresas = Empresa::all();
        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }
        return view('rpclinica.setor.edit', compact('setor', 'empresas'));
    }

    public function update(Request $request, RpclinicaSetores $setor) {
        $validator = Validator::make($request->all(), [ 
            'nome' => 'required|string',  
            'grupo' => 'required|in:A,P,R,N'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $setor->update([
                'nm_setor' => $request->post('nome'), 
                'sn_ativo' => ($request->post('ativo')) ? $request->post('ativo') : 'N', 
                'grupo' => $request->post('grupo'),
                'up_cadastro' => date('Y-m-d H:i:s'),
            ]);

            return redirect()->route('setor.listar')->with('success', 'Setor atualizado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function delete(RpclinicaSetores $setor)
    {
        try {
            $setor->delete();
        }
        catch(Exception $e) {
            abort(500);
        }
    }
}
