<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Perfil;
use App\Model\rpclinica\PerfilRelatorio;
use App\Model\rpclinica\PerfilRota;
use App\Model\rpclinica\Relatorio;
use App\Model\rpclinica\Rota;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Perfis extends Controller
{

    public function index(Request $request)
    {

        if ($request->query('b')) {
            $perfis = Perfil::where('cd_perfil', $request->b)
                ->orWhere('nm_perfil', 'LIKE', "%{$request->b}%")
                ->get();
        }
        else {
            $perfis = Perfil::all();
        }

        return view('rpclinica.perfil.lista', compact('perfis'));
    }

    public function create(Request $request)
    {
    
        $acessos=Rota::where('controla_rota','S') 
        ->leftJoin("rota_ordem","rota_ordem.cd_rota","rota.rota")
        ->selectRaw("distinct(grupo) grupo,nm_rota,menu,rota_ordem.ordem")
        ->orderByRaw("rota_ordem.ordem,menu,nm_rota")
        ->get(); 
        
        $acessos= DB::select(" select * from ( select distinct(grupo) grupo,nm_rota,menu from rota where controla_rota='S'  ) xx order by menu,nm_rota ");
        //dd($acessos);
        $P_acesso = ( isset($acessos[0]->menu ) ? $acessos[0]->menu : ' -- ');
        $S_acesso = ( isset($acessos[0]->sub_menu ) ? $acessos[0]->sub_menu : ' -- ');
        $relatorios = Relatorio::where('cd_empresa',$request->user()->cd_empresa)->orderBy('id')->get();
      
        return view('rpclinica.perfil.add', compact('acessos','P_acesso','S_acesso','relatorios'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'nome' => 'required|string',
            'paginas' => 'required|array',
            'relatorios' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $perfil = Perfil::create([
                'nm_perfil' => $request->post('nome'),
                'tp_agenda' => $request->post('tp_agenda'),
                'dashboard_inicial' => $request->post('dashboard_inicial'),
                'ag_editar_horario' => $request->post('ag_editar_horario'),
                'sn_ativo' => 'S'
            ]);

            foreach($request->post('paginas') as $pagina) {
                PerfilRota::create([
                    'cd_perfil' => $perfil->cd_perfil,
                    'nm_rota' => $pagina
                ]);
            }
            if($request->post('relatorios')){
                foreach($request->post('relatorios') as $pagina) {
                    PerfilRelatorio::create([
                        'cd_perfil' => $perfil->cd_perfil,
                        'cd_relatorio' => $pagina
                    ]);
                }
            }


            return redirect()->route('perfil.listar')->with('success', 'Perfil cadastrado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar o perfil. '.$e->getMessage()]);
        }
    }

    public function edit(Request $request, Perfil $perfil) {
        $acessos=Rota::where('controla_rota','S')  
        ->leftJoin("rota_ordem","rota_ordem.cd_rota","rota.rota") 
        ->selectRaw("distinct(grupo) grupo,nm_rota,menu,rota_ordem.ordem")
        ->orderByRaw("rota_ordem.ordem,menu,nm_rota")
        ->get();

        $P_acesso = ( isset($acessos[0]->menu ) ? $acessos[0]->menu : ' -- ');
        $S_acesso = ( isset($acessos[0]->sub_menu ) ? $acessos[0]->sub_menu : ' -- ');
 
        $itens = PerfilRota::where('cd_perfil',$perfil->cd_perfil)
        ->selectRaw("nm_rota")->get(); 
        $itensPerfil =  array_column($itens->toArray() , "nm_rota"); 

        $relatorios = Relatorio::where('cd_empresa',$request->user()->cd_empresa)->orderBy('id')->get();
        
        $relatoriosPerfil=PerfilRelatorio::where('cd_perfil',$perfil->cd_perfil)->selectRaw("cd_relatorio")->get();
        $relatoriosPerfil =  array_column($relatoriosPerfil->toArray() , "cd_relatorio");
        return view('rpclinica.perfil.edit', compact('relatorios','relatoriosPerfil','perfil','acessos','P_acesso','itensPerfil','S_acesso'));
    }

    public function update(Request $request, Perfil $perfil)
    {
        $validator = Validator::make($request->post(), [
            'nome' => 'required|string',
            'paginas' => 'required|array',
            'relatorios' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {

            $perfil->update([
                'nm_perfil' => $request->post('nome'),
                'tp_agenda' => $request->post('tp_agenda'),
                'dashboard_inicial' => $request->post('dashboard_inicial'),
                'ag_editar_horario' => $request->post('ag_editar_horario'),
            ]);

            $perfil_rotas = array_column($perfil->rotas->toArray(), 'nm_rota');

            $removes = array_diff($perfil_rotas, $request->post('paginas'));
            $adds = array_diff($request->post('paginas'), $perfil_rotas);

            foreach($removes as $pagina) {
                $rota = $perfil->rotas->where('nm_rota', $pagina)->first();
                $rota?->delete();
            }

            foreach($adds as $pagina) {
                PerfilRota::create([
                    'cd_perfil' => $perfil->cd_perfil,
                    'nm_rota' => $pagina
                ]);
            }
            
            PerfilRelatorio::where('cd_perfil',$perfil->cd_perfil)->delete();
            if($request->post('relatorios')){
                foreach($request->post('relatorios') as $pagina) {
                    PerfilRelatorio::create([
                        'cd_perfil' => $perfil->cd_perfil,
                        'cd_relatorio' => $pagina
                    ]);
                }
            }
       
            HelperSessionUsusario($request->user()->email);
            
            return redirect()->route('perfil.listar')->with('success', 'Perfil atualizado com sucesso!');
        }
        catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar o perfil. '.$e->getMessage()]);
        }
    }

    public function delete(Perfil $perfil)
    {
        try {
            $perfil->delete();
        }
        catch (Exception $e) {
            abort(500);
        }
    }
}
