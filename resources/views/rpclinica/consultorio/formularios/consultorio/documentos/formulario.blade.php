<div class="  col-md-9 col-sm-9 col-xs-12 col-lg-9">

  <div class="panel panel-white ui-sortable-handle">
    <div class="panel-heading">
      <h3 class="panel-title">  
        <i class="fa fa-fw fa-user-md"></i> 
        {{ mb_convert_case($agendamento->profissional->nm_profissional, MB_CASE_TITLE, 'UTF-8') }}
      </h3>

      <div class="panel-control">

        <a href="javascript:void(0);"  
            data-toggle="tooltip"
            data-placement="top"  
            style="margin-right: 10px;"
            x-on:click="TextoPadrao('DOC')" 
            data-original-title="Texto Padrão (Documentos)">
            <i  class="icon-doc"></i>
        </a>

    </div>
    </div>
    <div class="panel-body"  >
      <form class="form-horizontal" x-on:submit.prevent="storeDocumento" id="form_DOCUMENTO" method="post"  >
        @csrf
        <input type="hidden" name="cdDocumento" x-model="Anamnese.cdDocumento">
        <div class="form-group" style="margin-bottom: 5px;">

          <div class="col-md-6"> 
                  <label class="mat-label"><strong>Lista de Documentos</strong> <span
                          class="red normal">*</span></label>
                  <select class="form-control input-sm"   style="width: 100%;" id="modeloDocumento" name="cd_formulario">
                      <option value="">...</option>
                      <template x-for="item in modeloDocumento">
                            <option :value="item.cd_formulario"
                            x-text="item.nm_formulario">
                        </option> 
                      </template>
                  </select> 
          </div>
          <div class="col-md-6"> 
            <label class="mat-label"><strong>Titulo</strong> <span
                class="red normal"></span></label>
            <input type="text" class="form-control  " style="height: 30px;"  
                name="titulo" maxlength="100" aria-required="true" x-model="Anamnese.Titulo"  >                
          </div>
          <div class="col-md-12" style="margin-top: 5px;"> 
             
             
                <textarea   id="editor-formulario-documento" name="documento"   ></textarea> 
              
            


          </div>
        </div>
        <div class="panel-footer col-md-12">

          <div class="row">
            <div class="col-md-4">
              <button type="submit" class="btn btn-success" x-html="buttonSalvar" x-bind:disabled="buttonDisabled"> </button>
               
              <button type="button" class="btn btn-primary " 
              x-on:click="cancelarEdicao" 
              x-show="(snEdicaoDoc==true)"><i class="fa fa-mail-reply"></i>  Cancelar Edição</button>
              
            </div>
            <div class="col-md-5" style="text-align: center">
  
                <code style="" 
                    x-html="'Documento ' + Anamnese.Titulo + ' {codigo: ' + Anamnese.cdDocumento + '} esta sendo editado!'"
                    x-show="(snEdicaoDoc==true)" >  
                </code>
            </div>
            <div class="col-md-3" style="text-align: right">
             
                <button type="button" class="btn btn-default btn-rounded" style="color: #12AFCB" 
                x-html="buttonModelo" x-bind:disabled="buttonModeloDisabled"
                x-on:click.prevent="storeModelo('DOC')"> 
                </button>
            </div>

          </div>

        </div>
      </form>

       
    </div>
  </div>

  
  <table class="table table-striped">
    <thead>
        <tr class="active">
            <th>Codigo</th>
            <th>Formulario</th>
            <th>Profissional</th>
            <th>Data</th>
            <th class="text-center">Ação</th>
        </tr>
        
    </thead>

    <tbody>
         
        <tr x-show="loadingDoc">
            <td colspan="5">
                <div class="line">
                    <div class="loading"></div>
                    <span>Carregando Documentos...</span>
                </div>
            </td>
        </tr>

        <template x-if="dadosDocumentos.length > 0"> 
            <template x-for="(doc,indice) in dadosDocumentos">
  
                <tr  > 
                    <th ><span x-text="doc.cd_documento"></span></th> 
                    <td ><span x-html="doc.nm_formulario + ( (doc.form_assinado==true)?iconAssinaturaDigital:'' ) "></span></td>  
                    <td ><span x-text="doc.profissional?.nm_profissional"></span></td>  
                    <td ><span x-text="doc.data_hora"></span></td>   
                    <td class="text-center"> 
                        <div class="btn-group btn-xs">
                                <button type="button" class="btn btn-default btn-addon m-b-sm btn-rounded btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="margin-bottom: 0px; font-size: 1.3rem !important; color: #7a6fbe; font-weight: 600;">
                                    &nbsp;Opções &nbsp;  <span class="caret"></span>
                                    &nbsp;
                                </button>
                                <ul class="dropdown-menu" role="menu">

                                    <li>
                                        <a href="#" x-on:click="imprimirDocumento(doc.cd_documento)" style="color: #22baa0;font-weight: 600;">
                                            <i class="fa fa-print" style="margin-left:4px; margin-right: 4px;
                                            "></i>
                                            &nbsp;Imprimir
                                        </a>
                                    </li>

                                    <template x-if="!doc.form_assinado">
                                        <li>
                                            <a href="#" x-on:click="editDocumento(doc)" style="color: #7a6fbe; font-weight: 600;">
                                                <i class="fa fa-edit" style="margin-left:4px; margin-right: 4px;  "></i>
                                                &nbsp;Editar
                                            </a>
                                        </li>
                                    </template>

                                    <template x-if="(loadingCertificado)">
                                        <template x-if="!doc.form_assinado">

                                            <li>
                                                <a href="#" x-on:click.prevent="assinarDoc('D',doc.cd_documento)" style="color: #0e9bb4; font-weight: 600;">
                                                    <span aria-hidden="true" class="icon-key" style="margin-left:4px; margin-right: 4px; "></span>
                                                    &nbsp;Assinar
                                                </a>
                                            </li>

                                        </template> 
                                  
                                    </template>
                                    
  
                                    <li class="divider" style="margin:0;"></li>

                                    <li>
                                        <a href="#" x-on:click="excluirDocumento(doc.cd_documento)"  
                                             style="color: #f25656; font-weight: 600;">
                                            <i class="fa fa-trash" style="margin-left:4px; margin-right: 4px; "></i>
                                            &nbsp;Excluir
                                        </a>
                                    </li>

                                </ul>
                            </div> 
                    </td>
                </tr>
            </template>
        </template>   
  
    </tbody>
  </table>
  <hr>
  <div class="row">
    <div class="col-md-12" style="text-align: center;">

        <label style="margin-right: 15px;">
            <div class="checker"><span><input type="checkbox" name="sn_data" value="S" id="sn_data_doc" @if(old('sn_data_header_doc',Auth::user()->sn_data_header_doc )=='S')  checked   @endif class="flat-red"></span>
            </div>Ocultar Data
        </label>

        <label style="margin-right: 15px;">
            <div class="checker"><span><input type="checkbox" name="sn_header" id="sn_header_doc" value="S" @if(old('sn_header_doc',Auth::user()->sn_header_doc )=='S')  checked   @endif class="flat-red"></span>
            </div> Ocultar Dados do paciente
        </label>
        
        <label style="margin-right: 15px;">
            <div class="checker"><span><input type="checkbox" name="sn_logo" value="S" id="sn_logo_doc" @if(old('sn_logo_header_doc',Auth::user()->sn_logo_header_doc )=='S')  checked   @endif class="flat-red"></span>
            </div>Ocultar Logo
        </label>

        <label style="margin-right: 15px;">
            <div class="checker"><span><input type="checkbox" name="sn_footer" id="sn_footer_doc" value="S" @if(old('sn_footer_header_doc',Auth::user()->sn_footer_header_doc )=='S')  checked   @endif class="flat-red"></span>
            </div>Ocultar Rodapé
        </label>

        <label style="margin-right: 15px;">
            <div class="checker"><span><input type="checkbox" name="sn_assinatura" id="sn_assinatura_doc" value="S" @if(old('sn_assina_header_doc',Auth::user()->sn_assina_header_doc )=='S')  checked   @endif class="flat-red"></span>
            </div>Ocultar Assinatura
        </label>

        <label style="margin-right: 15px;">
            <div class="checker"><span><input type="checkbox" name="sn_ocultar_titulo" id="sn_ocultar_titulo" value="S" @if(old('sn_titulo_header_doc',Auth::user()->sn_titulo_header_doc )=='S')  checked   @endif  class="flat-red"></span>
            </div>Ocultar Titulo
        </label>

        <label style="margin-right: 15px;">
            <div class="checker"><span><input type="checkbox" name="sn_rec_especial" id="sn_rec_especial" value="S"   class="flat-red"></span>
            </div>Receituario Especial
        </label>

        

    </div>
</div>
</div>

<div class="col-md-3 col-sm-3 col-xs-12 col-lg-3" style="min-height: 300px;" >

  <div class="panel panel-white ui-sortable-handle">
    <div class="panel-heading">
      <div class="row">
        <div class="col-md-12">
            <h3 class="panel-title">Histórico</h3>
            <div class="panel-control"> 
                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top"
                title="" data-original-title="Histórico Completo"><i class="icon-docs"
                    x-on:click="modalDocumentos(history.Documento,'T')"></i></a>
              </div>
        </div> 
      </div>
    </div>
  </div>
  <div class="panel-group" role="tablist" aria-multiselectable="true" >
      <template x-if="history.Documento.length > 0">
          <div class="panel panel-default" style="    border-radius: 0px; ">
              <template x-for="item in history.Documento">
                  <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
                      <h4 class="panel-title"
                          style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; "
                          x-html=" iconHistry + ' ' + item.agendamento?.data + ' -  { ' + item.cd_agendamento + ' } ' "
                          x-on:click="modalDocumentos(item,'U')">
                      </h4>
                  </div>
              </template>
          </div>
      </template>
  </div>
</div>
 

<div class="modal fade modalHistDocumentos" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
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
                                                      x-html="' [ ' + item.cd_agendamento + ' ] ' + item.agendamento?.data ">
                                                  </span>

                                              </td>
                                              <td style="text-align: right">
                                                  <span style=" margin-right: 15px; font-style: italic"
                                                      x-html="' Usuario: ' + ( (item.usuario?.nm_usuario) ? item.usuario?.nm_usuario : ' -- ' )">
                                                  </span>
                                                  <br>

                                                  <span style=" margin-right: 15px;font-weight: 300;"
                                                      x-html="(item.agendamento?.data) ? item.agendamento?.data : ' -- ' ">
                                                  </span>

                                              </td>
                                          </tr>
                                      </table>
                                  </h3>
                              </div>
                              <div class="panel-body" style="padding-top: 1.5em">
                                  <form>

                                      <div class="form-group" style="margin-bottom: 5px;">
                                          <div class="col-md-12">
                                              <label for="input-placeholder" class=" control-label"
                                                  style="  padding-top: 0px;">Conteudo:</label>
                                              <!-- <textarea  style="min-height: 400px" class="form-control"  x-html="item.conteudo"> </textarea> -->
                                              <div style="background-color: #eee; border-radius: 0; border: 1px solid #dce1e4; font-size: 13px;
                                                         padding: 6px 10px !important;" x-html="item.conteudo"> </div> 
                                          </div>
                                      </div>

                                       

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