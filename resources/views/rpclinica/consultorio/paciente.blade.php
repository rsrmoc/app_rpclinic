<div x-data="appPaciente">
    <style>

        .form-group {
            margin-bottom: 7px;
        }

        .select2-selection {
            background: #fff !important;
            border-radius: 0 !important;
            border: 1px solid #dce1e4 !important;
            font-size: 13px !important;
            height: 30px !important;
            -webkit-transition: all .2s ease-in-out !important;
            -moz-transition: all .2s ease-in-out !important;
            -o-transition: all .2s ease-in-out !important;
            transition: all .2s ease-in-out !important;
        }
        .select2-dropdown, .select2-selection {
            box-shadow: none !important;
            padding: 4px 10px !important;
        }

        label { 
            margin-bottom: 0px; 
        }

        .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
            position: relative;
            min-height: 1px;

        }

    </style>
    <div class="row"> 
        <div class="col-md-9 col-xs-12">
            <form role="form" id="form-pac"   method="post" x-on:submit.prevent="getSalvar"  enctype="multipart/form-data">
                @csrf
                <input name="foto" type="file" style="opacity:0; height: 0;" id="foto">
                <input name="fotoCopy" type="text" style="opacity:0; height: 0;" id="fotoCopy">

                <div class="row">
          

                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Nome: </strong><span class="red normal">*</span></label>
                            <input type="text" class="form-control required input-sm" required x-model="dadosPaciente.nome"  name="nm_paciente" maxlength="100" aria-required="true">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>Data Nasc.: </strong><span class="red normal">*</span></label>
                            <input type="date" class="form-control input-sm" x-model="dadosPaciente.data_de_nascimento"  name="dt_nasc" required maxlength="100" aria-required="true">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>RG: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm"  x-model="dadosPaciente.rg" name="rg"   maxlength="100" aria-required="true" >
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>CPF: </strong><span class="red normal"></span></label>
                            <input x-mask="999.999.999-99" class="form-control input-sm"  x-model="dadosPaciente.cpf" name="cpf" maxlength="100" aria-required="true" type="text"  >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>Sexo: </strong><span class="red normal"></span></label>
                            <select name="sexo" x-model="dadosPaciente.sexo" id="PacSexo"  class="form-control input-sm" style="width: 100%;">
                                <option value="">SELECIONE</option>
                                <option value="H" @if(old('data_de_nascimento')=='H') selected @endif>Masculino</option>
                                <option value="M" @if(old('data_de_nascimento')=='M') selected @endif>Feminino</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Nome Social: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control  input-sm"  x-model="dadosPaciente.nome_social"   name="nome_social" maxlength="100" aria-required="true">
                        </div>
                    </div>



                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>Estado civil: </strong><span class="red normal"></span></label>
                            <select class="form-control input-sm" x-model="dadosPaciente.estado_civil" id="PacEstado_civil" name="estado_civil"   style="width: 100%;">
                                <option value="">SELECIONE</option>
                                <option value="S"  >Solteiro</option>
                                <option value="C" >Casado</option>
                                <option value="D" >Divorciado</option>
                                <option value="V" >Viúvo</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Cartão SUS: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.cartao_sus" name="cartao_sus"  maxlength="100" aria-required="true" >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Nome da mâe: </strong><span class="red normal"> </span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.nome_da_mae" name="nm_mae" maxlength="100" aria-required="true"   >
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Nome do pai: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.nome_do_pai" name="nm_pai" maxlength="100" aria-required="true">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Nome do Responsável: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.nm_responsavel" name="nm_responsavel" maxlength="100" aria-required="true">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>CPF Resp.: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.cpf_responsavel" name="cpf_responsavel" maxlength="100" aria-required="true">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Profissão: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.profissao" name="profissao" maxlength="100" aria-required="true">
                        </div>
                    </div>
 

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="mat-label"><strong>Convênio</strong> <span
                                    class="red normal">*</span></label>
                            <select class="form-control input-sm" x-model="dadosPaciente.convenio" id="PacConvenio" name="cd_categoria"  style="width: 100%;">
                                <option value="">SELECIONE</option>
                        
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>Cartão: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.cartao" name="cartao" maxlength="100" aria-required="true">
                        </div>
                    </div>
           
                    <div class="col-md-2">
                        <div class="form-group ">
                            <label><strong>Cep: </strong><span class="red normal"></span></label> 
                                <div class="input-group m-b-sm ">
                                    <input type="text" x-mask="99999-999" class="form-control input-sm" x-model="dadosPaciente.cep"
                                    name="cep" maxlength="100"  aria-required="true">
                                    <span class="input-group-addon" style="cursor: pointer;" x-on:click="buscarCep">
                                    <i class="fa fa-thumb-tack" style="margin-right: 0px;"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Rua: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.logradouro"  name="logradouro" maxlength="100" aria-required="true">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>Número: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm"  x-model="dadosPaciente.numero"  name="numero" maxlength="100" aria-required="true">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>Complemento: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.complemento" name="complemento" maxlength="100" aria-required="true">
                        </div>
                    </div>
              
                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Bairro: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.bairro"   name="nm_bairro" maxlength="100" aria-required="true">
                        </div>
                    </div>
           

                    <div class="col-md-4">
                        <div class="form-group">
                            <label><strong>Cidade: </strong><span class="red normal"></span></label>
                            <input type="text" class="form-control input-sm" x-model="dadosPaciente.cidade" name="cidade" maxlength="100" aria-required="true">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Estado: </strong><span class="red normal"></span></label>
                            <select name="uf" id="PacUf" x-model="dadosPaciente.uf" class="form-control input-sm"  style="width: 100%;">
                                <option value="">SELECIONE</option>
                                @foreach (ESTADOS as $estado)
                                    <option value="{{ $estado["sigla"] }}" >{{ $estado["nome"] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

 
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>Telefone: </strong><!--<span class="red normal">*</span>--></label>
                            <input type="text" class="form-control input-sm"  x-model="dadosPaciente.telefone"  name="fone" maxlength="100" aria-required="true">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>Celular: </strong><!--<span class="red normal">*</span>--></label>
                            <input  type="text" class="form-control input-sm" x-model="dadosPaciente.celular" name="celular" maxlength="100" aria-required="true">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label><strong>Email: </strong><!--<span class="red normal">*</span>--></label>
                            <input  type="email" class="form-control input-sm" x-model="dadosPaciente.email" name="email"  maxlength="100" aria-required="true">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label><strong>VIP: </strong><span class="red normal"></span></label>
                            <select name="vip" class="form-control input-sm" x-model="dadosPaciente.vip" id="PacVip"  style="width: 100%;">
                                <option value="">SELECIONE</option>
                                <option value="S" @if( old('vip') == 'S') selected @endif>SIM</option>
                            </select>
                        </div>
                    </div>
                </div> 
                <br>
                <div class="row">
                    <div class="col-md-12"> 
                        <div class="panel-footer">
                            <button type="submit"  class="btn btn-success" x-html="buttonSalvar" > </button>
                            
                        </div>
                    
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-3">
            <div class="panel-group" id="accordionPac" role="tablist" aria-multiselectable="true">
                

                <div class="panel panel-default" style="    border-radius: 0px;">

                    <template x-if="loadHistorico==false">

                        <div> 
                            <div class="panel-heading" role="tab" id="headPacAnamnese" style="margin: 1px; padding: 10px; ">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsePacAnamnese" aria-expanded="true" aria-controls="collapseOne" style=" font-size: 12px;  " >
                                        <i class="fa fa-list"></i>&nbsp;&nbsp; Observações Importantes</span>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapsePacAnamnese" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headPacAnamnese">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <textarea class="form-control" x-model="pacienteObs " style="border: 0px; height: 300px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right" style="margin-top: 15px;margin-bottom: 0px;">
                                            <button type="button" class="btn btn-success   btn-xs" x-on:click="getObs" 
                                            style="padding: 2px; padding-left: 10px; padding-right: 10px;" x-html="buttonSalvarObs"> </button>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>

                    </template>

                    <template x-if="loadHistorico==true">

                        <div>
                            <div class="panel-heading" role="tab"   style="margin: 1px; padding: 10px; ">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion"  aria-expanded="true"  style="font-style: italic;  font-size: 12px;  "  >
                                        <i class="fa fa-spinner fa-spin"></i> &nbsp;&nbsp;  Carregando Informação
                                    </a>
                                </h4>
                            </div>
                            <div style="text-align: center; padding: 40px;">   
                                <i class="fa fa-spinner fa-spin"  style="font-size: 6em; font-weight: 300; "></i> 
                            </div>
                        </div>

                    </template>

                </div>


            
            </div>


            
        </div>
    </div>

</div>

