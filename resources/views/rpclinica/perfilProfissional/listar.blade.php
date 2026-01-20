@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro do Perfil do Profissional</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a
                        href="{{ route('perfil-profi.listar') }}">{{ $profissional['nm_profissional'] . ' [ ' . $profissional['cd_profissional'] . ' ] ' }}</a>
                </li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">

                    <div role="tabpanel">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs nav-justified" role="tablist">

                            <li role="presentation" class="active">
                                <a href="#tabFormulario" role="tab" data-toggle="tab">
                                    <i class="fa fa-user m-r-xs"></i> Formularios
                                </a>
                            </li>

                            <li role="presentation">
                                <a href="#tabConfiguracao" role="tab" data-toggle="tab">
                                    <i class="fa fa-gears"></i> Configuração do Profissional
                                </a>
                            </li>

                            <li role="presentation" class="">
                                <a href="#tabCertificado" role="tab" data-toggle="tab">
                                    <i class="fa fa-certificate"></i> Certificado Digital
                                </a>
                            </li>

                        </ul>
 
                        <!-- Tab panes -->
                        <div class="tab-content">

                            <div role="tabpanel" class="tab-pane fade in active " id="tabFormulario"
                                x-data="appFormulario">

                                <form x-on:submit.prevent="formularioSubmit" id="formulario-texto">

                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                        aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Campos Inteligentes</h4>
                                                </div>
                                                <div class="modal-body">

                                                    <table class="table  ">
                                                        <thead>
                                                            <tr>
                                                                <th width="100%" colspan="2" class="text-center">PACIENTE
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Codigo</th>
                                                                <td>[paciente] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Nome</th>
                                                                <td>[paciente_nome] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_nome]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Idade</th>
                                                                <td>[paciente_idade] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_idade]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Data de Nascimento</th>
                                                                <td>[paciente_nascimento] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_nascimento]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Nome da Mãe</th>
                                                                <td>[paciente_mae] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_mae]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Nome do Pai</th>
                                                                <td>[paciente_pai] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_pai]')">
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <th scope="row">CPF</th>
                                                                <td>[paciente_cpf] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_cpf]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">RG</th>
                                                                <td>[paciente_rg] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_rg]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Estado Civil</th>
                                                                <td>[estado_civil] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_civil]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Telefone</th>
                                                                <td>[telefone] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[telefone]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Celular</th>
                                                                <td>[celular] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[celular]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">E-mail</th>
                                                                <td>[email] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[email]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Endereço</th>
                                                                <td>[paciente_end] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_end]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Endereço Numero</th>
                                                                <td>[paciente_end_num] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_end_num]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Bairro</th>
                                                                <td>[paciente_bairro] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_bairro]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Cidade</th>
                                                                <td>[paciente_cidade] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_cidade]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">UF</th>
                                                                <td>[paciente_uf] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_uf]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">CEP</th>
                                                                <td>[paciente_cep] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[paciente_cep]')">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th width="100%" colspan="2" class="text-center">
                                                                    PROFISSIONAL</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Nome</th>
                                                                <td>[profissional_nome] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[profissional_nome]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Conselho</th>
                                                                <td>[profissional_conselho] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[profissional_conselho]')">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th width="100%" colspan="2" class="text-center">
                                                                    ATENDIMENTO</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">Nr.Atendimento</th>
                                                                <td>[atendimento] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[atendimento]')">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th scope="row">Data</th>
                                                                <td>[atendimento_data] <img height="18"
                                                                        style="margin-left: 15px; cursor: pointer;"
                                                                        src="{{ asset('assets/images/copiar-texto.png') }}"
                                                                        x-on:click="copyText('[atendimento_data]')">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIM Modal -->

                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="fname">Nome: <span class="red normal">*</span></label>

                                                <input type="text" class="form-control required" value=""
                                                    name="nome" maxlength="100" aria-required="true" required
                                                    x-model="inputsFormulario.nome">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="mat-label">Tipo de Formulário <span
                                                        class="red normal">*</span></label>
                                                <select id="tipo_formulario" name="tipo_formulario"
                                                    class="js-states form-control select2-hidden-accessible"
                                                    tabindex="-1" style="width: 100%" aria-hidden="true" required>
                                                    <option value="">Selecione</option>
                                                    <option value="ATE">Atendimentos/Anammnese</option>
                                                    <option value="DOC">Documentos</option>
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-1">
                                            <div class="form-group"> 
                                                <button type="button" style="margin-top: 23px;" data-toggle="modal"
                                                    data-target="#myModal" class="btn btn-success m-b-xs"><i
                                                        class="fa fa-flickr"></i></button>
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="row"> 
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="mat-label"><strong>Conteudo</strong> <span
                                                        class="red normal">*</span></label>
                                                 <textarea name="conteudo" id="conteudo_formulario"></textarea>  
                                                <!--<textarea rows="10" class="form-control" x-model="inputsFormulario.conteudo"  name="conteudo"></textarea>-->
                                            </div>
                                        </div>

                                        <div x-show="(tipo_formulario=='ATE')">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="mat-label"><strong>Exame Fisico</strong> <span
                                                            class="red normal"></span></label>
                                                    <textarea rows="10" class="form-control" x-model="inputsFormulario.exame" id="conteudo_exame" name="exame"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group"> 
                                                    <label class="mat-label"><strong>Hipótese Diagnóstica</strong> <span
                                                            class="red normal"></span></label>
                                                    <textarea rows="10" class="form-control" x-model="inputsFormulario.hipotese" id="conteudo_hipotese" name="hipotese"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group"> 
                                                    <label class="mat-label"><strong>Conduta</strong> <span
                                                            class="red normal"></span></label>
                                                    <textarea rows="10" class="form-control" x-model="inputsFormulario.conduta" id="conteudo_conduta" name="conduta"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-footer" style="vertical-align: middle">
                                        <input type="submit" class="btn btn-success" value="Salvar"
                                            x-bind:disabled="loadingSubmitFormulario" />
                                        <input type="reset" class="btn btn-default" value="Limpar"
                                            x-on:click="clearFormulario" />
    
                                        <template x-if="loadingSubmitFormulario">
                                            <div style="display: inline-block; margin-left: 10px">
                                                <x-loading message="Salvando formulario..." />
                                            </div>
                                        </template>
                                    </div>
    
                                </form>
                                </br>
                                </br>

                                <div class="table-responsive">
                                    <table class="display table dataTable table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nome do Formulario</th>
                                                <th>Tipo</th>
                                                <th class="text-center">Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
  
                                            <template x-for="formulario, indice in formularios">
                                                <tr>
                                                    <template x-if="indiceFormularioDelete == indice">
                                                        <td >
                                                            <x-loading message="Excluindo formulario..." />
                                                        </td>
                                                    </template>

                                                    <template x-if="indiceFormularioDelete != indice">
                                                        <td x-text="formulario.nm_formulario"></td>
                                                    </template>

                                                    <td x-text="tiposFormulario[formulario.tp_formulario]"></td> 

                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <button class="btn btn-success"
                                                                x-on:click="setEditFormulario(formulario)">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-danger"
                                                                x-on:click="deleteFormulario(formulario.cd_formulario, indice)"
                                                                x-bind:disabled="indiceFormularioDelete == indice">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>

                                            <template x-if="formularios.length == 0">
                                                <tr>
                                                    <td colspan="4" class="text-center">Nenhum formulario
                                                    </td>
                                                </tr>
                                            </template>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
 

                            <div role="tabpanel" class="tab-pane fade " id="tabConfiguracao" x-data="appConfiguracao">
                                <form action="{{ route('perfil-profi.config') }}" enctype="multipart/form-data"
                                    method="post">
                                    @csrf

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Anamnese</h3>
                                        </div>
                                        <div class="panel-body">

                                            <div class="row" style="margin-bottom: 10px; margin-top: 15px;">

                                                <div class="col-md-2 col-sm-4 col-xs-12 col-md-offset-1 ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_historia_pregressa" value="S"
                                                                        @if (old('sn_historia_pregressa', Auth::user()->sn_historia_pregressa) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Campo<br>[ História Pregressa ]

                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-sm-4 col-xs-12 ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_anamnese" value="S"
                                                                        @if (old('sn_anamnese', Auth::user()->sn_anamnese) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Campo<br>[ Anamnese ]

                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-sm-4 col-xs-12 ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_exame_fisico" value="S"
                                                                        @if (old('sn_exame_fisico', Auth::user()->sn_exame_fisico) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Campo<br>[ Exame Físico ]

                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-sm-4 col-xs-12 ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_hipotese_diag" value="S"
                                                                        @if (old('sn_hipotese_diag', Auth::user()->sn_hipotese_diag) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Campo<br>[ Hipótese Diagnóstica ]

                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-sm-4 col-xs-12 ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_conduta" value="S"
                                                                        @if (old('sn_conduta', Auth::user()->sn_conduta) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Campo<br>[ Conduta ]

                                                        </label>
                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Impresso</h3>
                                        </div>
                                        <div class="panel-body">

                                            <div class="row" style="margin-bottom: 10px; margin-top: 15px;">

                                                <div class="col-md-4 col-sm-8 col-xs-12 ">
                                                    <div class="form-group  ">
                                                        <label>Nome do Profissional: <span class="red normal"></span></label>
                                                        <input type="text" class="form-control "
                                                            value="{{ old('nm_header_doc', Auth::user()->nm_header_doc) }}"
                                                            name="nm_header_doc" maxlength="200" aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-sm-8 col-xs-12 ">
                                                    <div class="form-group  ">
                                                        <label>Especialidade(s) do Profissional: <span
                                                                class="red normal"></span></label>
                                                        <input type="text" class="form-control "
                                                            value="{{ old('espec_header_doc', Auth::user()->espec_header_doc) }}"
                                                            name="espec_header_doc" maxlength="200" aria-required="true">
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-sm-8 col-xs-12 ">
                                                    <div class="form-group  ">
                                                        <label>Conselho do Profissional: <span
                                                                class="red normal"></span></label>
                                                        <input type="text" class="form-control "
                                                            value="{{ old('conselho_header_doc', Auth::user()->conselho_header_doc) }}"
                                                            name="conselho_header_doc" maxlength="200" aria-required="true">
                                                    </div>
                                                </div>


                                                <div class="col-md-2 col-sm-4 col-xs-12 col-md-offset-1 ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_data_header_doc" value="S"
                                                                        @if (old('sn_data_header_doc', Auth::user()->sn_data_header_doc) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Data

                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-sm-4 col-xs-12  ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_header_doc" value="S"
                                                                        @if (old('sn_header_doc', Auth::user()->sn_header_doc) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Dados do Paciente

                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-sm-4 col-xs-12  ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_logo_header_doc" value="S"
                                                                        @if (old('sn_logo_header_doc', Auth::user()->sn_logo_header_doc) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Logo

                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 col-sm-4 col-xs-12  ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_footer_header_doc" value="S"
                                                                        @if (old('sn_footer_header_doc', Auth::user()->sn_footer_header_doc) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Rodapé

                                                        </label>
                                                    </div>
                                                </div>


                                                <div class="col-md-2 col-sm-4 col-xs-12  ">
                                                    <div class="form-group">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox"
                                                                        name="sn_assina_header_doc" value="S"
                                                                        @if (old('sn_assina_header_doc', Auth::user()->sn_assina_header_doc) == 'S') checked @endif
                                                                        class="flat-red"></span>
                                                            </div>Ocultar Assinatura

                                                        </label>
                                                    </div>
                                                </div>

                                            </div>


                                        </div>
                                    </div>

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Assinatura</h3>
                                        </div>
                                        <div class="panel-body">

                                            <div class="row" style="margin-bottom: 10px; margin-top: 15px;">

                                                <div class="col-md-6 col-sm-12 col-xs-12 ">
                                                    <div class="form-group  ">
                                                        <label>Nome do Profissional: <span class="red normal"></span></label>
                                                        <input type="file" class="form-control " name="assinatura">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom: 10px; margin-top: 15px;">

                                                <div class="col-md-6 col-sm-12 col-xs-12 ">
                                                    @if ($profissional->assinatura)
                                                        <div class="modal-header">
                                                            <button type="button" class="close"
                                                                x-on:click="deleteAssinatura()"><span
                                                                    aria-hidden="true">×</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Assinatura atual</h4>
                                                        </div>
                                                        <img src = "data:{{ $profissional->tp_assinatura }};base64,{{ $profissional->assinatura }}"
                                                            width="100%" id="img_assinatura" />
                                                    @endif
                                                </div>

                                            </div>

                                        </div>
                                    </div>


                                    <div class="box-footer">
                                        <input type="submit" class="btn btn-success" value="Salvar" />
                                        <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                                    </div>

                                </form>
                            </div>

                            
                            <div role="tabpanel" class="tab-pane fade " id="tabCertificado" x-data="appCertificado">

                                @if (!isset($certificado))
                                    <form method="post" id="formCertificado"
                                        action="{{ route('perfil-profi.certificado') }}" enctype="multipart/form-data">
                                        <input type="hidden" name="tab" value="certificado">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group  ">
                                                    <label>Importar Certificado: <span class="red normal">*</span></label>
                                                    <input type="file" class="form-control " name="certificado"
                                                        accept=".pfx">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group  ">
                                                    <label>Senha do Certificado: <span class="red normal">*</span></label>
                                                    <input type="password" class="form-control " name="senha"
                                                        maxlength="200" aria-required="true">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="box-footer">
                                            <input type="submit" class="btn btn-success" value="Salvar" />
                                            <input type="reset" class="btn btn-default" value="Limpar" />
                                        </div>

                                    </form>
                                @else
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group  ">
                                                <label>Razão Social: <span class="red normal">*</span></label>
                                                <input type="text" class="form-control " readonly
                                                    value="{{ $certificado->pfx_razao }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group  ">
                                                <label>Nome: <span class="red normal">*</span></label>
                                                <input type="text" class="form-control " readonly
                                                    value="{{ $certificado->pfx_nome }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group  ">
                                                <label>CPF: <span class="red normal">*</span></label>
                                                <input type="text" class="form-control " readonly
                                                    value="{{ $certificado->pfx_cpf }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group  ">
                                                <label>Validade: <span class="red normal">*</span></label>
                                                <input type="text" class="form-control " readonly
                                                    value="{{ date('d/m/Y', strtotime($certificado->pfx_validade)) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group  ">
                                                <label>Hash: <span class="red normal">*</span></label>
                                                <input type="text" class="form-control " readonly
                                                    value="{{ $certificado->pfx_hash }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group  ">
                                                <label>Situação: <span class="red normal">*</span></label>
                                                <input type="text" class="form-control " style="font-weight: bold;"
                                                    readonly value="{{ $certificado->pfx_situacao }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group  ">
                                                <label>Emissor: <span class="red normal">*</span></label>
                                                <input type="text" class="form-control " readonly
                                                    value="{{ mb_strtoupper($certificado->pfx_emissor) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group  ">
                                                <label>Email: <span class="red normal">*</span></label>
                                                <input type="text" class="form-control " readonly
                                                    value="{{ $certificado->pfx_email }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div style="text-align: right">
                                        <button type="button" class="btn btn-default btn-rounded" style="color: red"
                                            x-on:click="deleteCertificado">
                                            <i class="fa fa-trash"></i> Exluir Certificado
                                        </button>
                                    </div>
                                @endif

                            </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        const formularios = @js($formularios);
        const procedimentosProfissional = @js($procedimentosProfissional);
        const especialidadesProfissional = @js($especialidadesProfissional);
        const documentosPadrao = @js($documentosPadrao);
        const cdProfissional = @js($profissional->cd_profissional);
    </script>
    <script src="{{ asset('js/rpclinica/perfil-profissional_atual.js') }}"></script>
@endsection
