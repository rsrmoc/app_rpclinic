<div class="  col-md-8 col-sm-8 col-xs-12 col-lg-8">

    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading" style="    padding-left: 0px;">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">
                      <i class="fa fa-fw fa-user-md"></i> 
                        {{ mb_convert_case($agendamento->profissional->nm_profissional, MB_CASE_TITLE, 'UTF-8') }}
                    </h3>
                    <div class="panel-control">
 
                        <a href="javascript:void(0);" 
                            x-on:click="TextoPadrao('ATE')" 
                            data-toggle="tooltip"
                            data-placement="top"  
                            style="margin-right: 10px;"
                            data-original-title="Texto Padrão (Anamnese)">
                            <i class="icon-doc"></i>
                        </a>

                        <a href="javascript:void(0);" 
                           x-on:click="imprimirAnamnese" 
                           data-toggle="tooltip"
                           data-placement="top"  
                           style="margin-right: 10px;"
                           data-original-title="Imprimir Anamnese">
                           <i  class="icon-printer"></i>
                        </a> 
 
                        <a href="javascript:void(0);" x-on:click="deleteAnamnese()" data-toggle="tooltip"
                            data-placement="top" title="" data-original-title="Exluir Anamnese"><i
                                class="icon-close"></i></a>


                    </div>

                </div>
            </div>
        </div>
        <div class="panel-body" style="    padding: 0 0px 0px;" x-data="{ index: 0, showHistory: false }">
            <form class="form-horizontal" x-on:submit.prevent="storeAnamnese" id="form_ANAMNESE" method="post">
                @csrf

                    <div class="col-md-12">
                        <div class="form-group" style="margin-bottom: 5px;">
                            <label class="mat-label"><strong>Texto Padrão</strong> <span class="red normal"></span></label>
                            <select class="form-control input-sm"  style="width: 100%;"
                                id="modeloAnamnese" name="modeloAnamnese">
                                <option value="">...</option>
                                <template x-for="item in modeloAnamnese">
                                    <option :value="item.cd_formulario" x-text="item.nm_formulario">
                                    </option>
                                </template>

                            </select>
                        </div>
                    </div>
 

                    <div class="form-group" style="margin-bottom: 5px;" x-show="view.historiaPregressa">
                        <div class="col-md-12">
                            <label for="input-placeholder" class="control-label" style="padding-top: 0px;"> 
                                História Pregressa:</label>
                            <textarea rows="7" class="form-control" name="historia_pregressa" id="editor-formulario-pregressa"  ></textarea>
                        </div>
                    </div>
 
                    <div class="form-group" style="margin-bottom: 5px;" x-show="view.anamnese">
                        <div class="col-md-12">
                            <label for="input-placeholder" class="control-label" style="padding-top: 0px;">
                                Anamnese:</label>
                            <textarea rows="10" class="form-control" name="anamnese" id="editor-formulario-anamnese"  ></textarea>
                        </div>
                    </div>
   
                    <div class="form-group" style="margin-bottom: 5px;" x-show="view.exameFisico">
                        <div class="col-md-12">
                            <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Exame  Fisico:</label>
                            <textarea rows="5" class="form-control" name="exame_fisico" id="editor-formulario-exame"  ></textarea>
                        </div>
                    </div>
                      
                    <div class="form-group" style="margin-bottom: 5px;"  x-show="view.hipoteseDiag">
                        <div class="col-md-12">
                            <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Hipótese
                                Diagnóstica:</label>
                            <textarea rows="5" class="form-control" name="hipotese_diagnostica"  id="editor-formulario-hipotese" ></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 5px;"   x-show="view.conduta">
                        <div class="col-md-12">
                            <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Conduta:</label>
                            <textarea rows="5" class="form-control" name="conduta"  id="editor-formulario-conduta"></textarea>
                        </div>
                    </div>
                     
 
 
                <div class="panel-footer col-md-12">

                    <div class="row">
                        <div class="col-md-6">

                            <button type="submit" class="btn btn-success" x-html="buttonSalvar"
                                x-bind:disabled="buttonDisabled"> </button> 

                        </div>
   

                        <div class="col-md-6" style="text-align: right">
                            <button type="button" class="btn btn-default btn-rounded" style="color: #12AFCB"
                                x-html="buttonModelo" x-bind:disabled="buttonModeloDisabled"
                                x-on:click.prevent="storeModelo('ATE')">
                            </button>
                        </div>

                    </div>

                </div>
                
                <div class="row">
                    <div class="col-md-12" style="text-align: center;">

                        <label style="margin-right: 15px;">
                            <div class="checker"><span><input type="checkbox" name="sn_data" value="S" @if(old('sn_data_header_doc',Auth::user()->sn_data_header_doc )=='S')  checked   @endif id="sn_data" class="flat-red"></span>
                            </div>Ocultar Data
                        </label>

                        <label style="margin-right: 15px;">
                            <div class="checker"><span><input type="checkbox" name="sn_header" id="sn_header" value="S" @if(old('sn_header_doc',Auth::user()->sn_header_doc )=='S')  checked   @endif class="flat-red"></span>
                            </div> Ocultar Dados do paciente
                        </label>
                        
                        <label style="margin-right: 15px;">
                            <div class="checker"><span><input type="checkbox" name="sn_logo" value="S" id="sn_logo" @if(old('sn_logo_header_doc',Auth::user()->sn_logo_header_doc )=='S')  checked   @endif class="flat-red"></span>
                            </div>Ocultar Logo
                        </label>

                        <label style="margin-right: 15px;">
                            <div class="checker"><span><input type="checkbox" name="sn_footer" id="sn_footer" value="S" @if(old('sn_footer_header_doc',Auth::user()->sn_footer_header_doc )=='S')  checked   @endif class="flat-red"></span>
                            </div>Ocultar Rodapé
                        </label>

                        <label style="margin-right: 15px;">
                            <div class="checker"><span><input type="checkbox" name="sn_assinatura" id="sn_assinatura" value="S" @if(old('sn_assina_header_doc',Auth::user()->sn_assina_header_doc )=='S')  checked   @endif class="flat-red"></span>
                            </div>Ocultar Assinatura
                        </label>

                    </div>
                </div>

            </form>

        </div>
    </div>

</div>

<div class="col-md-4 col-sm-4 col-xs-12 col-lg-4">

    <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a style="border-bottom: hidden; margin-right: 0px;" href="#tabHistoricoAnam" role="tab" data-toggle="tab" aria-expanded="true">
                    <i class="fa fa-th-list"></i> Histórico
                </a>
            </li>
            <li role="presentation" class="">
                <a href="#tabExameAnam" style="border-bottom: hidden; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">
                    <i class="fa fa-medkit"></i> Exames
                </a>
            </li> 
            <li role="presentation" class="">
                <a href="#tabArquivosAnam" style="border-bottom: hidden; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">
                    <i class="fa fa-file"></i> Arquivos
                </a>
            </li> 
        </ul>
        <!-- Tab panes -->
        <div class="tab-content" style="padding-left: 0px; padding-right: 0px;">

            <div role="tabpanel" class="tab-pane fade active in" id="tabHistoricoAnam" >
                
                <div class="panel panel-white ui-sortable-handle">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="panel-title" >Histórico</h3>
                                <div class="panel-control">
                                    <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title=""
                                        data-original-title="Histórico Completo"><i class="icon-docs"
                                            x-on:click="modalAnamnese(history.Anamnese,'T')"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <template x-if="Anamnese.loadHist==false"> 
                    <div class="panel-group" role="tablist" aria-multiselectable="true">
                        <template x-if="history.Anamnese.length > 0">
                            <div class="panel panel-default" style="    border-radius: 0px;">
                                <template x-for="item in history.Anamnese">
                                    <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
                                        <line> 
                                            <h4 class="panel-title"
                                                style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; white-space: nowrap; "
                                                x-html=" iconHistry + ' ' + item.data + ' -  { ' + item.cd_agendamento + ' } ' "
                                                x-on:click="modalAnamnese(item,'U')">
                                            </h4>
                                        </line>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="Anamnese.loadHist==true"> 
                    <div> 
                        <div style="text-align: center; padding: 40px;">   
                            <i class="fa fa-spinner fa-spin"  style="font-size: 6em; font-weight: 300; "></i> 
                        </div>
                    </div> 
              </template>

            </div>

            <div role="tabpanel" class="tab-pane fade" id="tabExameAnam">
                
                <template x-if="Anamnese.loadExame==false">
                    <div> 
                        <template x-for="item in historico_exames">

                            <div class="panel-group"
                                style="margin-bottom: 5px;" role="tablist"
                                aria-multiselectable="true">
                                <div class="panel panel-default"
                                    style="border-radius: 0px;">
                                    <div class="panel-heading" role="tab"
                                        style="margin: 1px; padding: 10px; padding-bottom: 2px; padding-top: 2px; ">
                                        <h4 class="panel-title"
                                            style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; ">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <spam
                                                        style="font-weight: normal; font-size: 11px;"
                                                        x-html="item.atendimento?.dt_agenda.substr(0, 10).split('-').reverse().join('/') ">
                                                    </spam> - <span
                                                        x-html="item.atendimento?.profissional?.nm_profissional">
                                                    </span> <br>
                                                    <spam
                                                        x-html="item.exame.nm_exame">
                                                    </spam>
                                                </div>
                                                <div class="col-md-2"
                                                    style="text-align: center">
                                                    <a target="_blank"
                                                        x-bind:href="'/rpclinica/central-laudos-documento/' +
                                                        item.cd_agendamento_item">
                                                        <img
                                                            src="{{ asset('assets\images\pdf.png') }}">
                                                    </a>

                                                </div>
                                            </div>
                                        </h4>
                                    </div>
                                </div>
                            </div>

                        </template>
                        <template x-if="(Anamnese.historico_exames==0)">
                            <div style="text-align: center; margin-top: 100px;"> 
                                <img src="{{ asset('assets\images\exame_historico.png')}}" style="text-align: center;"><br>
                                #Sem registro de Exames
                            </div> 
                        </template>
                    </div>
                </template>

                <template x-if="Anamnese.loadExame==true">

                    <div> 
                        <div style="text-align: center; padding: 40px;">   
                            <i class="fa fa-spinner fa-spin"  style="font-size: 6em; font-weight: 300; "></i> 
                        </div>
                    </div>

              </template>

            </div>

            <div role="tabpanel" class="tab-pane fade" id="tabArquivosAnam" >
 
                <form class="form-horizontal" x-on:submit.prevent="storeFile" enctype="multipart/form-data" 
                      id="form_ARQUIVO_ANAM" method="post">
                      @csrf
                    <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-md-10"> 
                        <input type="file" class="form-control"  name="image">
                      </div>
                      <div class="col-md-2">
                        <button type="submit" class="btn btn-success  m-b-xs"><i class="fa fa-upload"></i></button>
                      </div>
                    </div>
                     
                </form>

                  <template x-if="Anamnese.loadFile==false">

                    <div style=" height: 800px;width: 100%; overflow-y: scroll; overflow-x: hidden;" > 
                        <template x-if="Anamnese.historico_arquivos" >

                            <template x-for="item in Anamnese.historico_arquivos">

                                <div class="panel panel-white" style="padding-left:5px; padding-right:5px;">
                                    <div class="panel-heading"
                                        style="padding: 12px; height: 60px;">
                                        <h3 class="panel-title"style="width: 100%;">
                                            <table width="100%">
                                                <tr>
                                                    <td width="70%"
                                                        style="text-align: left"> 
                                                        <span
                                                            style=" margin-right: 15px;font-weight: 300;"
                                                            x-html="item.data+'<br>'+item.usuario">
                                                        </span>
                                                    </td>
                                                    <td width="15%"
                                                    style="text-align: center">
                                                        <line> 
                                                            
                                                            <div class="panel-control">
                                                                <a href="javascript:void(0);"
                                                                    data-toggle="tooltip"
                                                                    x-on:click="deleteExaImg(item.cd_img_formulario)"
                                                                    data-placement="top"
                                                                    style="margin-left: 10px;"
                                                                    title=""
                                                                    data-original-title="Exluir"><i
                                                                        class="icon-close"></i></a>
                                                            </div>
                                                            <div class="panel-control">
                                                                <a href="javascript:void(0);" 
                                                                    data-placement="top"
                                                                    title=""
                                                                    style="font-size: 16px;"
                                                                    data-toggle="modal" data-target=".modalArquivoAnamnese"
                                                                    x-on:click="modalFile(item)" >
                                                                    <i class="icon-eye"></i>
                                                                </a>
                                                            </div>
                                                        </line>
                                                    </td> 
                                                </tr>
                                            </table>
                                        </h3>
                                    </div>
                                    <div class="panel-body" style="text-align: center;min-height: 300px; padding-left:5px; padding-right:5px;" >
                                        
                                        <template x-if="(item.tipo=='img')">
                                            <img class="img-fluid" data-toggle="modal" data-target=".modalArquivoAnamnese"
                                                style="max-height: 300px;max-width: 100%; cursor: pointer;" x-on:click="modalFile(item)"
                                                x-bind:src="item.conteudo_img" />
                                        </template>

                                        <template x-if="(item.tipo=='pdf')">
                                            <line> 
                                                <iframe x-bind:src="item.conteudo_img" 
                                                    frameBorder="0" scrolling="auto" 
                                                    style="height: 300px; width: 100%; border: 5px solid #525659; ">
                                                </iframe>
                                            </line>
                                            <line> 
                                            
                                            </line>
                                        </template>
                                    </div>
                                </div>

                            </template>
                        </template>
                        <template x-if="(Anamnese.historico_arquivos==0)">
                            <div style="text-align: center; margin-top: 100px;"> 
                                <img src="{{ asset('assets\images\aquivos_historico.png')}}" style="text-align: center;"><br>
                                #Sem registro de Arquivos
                            </div> 
                        </template>
                    </div>

                  </template>

                  <template x-if="Anamnese.loadFile==true">

                        <div> 
                            <div style="text-align: center; padding: 40px;">   
                                <i class="fa fa-spinner fa-spin"  style="font-size: 6em; font-weight: 300; "></i> 
                            </div>
                        </div>

                  </template>
 

                    <div class="modal fade modalArquivoAnamnese" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="icon-close"></i></span></button>
                                    <h4 class="modal-title" id="myLargeModalLabel">
                                        <span x-html="Anamnese.modal_arquivo.descricao"> </span> <small style="margin-left: 10px;" x-text="Anamnese.modal_arquivo.data"></small>
                                    </h4>
                                </div>
                                <div class="modal-body" style="text-align: center">
                                    
                                    <template x-if="(Anamnese.modal_arquivo.tipo=='img')">
                                        <img class="img-fluid"  x-bind:src="Anamnese.modal_arquivo.conteudo_img" 
                                        style="max-height: 600px;max-width: 100%; "/>
                                    </template>
    
                                    <template x-if="(Anamnese.modal_arquivo.tipo=='pdf')">
                                        <iframe x-bind:src="Anamnese.modal_arquivo.conteudo_img"
                                            frameBorder="0" scrolling="auto"
                                            style="height: 600px; width: 100%; border: 5px solid #525659">
                                        </iframe>
                                    </template>

                                </div>
                          
                            </div>
                        </div>
                    </div>

            </div>
           
        </div>
    </div>


</div>

<div class="modal fade modalHistFormularios" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myLargeModalLabel"
                    style="font-size: 30px;font-weight: 300;font-style: italic;">
                    {{ $agendamento->paciente->nm_paciente }}</h4>
            </div>

            <div class="panel-body" style="padding-top: 1.5em">

              <template x-if="Modal">

                  <template x-for="item in Modal">
                    
                      <fieldset disabled>

                          <div class="panel panel-default"
                              style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid #ddd !important; ">
                              <div class="panel-heading" style="padding: 12px; height: 60px;">
                                  <h3 class="panel-title"style="width: 100%;">

                                      <table width="100%">
                                          <tr>
                                              <td width="50%" style="text-align: left">
                                                  <span style=" margin-right: 15px; font-style: italic"
                                                      x-html="(item.profissional?.nm_profissional) ? item.profissional?.nm_profissional : ' -- '">
                                                  </span>
                                                  <br>
                                                  <span style=" margin-right: 15px;font-weight: 300;"
                                                      x-html="' [ ' + item.cd_agendamento + ' ] ' + item.data ">
                                                  </span>

                                              </td>
                                              <td style="text-align: right">
                                                  <span style=" margin-right: 15px; font-style: italic"
                                                      x-html="' Usuario: ' + ( (item.user_anamnese?.nm_usuario) ? item.user_anamnese?.nm_usuario : ' -- ' )">
                                                  </span>
                                                  <br>

                                                  <span style=" margin-right: 15px;font-weight: 300;"
                                                      x-html="(item.data) ? item.data : ' -- ' ">
                                                  </span>

                                              </td>
                                          </tr>
                                      </table>
                                  </h3>
                              </div>
                              <div class="panel-body" style="padding-top: 1.5em">
                                  <form>
                                    
                                        <template x-if="(item.historia_pregressa != '')">
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-md-12">
                                                    <label for="input-placeholder" class=" control-label"
                                                        style="  padding-top: 0px;">História Pregressa:</label>
                                                        <!--<textarea style="min-height: 300px" class="form-control" x-html="item.historia_pregressa"> </textarea> --> 
                                                        <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                         padding: 6px 10px !important;"  x-html="item.historia_pregressa"> </div>  
                                                
                                                </div>
                                            </div>
                                        </template>
                                   
                                
 
                                        <template x-if="(item.anamnese != '')">
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-md-12">
                                                    <label for="input-placeholder" class=" control-label"
                                                        style="  padding-top: 0px;">Anamnese:</label>
                                                        <!--<textarea style="min-height: 400px" class="form-control" x-html="item.anamnese"> </textarea>   --> 
                                                        <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                         padding: 6px 10px !important;"  x-html="item.anamnese"> </div>  
                                                </div>
                                            </div>
                                        </template>
                                    
                                     
                                        <template x-if="(item.exame_fisico != '')">
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-md-12">
                                                    <label for="input-placeholder" class=" control-label"
                                                        style="  padding-top: 0px;">Exame Fisico:</label>
                                                    <!--<textarea style="min-height: 400px" class="form-control" x-html="item.exame_fisico"> </textarea> -->  
                                                    <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                         padding: 6px 10px !important;"  x-html="item.exame_fisico"> </div>  
                                                </div>
                                            </div>
                                        </template>
                                     
                                     
                                        <template x-if="(item.hipotese_diagnostica != '')">
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-md-12">
                                                    <label for="input-placeholder" class=" control-label"
                                                        style="  padding-top: 0px;">Hipótese Diagnóstica:</label> 
                                                        <!--<textarea class="form-control" style="min-height: 300px" x-html="item.hipotese_diagnostica"> </textarea> -->
                                                        <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                         padding: 6px 10px !important;"  x-html="item.hipotese_diagnostica"> </div>  
                                                    
                                                </div>
                                            </div> 
                                        </template>
                                   
                                     
                                        <template x-if="(item.conduta != '')">
                                            <div class="form-group" style="margin-bottom: 5px;">
                                                <div class="col-md-12">
                                                    <label for="input-placeholder" class=" control-label"
                                                        style="  padding-top: 0px;">Conduta:</label>
                                                    <!--<span class="form-control" style="min-height: 300px" x-html="item.conduta"> </span>  -->

                                                    <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                         padding: 6px 10px !important;"  x-html="item.conduta"> </div>
                                                </div>
                                            </div>
                                        </template>
                                   

                                  </form>

                              </div>
                          </div>
                      </fieldset>
                  </template>

              </template>

              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
              </div>

            </div>

        </div>

    </div>
</div>

 
