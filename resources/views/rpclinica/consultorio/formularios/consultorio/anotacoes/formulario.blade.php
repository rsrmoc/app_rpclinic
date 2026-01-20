<div class=" col-md-offset-1 col-md-10 col-sm-10 col-xs-12 col-lg-10">

  <div class="panel panel-white ui-sortable-handle">
    <div class="panel-heading">
      <h3 class="panel-title">  
        <i class="fa fa-fw fa-user-md"></i> 
        {{ mb_convert_case($agendamento->profissional->nm_profissional, MB_CASE_TITLE, 'UTF-8') }}
      </h3>
      <div class="panel-control">

    
      </div>
    </div>
    <div class="panel-body"  >
      <form class="form-horizontal" x-on:submit.prevent="storeAnotacao" enctype="multipart/form-data"
       id="form_ANOTACAO" method="post"  >
        @csrf
   
        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-md-12">
            <label for="input-placeholder" class="control-label" style="padding-top: 0px;"> Descrição da anotação:</label>
            <textarea rows="10" class="form-control" name="Anotacao" x-model="Anamnese.Anotacao"></textarea>
          </div>
        </div>
        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-md-12"> 
            <input type="file" class="form-control" id="imageFile" name="image">
          </div>
        </div>
        <div class="panel-footer col-md-12">

          <div class="row">
            <div class="col-md-6">
              <button type="submit" class="btn btn-success" x-html="buttonSalvar" x-bind:disabled="buttonDisabled"> </button> 
            </div>
            <div class="col-md-6" style="text-align: right">
           
            </div>

          </div>

        </div>
      </form>
 
    </div>
  </div>

  
  
    <div class="form-group" style="margin-bottom: 5px;">
      <fieldset disabled>

        <template x-if="dadosAnotacao"> 
          <template x-for="item in dadosAnotacao">

            <div class="panel panel-default"
                style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid #ddd !important; ">
                <div class="panel-heading" style="padding: 12px; height: 60px;">
                    <h3 class="panel-title"style="width: 100%;">

                        <table width="100%">
                            <tr>
                                <td width="50%" style="text-align: left">
                                    <span style=" margin-right: 15px; font-style: italic"
                                        x-html="'Atendimento: ' + item.dados.cd_agendamento ">
                                    </span>
                                    <br>
                                    <span style=" margin-right: 15px;font-weight: 300;"
                                        x-html=" item.dados.data ">
                                    </span>

                                </td>
                                <td style="text-align: right">
                                    <span style=" margin-right: 15px; font-style: italic"
                                        x-html="' Usuario: ' + ( (item.dados.tab_usuario?.email) ? item.dados.tab_usuario?.email : ' -- ' )">
                                    </span>
                                    <br>

                                    <span style=" margin-right: 15px;font-weight: 300;"
                                        x-html="(item.dados.tab_usuario?.nm_usuario) ? item.dados.tab_usuario?.nm_usuario : ' -- ' ">
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
                                  style="  padding-top: 0px;">Anotação:</label>
                              <textarea rows="10" class="form-control" name="comentario" x-html="item.dados.conteudo">
                            </textarea>
                            <br>
                            <template x-if="(item.tipo=='img')">
                                <img class="img-fluid"
                                    style="max-height: 400px;max-width: 100%;"
                                    x-bind:src="item.conteudo_arquivo" />
                            </template>

                            <template x-if="(item.tipo=='pdf')">
                                <iframe 
                                    x-bind:src="item.conteudo_arquivo"
                                    frameBorder="0"
                                    scrolling="auto" 
                                    style="height: 500px; width: 100%; border: 5px solid #525659">
                                </iframe>
                            </template>

                          </div>
                        </div>
      
                    </form>

                </div>
            </div>

          </template>
        </template>

      </fieldset>
    </div>

</div>

 
