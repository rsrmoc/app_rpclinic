<div class="modal fade" id="cadastro-agendamento">

    <div class="modal-dialog modal-fullscreen">

        <div class="modal-content">

            <div class="absolute-loading" style="display: none">
                <div class="line">
                    <div class="loading"></div>
                    <span style="font-weight: bold; font-size: 1.3em; font-style: italic" x-html="loadingAcao"></span>
                </div>
            </div>

            <div class="modal-header m-b-sm">

                <div class="line" style="justify-content: space-between">
                    <h4 class="modal-title" x-html="' &nbsp;Cadastrar Escala' + ( (modalEscala.cd_escala) ? ' - ' + modalEscala.cd_escala : '' ) "> </h4>

                    <div class="line">


                        <button type="button" x-show="snExcluir" class="btn btn-default btn-rounded" x-on:click="ExcluirAgenda" style="color: red">
                            <i class="fa fa-trash"></i> Excluir
                        </button>


                        <button type="button" class="close m-l-sm" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true" class="icon-close"></span></span>
                        </button>

                    </div>

                </div>

            </div>

            <div class="modal-body">

                <template x-if="modalData.errors.length > 0">
                    <div class="alert alert-danger">
                        <h5>Houve alguns erros:</h5>
                        <ul>
                            <template x-for="error in modalData.errors">
                                <li x-html="error"></li>
                            </template>
                        </ul>
                    </div>
                </template>

                <div class="tabpanel">

                    <ul class="nav nav-tabs nav-justified" role="tablist" x-show="snExcluir">

                        <li role="presentation" class="active">
                            <a href="#TabSobre" role="tab" data-toggle="tab" style="padding: 3px 15px; border-left: 0px;"><span aria-hidden="true" class="icon-calendar" style="margin-right: 10px;"></span> Sobre a
                                agenda</a>
                        </li>

                        <li role="presentation" class="">
                            <a href="#TabDadosAdicionais" role="tab" data-toggle="tab" style="padding: 3px 15px;">
                                <span aria-hidden="true" class="icon-user" style="margin-right: 10px;"></span>Cadastro
                                de Paciente
                            </a>
                        </li>

                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane active" role="tabpanel" id="TabSobre">

                            <div x-show="loadingModal">
                                <p class="text-center">
                                    <br><br> <img style="margin-top: 50px; height: 80px;" src="{{ asset('assets\images\carregandoFormulario.gif') }}"><br><br><br>
                                </p>
                            </div>
                            <div x-show="loadingModal==false">
                                <form x-on:submit.prevent="storeEscala" id=form-Agenda>
                                    @csrf
                                    <div class="row">

                                        <div class="col-md-8">

                                            <input type="hidden" name="cd_escala" x-bind:value="modalEscala.cd_escala" />

                                            <div class="row">

                                                <div class="col-md-6">
                                                    <label>Profissional: <span class="red normal">*</span></label> 
                                                    <div class="form-group">
                                                        <select class="form-control" style="width: 100%" name="cd_profissional" id="escala-profissional" required>
                                                            <option value="">Selecione</option>
                                                            <template x-if="camposModal.profissional">
                                                                <template x-for="profissional in camposModal.profissional">
                                                                    <option x-bind:value="profissional.cd_profissional" x-text="profissional.nm_profissional"></option>
                                                                </template>
                                                            </template>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <label>Localidade:
                                                        <span class="red normal">*</span></label>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width: 100%" name="cd_localidade" required id="escala-local">
                                                            <option value="">Selecione</option>
                                                            <template x-if="camposModal.local">
                                                                <template x-for="local in camposModal.local">
                                                                    <option x-bind:value="local.cd_escala_localidade" x-text="local.nm_localidade"></option>
                                                                </template>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <label>Data <span class="red normal">*</span></label>
                                                    <div class="form-group">
                                                        <input type="date" name="dt_agenda" x-model="modalEscala.dt_agenda"  x-on:change="dadosProfissional()" required class="form-control" id="dt_agenda" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Hora Inicio <span class="red normal">*</span></label>
                                                    <div class="form-group">
                                                        <input type="text" name="hr_inicio" x-mask="99:99" required x-model="modalEscala.hr_agenda" class="form-control center" id="hr_inicio" />
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label>Hora Término <span class="red normal">*</span></label>
                                                    <div class="form-group">
                                                        <input type="text" name="hr_fim" x-mask="99:99" required x-model="modalEscala.hr_final" class="form-control center" id="hr_fim" />
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <label>Tipo de Escala:
                                                        <span class="red normal"></span></label>
                                                    <div class="form-group">
                                                        <select class="form-control" style="width: 100%" name="cd_tipo_escala" id="escala-tipo">
                                                            <option value="">Selecione</option>
                                                            <template x-if="camposModal.tipo">
                                                                <template x-for="tipo in camposModal.tipo">
                                                                    <option x-bind:value="tipo.cd_escala_tipo" x-text="tipo.nm_tipo_escala"></option>
                                                                </template>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Qtde. Escala<span class="red normal"></span></label>
                                                    <div class="form-group">
                                                        <input type="text" name="qtde_escala" x-mask="999" x-model="modalEscala.qtde" class="form-control center"   />
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Qtde. Inst.<span class="red normal"></span></label>
                                                    <div class="form-group">
                                                        <input type="text" name="qtde_instituicao" x-mask="999" readonly x-model="modalEscala.qtde_inst" class="form-control center"   />
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Qtde. Prof.<span class="red normal"></span></label>
                                                    <div class="form-group">
                                                        <input type="text" name="qtde_profissional" x-mask="999" readonly x-model="modalEscala.qtde_prof" class="form-control center"   />
                                                    </div>
                                                </div>
                                                <div class="col-md-2" >
                                                    <label>Qtde. Final<span class="red normal"></span></label>
                                                    <div class="form-group">
                                                        <input type="text" name="qtde_final" x-mask="999" readonly x-model="modalEscala.qtde_final" class="form-control center"  />
                                                    </div>
                                                </div>

                                                <div class="col-md-4" >
                                                    <label>Situação: <span class="red normal">*</span></label>
                                                    <select id="escala-situacao" class="form-control" style="width: 100%; z-index: 5000" name="situacao" required>
                                                        <option value="">Todos as Situação</option>
                                                        <option value="Agendado">Agendado</option>
                                                        <option value="Confirmado">Confirmado</option>  
                                                    </select>
                                                </div>
                                            </div>
 
                                            <div class="row">
  
                                                <div class="col-md-12">
                                                    <label>Observações</label>
                                                    <textarea rows="5" class="form-control" name="obs" id="obs-agendamento" x-model="modalEscala.obs"></textarea>
                                                </div>

                                            </div>
 
                                            <div class="row">
  
                                                <div class="col-md-12" style="margin-top: 10px;"> 
                                                    <label>Informativos:
                                                        <span class="red normal"></span></label>
                                                    <textarea rows="3" class="form-control" name="informativo" id="informativo" x-model="modalEscala.informativo"></textarea>
                                                </div>

                                            </div>

                                            <div class="row">

                                                
                                                <div class="col-md-10" style="text-align: left; "> 

                                                </div> 
                                                <div class="col-md-2">
                                                    <div class="form-group" style="text-align: right; margin-top: 10px;">
                                                        <button type="submit" class="btn btn-success" x-html="buttonSalvarAgendamneto">
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-4" style="text-align: center;">

                                            <div role="tabpanel">
                                                <!-- Nav tabs -->
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li role="presentation" class="active"><a href="#tabDados" role="tab" data-toggle="tab">Dados</a></li>
                                                    <li role="presentation"><a href="#tabDisponib" role="tab" data-toggle="tab">Disponibilidades</a></li>
                                                    <li role="presentation"><a href="#tabEscala" role="tab" data-toggle="tab">Escala</a></li> 
                                                </ul>
                                                <!-- Tab panes -->
                                                <div class="tab-content">

                                                    <div role="tabpanel" class="tab-pane active" id="tabDados">
                                                        <p>
                                                            <ol class="breadcrumb" style="margin-top: 20px;"> 
                                                                <li class="active" style="font-weight: 600;">Dados do Profissional</li>
                                                            </ol>
                                                            <img style="opacity: 0.6; margin-top: 25px; " x-show="dados_profissional.profissional==false" src="{{ asset('assets\images\medico.png') }}">

                                                            <div style="text-align: left" x-show="dados_profissional.profissional==true">
                                                                <span> 
                                                                    <code style=" text-align: left;   background-color: #f2f2f9;    color: #0062a5;"> 
                                                                        <strong>Nome : </strong> <span x-text=" (dados_profissional.nome) ? dados_profissional.nome : ' --' "></span><br> 
                                                                    </code> 
                                                                    <code x-bind:style=" (dados_profissional.whats) ? 'text-align: left;   background-color: #f2f2f9;    color: #0062a5;' : ' ' "> 
                                                                        <strong>WhatsApp: : </strong> <span x-text=" (dados_profissional.whats) ? dados_profissional.whats : ' --' "></span><br> 
                                                                    </code> 
                                                                    <code x-bind:style=" (dados_profissional.email) ? 'text-align: left;   background-color: #f2f2f9;    color: #0062a5;' : ' ' "> 
                                                                        <strong>Email: : </strong> <span x-text=" (dados_profissional.email) ? dados_profissional.email : ' --' "></span> 
                                                                    </code>  
                                                                </span>
                                                            </div>
                                                            
                                                            <template x-if="checkEscala.dados.length > 0">
                                                                <code style="width: 100%;"> 
                                                                    <table style="width: 100%; text-align: left; margin-top: 15px; "> 
                                                                        <tr>
                                                                            <th colspan="3" style="text-align: center; font-weight: 900;"> **** DADOS DA ESCALA **** </th>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Escala </th>
                                                                            <th> Horario</th>
                                                                            <th> Localidade</th>
                                                                        </tr>
                                                                        <template x-for="item in checkEscala.dados">
                                                                            <tr>
                                                                                <td x-text="item.cd_escala_medica"></td>
                                                                                <td x-text="item.data"></td>
                                                                                <td x-text="item.localidade.nm_localidade"></td>
                                                                            </tr>
                                                                        </template>
                                                                    </table>
                                                                </code>
                                                            </template>
                                                            
                                                        </p>
                                                    </div>

                                                    <div role="tabpanel" class="tab-pane" id="tabDisponib">
                                                        <p>
                                                            <ol class="breadcrumb" style="margin-top: 20px;"> 
                                                                <li class="active" style="font-weight: 600;">Horarios Disponíveis</li>
                                                            </ol>
                                                            
                                                            <template x-if="!dados_profissional.disponibilidade">
                                                                <img style="opacity: 0.6; margin-top: 25px; " src="{{ asset('assets\images\calendario_disponivel.png') }}">
                                                            </template>

                                                            <template x-if="dados_profissional.disponibilidade">
                                                                <div class="row" style="text-align: left">
                                                                    <template x-for="item in dados_profissional.disponibilidade">
                                                                        <div class="col-md-6" x-html=" ' &#10152; ' + item.data + ' <b>[ ' + item.dia + ' ] </b>'"></div>
                                                                    </template>
                                                                </div>
                                                            </template> 

                                                        </p>
                                                    </div>

                                                    <div role="tabpanel" class="tab-pane" id="tabEscala">
                                                        <p>
                                                            <ol class="breadcrumb" style="margin-top: 20px;"> 
                                                                <li class="active" style="font-weight: 600;">Dados de Escala</li>
                                                            </ol>

                                                            <template x-if="!dados_profissional.escala">
                                                                <img style="opacity: 0.6; margin-top: 25px; "  src="{{ asset('assets\images\calendario_escalas.png') }}">
                                                            </template>

                                                            <template x-if="dados_profissional.escala">
                                                                <table class="table-striped table" style="width: 100%; text-align: left; margin-top: 15px; "> 
                                                                     
                                                                    <tr class="active">
                                                                        <th>Escala </th>
                                                                        <th> Horario</th>
                                                                        <th> Localidade</th>
                                                                    </tr>
                                                                    <template x-for="item in dados_profissional.escala">
                                                                        <tr>
                                                                            <th x-text="item.cd_escala_medica"></th>
                                                                            <td x-text="item.data + ' [ '+ item.hri + ' - ' + item.hrf +' ]'"></td>
                                                                            <td x-text="item.localidade.nm_localidade"></td>
                                                                        </tr>
                                                                    </template>
                                                                </table>
                                                            </template> 
                                                        </p>
                                                    </div>
                                                  
                                                </div>
                                            </div>

                                            

                                        </div>

                                    </div>

                                </form>

                            </div>


                        </div>

                        <div class="tab-pane" role="tabpanel" id="TabDadosAdicionais">

                                #dadosAdicionais

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
