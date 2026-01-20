<div class="col-md-9" x-data="{  index: 0, showHistory: false }"  >
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12" style="  height: 150px;overflow: auto;">
      <table class="table table-striped">
        <thead>
          <tr >
            <th>Atendimento</th>
            <th>Médico Solicitante</th>
            <th>Exame</th>
            <th>Data do Exame</th>
            <th>Médico</th>
            <th>Data do Laudo</th>
            <th>Situação</th>
          </tr>
        </thead>
        <tbody>

          <template x-for=" (item, idx) in HISTORICO.history">
            <tr style="cursor: pointer;" x-on:click="index = idx">
              <th x-text="item.cd_agendamento"></th>
              <td x-text="item.atendimento.profissional.nm_profissional"></td>
              <td x-text="item.exame.nm_exame"></td>  
              <td x-text=" item.created_data "></td>  
              <td x-text="item.atendimento.profissional.nm_profissional"></td>
              <td x-text="(item.dt_laudo) ?  item.data_laudo : '--'"></td> 
              <td>
                <template x-if="item.situacao=='A'">
                  <span class="label label-warning " style="background: #bfbebe;"  ><i class="fa fa-calendar" style="padding-left:2px; "></i> Agendado</span>
                </template>
                <template x-if="item.situacao=='E'">
                  <span class="label label-warning "  ><i class="fa fa-calendar" style="padding-left:2px; "></i> Pendente</span>
                </template>
                <template x-if="item.situacao=='L'">
                  <span class="label label-success"   ><i class="fa fa-calendar" style="padding-left:2px; "></i> Laudado</span>
                </template>
            
                
              </td>
            </tr>
          </template>
  
        </tbody>
      </table>
    </div>
  </div>

  <div role="tabpanel col-md-12 col-sm-12 col-xs-12 col-lg-12" style="padding-top: 20px;">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active" class=""><a href="#tabLaudoModal" style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">Laudo do Exame</a></li>
      <li role="presentation" class="" class=""><a href="#tabImgModal" style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">Imagens do Exame</a></li>
    </ul>

    <div class="tab-content" style="padding: 0px;">
      <div role="tabpanel" class="tab-pane fade  active in" id="tabLaudoModal">
        <div class="row" style="padding-top: 20px;">
          <div class="col-md-12">

            <div class="panel panel-default">
              <div class="panel-heading" style="padding: 12px; height: 60px;">
                <h3 class="panel-title" style="width: 100%;"> 
                    <table width="100%">
                      <tr>
                        <td  width="50%" style="text-align: left"> 
                          <span style=" margin-right: 15px; font-style: italic" x-text="HISTORICO.history[index]['atendimento']['paciente']['nm_paciente']" ></span>
                          <br>
                          <span style=" margin-right: 15px;font-weight: 300;" x-text="HISTORICO.history[index]['atendimento']['convenio']['nm_convenio']" >  </span>
                        </td>
                        <td style="text-align: right"> 
                          <span style=" margin-right: 15px; font-style: italic" x-text="HISTORICO.history[index]['exame']['nm_exame']" ></span>
                          <br> 
                          <span style=" margin-right: 15px;font-weight: 300;" x-text="HISTORICO.history[index]['created_data']"  > </span> 
                        </td>
                      </tr>
                    </table> 
                   </h3>
              </div>
 
              <ul class="list-group"> 
                <li class="list-group-item">
                  <div class="row">
                    <div class="col-md-3">
                      <strong>Atendimento:</strong> <span x-text="HISTORICO.history[index]['cd_agendamento']"></span> 
                    </div> 
                    <div class="col-md-3">
                      <strong>Data do Laudo:</strong> <span x-text="(HISTORICO.history[index]['data_laudo']) ? HISTORICO.history[index]['data_laudo'] : ' -- '"></span> 
                    </div>
                    <div class="col-md-6">
                      <strong>Usuario da Laudo:</strong> <span x-text="(HISTORICO.history[index]['usuario_laudo']) ? HISTORICO.history[index]['usuario_laudo']['nm_usuario'] : ' -- '"></span> 
                    </div>
                  </div>
                  
                  </li>
                <li class="list-group-item">
                  <div class="row">
                    <div class="col-md-6">
                      <strong>Profissional Solicitante:</strong> <span x-text="HISTORICO.history[index]['atendimento']['profissional']['nm_profissional']"></span> 
                    </div>
                    <div class="col-md-6">
                      <strong>Profissional Executante:</strong> <span x-text="HISTORICO.history[index]['atendimento']['profissional']['nm_profissional']"></span> 
                    </div>
                  </div>
                  
                </li>
              </ul>
              <div class="panel-body">
                <template x-if="HISTORICO.history[index]['conteudo_laudo']">
                    <span x-html="HISTORICO.history[index]['conteudo_laudo']"></span>
                </template>

                <template x-if="!HISTORICO.history[index]['conteudo_laudo']">
                  <div style="text-align: center; line-height: 5px; font-style: italic; ">
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                   <img src="{{ asset('assets\images\laudo.png')}}"><br>
                   #Laudo Pendente
                   <p>&nbsp;</p>
                   <p>&nbsp;</p>
                  </div> 
                </template>
                
              </div>

            </div>
          </div>
        </div>

      </div>
      <div role="tabpanel" class="tab-pane fade  in" id="tabImgModal">

        <div class="row" style="padding-top: 20px;">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading" style="padding: 12px; height: 60px;">
                <h3 class="panel-title"style="width: 100%;">  
                  <table width="100%">
                    <tr>
                      <td  width="50%" style="text-align: left"> 
                        <span style=" margin-right: 15px; font-style: italic" x-text="HISTORICO.history[index]['atendimento']['paciente']['nm_paciente']" ></span>
                        <br>
                        <span style=" margin-right: 15px;font-weight: 300;" x-text="HISTORICO.history[index]['atendimento']['convenio']['nm_convenio']" >  </span>
                      </td>
                      <td style="text-align: right"> 
                        <span style=" margin-right: 15px; font-style: italic" x-text="HISTORICO.history[index]['exame']['nm_exame']" ></span>
                        <br> 
                        <span style=" margin-right: 15px;font-weight: 300;" x-text="HISTORICO.history[index]['created_data']"  > </span> 
                      </td>
                    </tr>
                  </table>
                </h3>
              </div>

              <div class="panel-body" style="text-align: center;  margin-top: 20px;"> 
                <template x-for="img in HISTORICO.history[index]['img']" >
                  <div>
                    <img class="img-fluid" style="max-width: 100%;" x-bind:src="img.img" >
                  </div>
                </template>
                <template x-if="(HISTORICO.history[index]['img'] == '')" >
                  <div><img class="img-fluid" style="max-width: 100%;" src="{{ asset('assets\images\sem_img.jpeg')}}" >
                  </div>
                </template>
               
                
                
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>