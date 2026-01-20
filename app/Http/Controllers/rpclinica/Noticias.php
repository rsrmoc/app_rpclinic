<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Empresa;
use Exception;
use Illuminate\Support\Facades\Validator;

class Noticias extends Controller
{
    public function index(Request $request)
    {
        $empresa =  Empresa::find($request->user()->cd_empresa);
        if($empresa->link_noticias){
            $content = file_get_contents($empresa->link_noticias);
            $content = explode('class="container ">',$content);
            $content = explode('</section>',$content[1]);
            $conteudo=$content[0];
            $conteudo = str_replace('<p class="widget--navigational__publisher">g1</p>', "", $conteudo);
            $conteudo = str_replace('<p class="widget--navigational__publisher">oglobo</p>', "", $conteudo);
            $conteudo = str_replace('<p class="widget--navigational__description">Tratamentos, sintomas, doenças, ciência, bem-estar, fitness e longevidade. Leia as últimas notícias da área de saúde no jornal O GLOBO. </p>', "", $conteudo);
            $conteudo = str_replace('class="pagination widget"', "", $conteudo);
            $conteudo = str_replace('<a', '<a target="_blank" ', $conteudo);
            
            $conteudo = str_replace('Veja mais', "", $conteudo);
            $noticias['moc']=$conteudo; 
        }else{ 
            $noticias['moc']=null;
        }





        $content = file_get_contents("https://www.globo.com/busca/?q=saude&page=1");
        $content = explode('class="container ">',$content);
        $content = explode('</section>',$content[1]);
        $conteudo=$content[0];
        $conteudo = str_replace('<p class="widget--navigational__publisher">g1</p>', "", $conteudo);
        $conteudo = str_replace('<p class="widget--navigational__publisher">oglobo</p>', "", $conteudo);
        $conteudo = str_replace('<p class="widget--navigational__description">Tratamentos, sintomas, doenças, ciência, bem-estar, fitness e longevidade. Leia as últimas notícias da área de saúde no jornal O GLOBO. </p>', "", $conteudo);
        $conteudo = str_replace('class="pagination widget"', "", $conteudo);
        $conteudo = str_replace('Veja mais', "", $conteudo);
        $conteudo = str_replace('<a', '<a target="_blank" ', $conteudo);
        $noticias['saude']=$conteudo;
        
        return view('rpclinica.noticias.lista', compact('noticias'));
    }
  
}
