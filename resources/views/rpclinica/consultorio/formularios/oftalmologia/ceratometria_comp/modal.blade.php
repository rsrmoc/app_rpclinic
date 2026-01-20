@foreach($historico as $index => $item)
<div class="panel panel-default" style="margin-top: 1.5em; margin-bottom: 0px; border: 1px solid rgb(221, 221, 221) !important;">
    <div class="panel-heading" style="padding: 12px; height: 60px;">
        <h3 class="panel-title"style="width: 100%;"> 
            
            <table width="100%">
                <tr>
                  <td  width="50%" style="text-align: left"> 
                    <span style=" margin-right: 15px; font-style: italic" >{{($item->profissional?->nm_profissional) ? $item->profissional?->nm_profissional : ' -- '}} </span>
                    <br>
                    <span style=" margin-right: 15px;font-weight: 300;" >[ {{$item->cd_agendamento}} ] {{ ($item->dt_exame) ? date('m/d/Y', strtotime($item->dt_exame)) : ' -- ' }} </span>
                    
                  </td>
                  <td style="text-align: right"> 
                    <span style=" margin-right: 15px; font-style: italic" >Usuario: {{ ($item->nm_usuario) ? $item->nm_usuario : ' -- ' }}</span>
                    <br>
                
                    <span style=" margin-right: 15px;font-weight: 300;" >{{   ($item->dt_cad_exame) ? date('m/d/Y', strtotime($item->dt_cad_exame)) : ' -- ' }} </span>
                    
                  </td>
                </tr>
              </table>
        </h3>
    </div>
    <div class="panel-body" style="padding-top: 1.5em">
        <div class="panel panel-white">
             
            <div class="panel-body" style="text-align: center;  margin-top: 20px;"> 
                <img class="img-fluid"  x-bind:src="'{{($array_img[$index]['conteudo_img']) ? $array_img[$index]['conteudo_img'] : asset('assets/images/img_nao_econtrada.png')  }}'" style="max-width: 100%;">
            </div>
        </div> 

    </div>
</div>
@endforeach
 