@extends('rpclinica.layout.layout')

@section('content')
    <div id="main-wrapper">

        <style>
            ul>.active {
                border-top: 3px solid #8CDDCD;
            }

            ul>.active>a>i {
                color: #8CDDCD;
                margin-right: 10px;
                font-size: 15px;
            }

            .fa {
                margin-right: 7px;
            }

            .form-horizontal .control-label {
                padding-top: 0px;
                margin-bottom: 0;
            }

            .swal2-styled.swal2-confirm {
                border-radius: 0;
                font-size: 1.1em;
            }

            .swal2-styled.swal2-cancel {
                border-radius: 0;
                font-size: 1.1em;
            }

            .customClassName {
                z-index: 2147483647 !important; //max possible value for zindex
            }

 
        </style>



        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-white">


                    <div class="panel panel-white" style="margin-bottom: 10px;">
                        <div class="panel-body" style="padding: 5px;">

                            <div class="row" style="margin-top: 5px; padding-bottom: 5px; margin-left: 3px;">
                                <div class="col-sm-5 col-md-5 col-xs-12">
                                    <table>
                                        <tr>
                                            <td>
                                                <img class="img-circle avatar" id="fotodopaciente " data-toggle="modal"
                                                    data-target=".modal-info-pac"
                                                    src="{{ $agendamento->paciente->foto_url ? $agendamento->paciente->foto_url : asset('assets/images/avatarPaciente.png') }}"
                                                    width="60" height="60" style="margin-right: 8px;" alt="">
                                            </td>
                                            <td style="line-height: 1;">
                                                <div>
                                                    <b>
                                                        @if ($agendamento->paciente->vip == 'S')
                                                            <i class="fa fa-star" style="color: #f8ac59;   "></i>
                                                        @else
                                                            <i class="fa fa-star-o" style="    "></i>
                                                        @endif
                                                        {{ mb_convert_case($agendamento->paciente->nm_paciente, MB_CASE_TITLE, 'UTF-8') }}
                                                    </b>
                                                    <br>
                                                    <span aria-hidden="true" class="icon-calendar"
                                                        style="margin-right: 5px;"></span>
                                                    @php
                                                        $nasc = $agendamento->paciente->dt_nasc
                                                            ? date('d/m/Y', strtotime($agendamento->paciente->dt_nasc))
                                                            : ' Não Informado ';
                                                        $idade = $agendamento->paciente->dt_nasc
                                                            ? idadeAluno($agendamento->paciente->dt_nasc)
                                                            : ' -- ';
                                                    @endphp
                                                    {{ $nasc . ' [ ' . $idade . ' ] ' }}
                                                    <br>
                                                    <b>Mãe:
                                                    </b>{{ mb_convert_case($agendamento->paciente->nm_mae ? $agendamento->paciente->nm_mae : ' -- ', MB_CASE_TITLE, 'UTF-8') }}
                                                    <br>
                                                    <b>Pai:
                                                    </b>{{ mb_convert_case($agendamento->paciente->nm_pai ? $agendamento->paciente->nm_pai : ' -- ', MB_CASE_TITLE, 'UTF-8') }}
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-sm-4 col-md-4 col-xs-12" style="line-height: 1;">
                                    <b> Atendimento:</b>
                                    {{ mb_convert_case($agendamento->cd_agendamento, MB_CASE_TITLE, 'UTF-8') }}
                                    <br>
                                    <b> Data:</b>
                                    {{ mb_convert_case(formatDate($agendamento->dt_agenda), MB_CASE_TITLE, 'UTF-8') }}
                                    <br>
                                    <b> Profissional:</b>
                                    {{ mb_convert_case($agendamento->profissional->nm_profissional, MB_CASE_TITLE, 'UTF-8') }}
                                    <br>
                                    <b>Agenda:</b>
                                    {{ mb_convert_case($agendamento->agenda?->nm_agenda, MB_CASE_TITLE, 'UTF-8') }}
                                </div>

                                <div class="col-sm-3 col-md-3 col-xs-12" style="line-height: 1;">
                                    <b>Guias:</b> --
                                    <br>
                                    <b>Situação:</b>
                                    {{ mb_convert_case($agendamento->tab_situacao?->nm_situacao, MB_CASE_TITLE, 'UTF-8') }}
                                    <br>
                                    <b>Convênio:</b>
                                    {{ mb_convert_case($agendamento->convenio?->nm_convenio, MB_CASE_TITLE, 'UTF-8') }}
                                    <br>
                                    <b>Cartão:</b>
                                    {{ mb_convert_case($agendamento->cartao ? $agendamento->cartao : '', MB_CASE_TITLE, 'UTF-8') }}
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="panel-body" style="padding-top: 0px;">

                        <!-- Nav tabs OFTALMO -->
                        @if ($tabelas['tp_prontuario'] == 'oftalmo')
                            <div role="tabpanel">
                                <ul class="nav nav-tabs nav-justified no-print" role="tablist">

                                    <li role="presentation" class="active">
                                        <a href="#tabPaciente" role="tab" data-toggle="tab"> <i class="fa fa-user"></i>
                                            Dados do Paciente</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#tabOftalmologia" role="tab" data-toggle="tab"><i
                                                class="fa fa-folder-open"></i>
                                            Prontuario Eletrônico</a>
                                    </li>
                                </ul>
                                <div class="tab-content">

                                    <div role="tabpanel" class="tab-pane fade in active" id="tabPaciente">
                                        @include('rpclinica.consultorio.paciente')
                                    </div>

                                    <div role="tabpanel" class="tab-pane fade " id="tabOftalmologia">
                                        <div class="row" x-data="appOftalmologia">
                                            <div class="col-md-3 col-sm-3 col-lg-3 col-xs-12  ">

                                                <div class="  panel-white ui-sortable-handle">
                                                    <div class="panel-heading">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3 class="panel-title">Menu</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @include('rpclinica.consultorio.sub_menu.sub_menu', [
                                                    'formularios' => $formulario,
                                                    'menu' => $menu,
                                                ])

                                            </div>

                                            @include('rpclinica.consultorio.telas', [
                                                'agendamento' => $agendamento,
                                                'formularios' => $formulario,
                                                'tabelas' => $tabelas,
                                            ])
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- FIM Nav tabs OFTALMO -->
                        @endif


                        @if ($tabelas['tp_prontuario'] == 'consultorio')
                            <!-- Nav tabs CONSULTORIO -->
                            <div role="tabpanel" x-data="appConsultorioGeral">
                                <!-- Nav tabs -->
                                <div style="width: 100%; text-align: right">
                                    <i class="fa fa-gears" style="color: #a7a7a7; cursor: pointer;" data-toggle="modal"
                                        data-target=".bs-example-modal-lg"></i>
                                </div>

                                <!-- Modal CONFIGURAÇÃO -->
                                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog"
                                    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myLargeModalLabel">Configuração do Profissional
                                                </h4>
                                            </div>
                                            <div class="modal-body">

                                                <br> 
                                                <div role="tabpanel">
                                                    <!-- Nav tabs -->
                                                    <ul class="nav nav-tabs nav-justified" role="tablist">
                                                        <li role="presentation" class="active"><a href="#tabConfiguracao" role="tab" data-toggle="tab">Configurações Gerais</a></li>
                                                        <li role="presentation"><a href="#tabCertificado" role="tab" data-toggle="tab">Certificado Digital</a></li> 
                                                    </ul>
                                                    <!-- Tab panes -->
                                                    <div class="tab-content">

                                                        <div role="tabpanel" class="tab-pane active fade in" id="tabConfiguracao">

                                                            <br> 
                                                            <form enctype="multipart/form-data" x-on:submit.prevent="storeConfigPerfil" id="form_CONFIG_PERFIL">

                                                                @csrf
                                                                <input type="hidden" name="tp_form" value="prontuario">
            
                                                                <div class="panel panel-white">
                                                                    <div class="panel-heading">
                                                                        <h3 class="panel-title">Anamnese</h3>
                                                                    </div>
                                                                    <div class="panel-body">
            
                                                                        <div class="row"
                                                                            style="margin-bottom: 10px; margin-top: 15px;">
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12 col-md-offset-1 ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker"
                                                                                            id="sn_historia_pregressa">
                                                                                            <span><input type="checkbox"
                                                                                                    name="sn_historia_pregressa"
                                                                                                    value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Campo<br>[ História Pregressa ]
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12 ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker" id="sn_anamnese">
                                                                                            <span><input type="checkbox"
                                                                                                    name="sn_anamnese" value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Campo<br>[ Anamnese ]
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12 ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker" id="sn_exame_fisico">
                                                                                            <span><input type="checkbox"
                                                                                                    name="sn_exame_fisico"
                                                                                                    value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Campo<br>[ Exame Físico ]
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12 ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker" id="sn_hipotese_diag">
                                                                                            <span><input type="checkbox"
                                                                                                    name="sn_hipotese_diag"
                                                                                                    value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Campo<br>[ Hipótese Diagnóstica ]
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12 ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker" id="sn_conduta">
                                                                                            <span><input type="checkbox"
                                                                                                    name="sn_conduta" value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Campo<br>[ Conduta ]
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
            
                                                                        </div>

                                                                        <div class="row" style="margin-bottom: 10px; margin-top: 15px;">
        
                                                                            <div class="col-md-4 col-sm-8 col-xs-12 ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker"
                                                                                            id="carregar_historia_pregressa">
                                                                                            <span><input type="checkbox"
                                                                                                    name="carregar_historia_pregressa"
                                                                                                    value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Carregar "História Pregressa"
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
            
                                                                        <div class="row"   style="margin-bottom: 10px; margin-top: 15px;">
            
                                                                            <div class="col-md-3 col-sm-8 col-xs-12 ">
                                                                                <div class="form-group  ">
                                                                                    <label>Nome do Profissional: <span
                                                                                            class="red normal"></span></label>
                                                                                    <input type="text" class="form-control "
                                                                                        x-model="ConfigPerfil.nm_header_doc"
                                                                                        name="nm_header_doc">
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-4 col-sm-8 col-xs-12 ">
                                                                                <div class="form-group  ">
                                                                                    <label>Especialidade(s) do Profissional: <span
                                                                                            class="red normal"></span></label>
                                                                                    <input type="text" class="form-control "
                                                                                        x-model="(ConfigPerfil?.espec_header_doc) ? ConfigPerfil?.espec_header_doc : '' "
                                                                                        name="espec_header_doc" maxlength="200"
                                                                                        aria-required="true">
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-3 col-sm-8 col-xs-12 ">
                                                                                <div class="form-group  ">
                                                                                    <label>Conselho do Profissional: <span
                                                                                            class="red normal"></span></label>
                                                                                    <input type="text" class="form-control "
                                                                                        x-model="(ConfigPerfil.conselho_header_doc) ? ConfigPerfil.conselho_header_doc : '' "
                                                                                        name="conselho_header_doc" maxlength="200"
                                                                                        aria-required="true">
                                                                                </div>
                                                                            </div>
            
                                                                        </div>
                                                                        <div class="row" >
                                                                            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12   ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker" id="sn_data_header_doc">
                                                                                            <span><input type="checkbox"
                                                                                                    name="sn_data_header_doc"
                                                                                                    value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Data
            
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12  ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker" id="sn_header_doc">
                                                                                            <span><input type="checkbox"
                                                                                                    name="sn_header_doc"
                                                                                                    value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Dados do Paciente
            
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12  ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker" id="sn_logo_header_doc">
                                                                                            <span><input type="checkbox"
                                                                                                    name="sn_logo_header_doc"
                                                                                                    value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Logo
            
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12  ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker"
                                                                                            id="sn_footer_header_doc"><span><input
                                                                                                    type="checkbox"
                                                                                                    name="sn_footer_header_doc"
                                                                                                    value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Rodapé
            
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12  ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker"
                                                                                            id="sn_titulo_header_doc"><span><input
                                                                                                    type="checkbox"
                                                                                                    name="sn_titulo_header_doc"
                                                                                                    value="S"
                                                                                                    class="flat-red"></span>
                                                                                        </div>Ocultar Titulo
            
                                                                                    </label>
                                                                                </div>
                                                                            </div>
            
            
                                                                            <div class="col-md-2 col-sm-4 col-xs-12  ">
                                                                                <div class="form-group">
                                                                                    <label>
                                                                                        <div class="checker"
                                                                                            id="sn_assina_header_doc"><span><input
                                                                                                    type="checkbox"
                                                                                                    name="sn_assina_header_doc"
                                                                                                    value="S"
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
            
                                                                        <div class="row"
                                                                            style="margin-bottom: 10px; margin-top: 15px;">
            
                                                                            <div class="col-md-6 col-sm-12 col-xs-12 ">
                                                                                <div class="form-group  ">
                                                                                    <label>Nome do Profissional: <span
                                                                                            class="red normal"></span></label>
                                                                                    <input type="file" class="form-control "
                                                                                        name="assinatura">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row"
                                                                            style="margin-bottom: 10px; margin-top: 15px;">
                                                                            <template x-if="ConfigPerfil.profissional?.tp_assinatura">
                                                                                <div class="col-md-6 col-sm-12 col-xs-12 ">
            
                                                                                    <div class="modal-header">
            
                                                                                        <h4 class="modal-title" id="myModalLabel">
                                                                                            Assinatura atual</h4>
                                                                                    </div>
                                                                                    <img x-bind:src="'data:' + ConfigPerfil.profissional
                                                                                        ?.tp_assinatura + ';base64,' + ConfigPerfil
                                                                                        .profissional?.assinatura"
                                                                                        style="max-height: 100px;"
                                                                                        id="img_assinatura" />
            
                                                                                </div>
                                                                            </template>
            
                                                                        </div>
            
                                                                    </div>
                                                                </div>
             
                                                                <div class="box-footer">
                                                                    <button type="submit" class="btn btn-success"
                                                                        x-html="buttonSalvar" x-bind:disabled="buttonDisabled">
                                                                    </button>
                                                                </div>
            
                                                            </form>
                                                               
                                                        </div>
                                                        <div role="tabpanel" class="tab-pane fade" id="tabCertificado">
                                                            
                                                            <template x-if="(!loadingCertificado)">
                                                       
                                                                <form method="post" id="formCertificado" x-on:submit.prevent="storeCertificado"
                                                                    action="{{ route('perfil-profi.certificado') }}" enctype="multipart/form-data">
                                                                    <input type="hidden" name="tab" value="certificado">
                                                                    <input type="hidden" name="pagina" value="prontuario">
                                                                    @csrf
                                                                    <div class="row">
                                                                        <div class="col-md-7">
                                                                            <div class="form-group  ">
                                                                                <label>Importar Certificado: <span class="red normal">*</span></label>
                                                                                <input type="file" class="form-control " required name="certificado"
                                                                                    accept=".pfx">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group  ">
                                                                                <label>Senha do Certificado: <span class="red normal">*</span></label>
                                                                                <input type="password" class="form-control " required name="senha"
                                                                                    maxlength="200" aria-required="true">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <button type="submit" style="width: 100%; margin-top: 18px;" class="btn btn-success"
                                                                                    x-html="buttonUpload" x-bind:disabled="buttonDisabled" > 
                                                                            </button> 
                                                                        </div>
    
                                                                    </div>
                            
                                                                </form>
                                                            </template>

                                                            <template x-if="(loadingCertificado)">
                                                                <div> 
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="form-group  ">
                                                                                <label>Razão Social: <span class="red normal">*</span></label>
                                                                                <input type="text" class="form-control " readonly x-bind:value="dadosCertificado.pfx_razao" >
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="form-group  ">
                                                                                <label>Nome: <span class="red normal">*</span></label>
                                                                                <input type="text" class="form-control " readonly x-bind:value="dadosCertificado.pfx_nome" > 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-3">
                                                                            <div class="form-group  ">
                                                                                <label>CPF: <span class="red normal">*</span></label>
                                                                                <input type="text" class="form-control " readonly x-bind:value="dadosCertificado.pfx_cpf" >  
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group  ">
                                                                                <label>Validade: <span class="red normal">*</span></label>
                                                                                <input type="text" class="form-control " readonly x-bind:value="dadosCertificado.pfx_validade" >   
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group  ">
                                                                                <label>Hash: <span class="red normal">*</span></label>
                                                                                <input type="text" class="form-control " readonly x-bind:value="dadosCertificado.pfx_hash" >  
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="form-group  ">
                                                                                <label>Situação: <span class="red normal">*</span></label>
                                                                                <input type="text" class="form-control " style="font-weight: bold;"
                                                                                    readonly x-bind:value="dadosCertificado.pfx_situacao" > 
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-7">
                                                                            <div class="form-group  ">
                                                                                <label>Emissor: <span class="red normal">*</span></label>
                                                                                <input type="text" class="form-control " readonly x-bind:value="dadosCertificado.pfx_emissor" >  
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-5">
                                                                            <div class="form-group  ">
                                                                                <label>Email: <span class="red normal">*</span></label>
                                                                                <input type="text" class="form-control " readonly x-bind:value="dadosCertificado.pfx_email" >  
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div style="text-align: right">
                                                                        <button type="button" class="btn btn-default btn-rounded" style="color: red"
                                                                            x-on:click="deleteCertificado" x-html="buttonDelete" x-bind:disabled="buttonDisabled" > 
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            </template>

                                                        </div>
                                                        
                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Fim MODAL -->

 
                                <!-- Modal PERFIL CONFIGURAÇÃO -->
                                <div class="modal fade modaltextoPadrao" tabindex="-1" role="dialog"
                                    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><span aria-hidden="true">×</span></button>
                                                <h4 class="modal-title" id="myLargeModalLabel"
                                                    x-html="header_padrao_titulo"></h4>
                                            </div>

                                            <div class="modal-body">

                                                <form   x-on:submit.prevent="storeTextoPadrao" method="POST" id="form_TEXTO_PADRAO">
                                                    @csrf
                                                    <div class="row">

                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label class="mat-label"><strong>Titulo</strong> <span
                                                                        class="red normal"> * </span></label>
                                                                <input type="text" class="form-control  "
                                                                    style="height: 30px;" name="titulo" maxlength="100"
                                                                    aria-required="true"
                                                                    x-model="form_texto_padrao.titulo">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">

                                                            <div role="tabpanel">
                                                                <!-- Nav tabs -->
                                                                <ul class="nav nav-tabs" role="tablist">
                                                                    <li role="presentation" class="active">
                                                                        <a href="#tabConteudoDoc" style="margin-right: 0px;border-bottom: 0px;" role="tab" data-toggle="tab">Documento</a>
                                                                    </li>
                                                                    <li role="presentation">
                                                                        <a href="#tabCamposInteligentes" style="margin-right: 0px;border-bottom: 0px;" role="tab" data-toggle="tab">Campos Inteligentes</a>
                                                                    </li>
                                                                </ul>
                                                                <!-- Tab panes -->
                                                                <div class="tab-content">
                                                                    <div role="tabpanel" class="tab-pane active" id="tabConteudoDoc">
                                                                         
                                                                        <div class="form-group">
                                                                            <label for="input-placeholder" class="control-label"
                                                                                style="padding-top: 0px; "><strong>Conteudo</strong>
                                                                                <span class="red normal"> * </span></label>
                                                                            <textarea rows="10" class="form-control" name="conteudo" id="editor-modelo-documento" required></textarea>
                                                                            <input type="hidden" name="codigo" x-model="form_texto_padrao.codigo">
                                                                        </div>

                                                                        <div class="row ">
                                                                            <div class="col-md-3"></div> 
                                                                            <div class="col-md-6" style="text-align: center; margin-top: 10px;">
                                                                                <button type="submit" class="btn btn-success"
                                                                                    x-html="buttonSalvar" x-bind:disabled="buttonDisabled">
                                                                                </button>
                                                                            </div>
                                                                            <div class="col-md-3" style="text-align: right; margin-top: 10px;">
                                                                                <button type="button" class="btn btn-default" x-on:click="clearTextoPadrao" ><span aria-hidden="true" class="icon-doc"></span>
                                                                                </button>
                                                                            </div> 
                                                                        </div>

                                                                    </div>
                                                                    <div role="tabpanel" class="tab-pane" id="tabCamposInteligentes">
                                                                        
                                                                        <div class="row"> 
                                                                            <div class="col-md-6">
                                                                                <table class="table  ">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th width="100%" colspan="2" class="text-center"><span aria-hidden="true" style="color: #22baa0;margin-right: 10px;font-size: 15px;" class="icon-users"></span> PACIENTE
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
                                                                                    </tbody>
                                                                                    
                                                                                </table>
                                                                            </div>

                                                                            <div class="col-md-6">

                                                                                <table class="table  ">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th width="100%" colspan="2" class="text-center"> <span aria-hidden="true" style="color: #22baa0;margin-right: 10px;font-size: 15px;" class="icon-users"></span> PACIENTE
                                                                                            </th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody> 

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
                                                                                                <span aria-hidden="true" style="color: #22baa0;margin-right: 10px;font-size: 15px;" class="icon-user"></span> PROFISSIONAL</th>
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
                                                                                                <span aria-hidden="true" style="color: #22baa0;margin-right: 10px;font-size: 15px;" class="icon-note"></span>  ATENDIMENTO</th>
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
                                                            </div>


                                                        </div>
                                                    </div>


                                                </form>
                                                <br>
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr class="active">
                                                            <th>Codigo</th>
                                                            <th>Formulario</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr x-show="loadingDoc">
                                                            <td colspan="3">
                                                                <div class="line">
                                                                    <div class="loading"></div>
                                                                    <span>Carregando Formularios...</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <template x-for="val in texto_padrao">
                                                            <tr>
                                                                <td x-html="val.cd_formulario"></td>
                                                                <td x-html="val.nm_formulario">Formulario</td>
                                                                <td class="text-center">
                                                                    <div class="btn-group btn-xs">
                                                                        <button type="button"
                                                                            class="btn btn-default btn-addon m-b-sm btn-rounded btn-xs dropdown-toggle"
                                                                            data-toggle="dropdown" aria-expanded="false"
                                                                            style="margin-bottom: 0px; font-size: 1.3rem !important; color: #7a6fbe; font-weight: 600;">
                                                                            &nbsp;Opções &nbsp; <span
                                                                                class="caret"></span>
                                                                            &nbsp;
                                                                        </button>
                                                                        <ul class="dropdown-menu" role="menu">

                                                                            <li>
                                                                                <a href="#"
                                                                                    x-on:click="editTextoPadrao(val)"
                                                                                    style="color: #7a6fbe; font-weight: 600;">
                                                                                    <i class="fa fa-edit"
                                                                                        style="margin-left:4px; margin-right: 4px;  "></i>
                                                                                    &nbsp;Editar
                                                                                </a>
                                                                            </li>


                                                                            <li>
                                                                                <a href="#"
                                                                                    x-on:click="deleteTextoPadrao(val)"
                                                                                    style="color: #f25656; font-weight: 600;">
                                                                                    <i class="fa fa-trash"
                                                                                        style="margin-left:4px; margin-right: 4px; "></i>
                                                                                    &nbsp;Excluir
                                                                                </a>
                                                                            </li>

                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Fim MODAL PERFIL CONFIGURAÇÃO -->


                                <ul class="nav nav-tabs nav-justified no-print" role="tablist">

                                    <li role="presentation" class="active">
                                        <a href="#tabPaciente" role="tab" data-toggle="tab"> <i
                                                class="fa fa-user"></i>
                                            Dados do Paciente</a>
                                    </li>

                                    <li role="presentation">
                                        <a href="#tabAnamnese" role="tab" data-toggle="tab"><i
                                                class="fa fa-stethoscope"></i>
                                            Anamnese
                                        </a>
                                    </li>

                                    <li role="presentation">
                                        <a href="#tabDocumentos" class="" role="tab" data-toggle="tab">
                                            <i class="fa fa-file-text"></i>
                                            Documentos
                                            <span style="margin-left: 10px; height: 16px;" x-show="(nrDocumentos > 0)"
                                                x-text="nrDocumentos" class="badge badge-success"> </span>

                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#tabAnotacoes" role="tab" data-toggle="tab">
                                            <i class="fa fa-clipboard"></i>
                                            Anotações 
                                            <span style="margin-left: 10px; height: 16px;" x-show="(nrAnotacao > 0)"
                                                x-text="nrAnotacao" class="badge badge-success"> </span>

                                        </a>
                                    </li>

                                    <li role="presentation">
                                        <a href="#tabHistoricos" role="tab" data-toggle="tab">
                                            <i class="fa fa-files-o"></i>
                                            Historicos
                                            <span style="margin-left: 10px; height: 16px;" x-show="(nrHistory > 0)"
                                                x-text="nrHistory" class="badge badge-success"> </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" aria-expanded="false" x-on:click="encerrarConsulta"
                                            class="btn btn-default" style=" fill: #22baa0; color: #22baa0;"
                                            data-placement="top" data-toggle="tooltip" title=""
                                            data-original-title="Finalizar Atendimento">
                                            <i class="fa fa-check-square-o" style="margin-left: 10px;"></i>
                                            <b>Finalizar</b>
                                        </a>
                                    </li>


                                </ul>

                                <div class="tab-content">


                                    <div role="tabpanel" class="tab-pane fade in active" id="tabPaciente">
                                        @include('rpclinica.consultorio.paciente')
                                    </div>


                                    <div role="tabpanel" class="tab-pane fade " id="tabAnamnese">
                                        @include(
                                            'rpclinica.consultorio.formularios.consultorio.anamnese.formulario',
                                            ['agendamento' => $agendamento]
                                        )
                                    </div>

                                    <div role="tabpanel" class="tab-pane fade " id="tabDocumentos">
                                        @include(
                                            'rpclinica.consultorio.formularios.consultorio.documentos.formulario',
                                            ['agendamento' => $agendamento]
                                        )
                                    </div>

                                    <div role="tabpanel" class="tab-pane fade " id="tabAnotacoes">
                                        @include(
                                            'rpclinica.consultorio.formularios.consultorio.anotacoes.formulario',
                                            ['agendamento' => $agendamento]
                                        )
                                    </div>

                                    <div role="tabpanel" class="tab-pane fade " id="tabHistoricos">
                                        @include(
                                            'rpclinica.consultorio.formularios.consultorio.historico.formulario',
                                            ['agendamento' => $agendamento]
                                        )
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            @if(isset($tabelas['links']))
                                            <h2 style="font-size: 18px;  margin-top: 10px;">Links Uteis</h2>
                                                <line> 
                                                    @foreach($tabelas['links'] as $link)
                                                        <a href="{{ $link['url_link'] }}" target="_blanck" style="font-size: 11px; ont-weight: 600; margin-left: 10px; background: #48c9b3;" class="label label-success">
                                                            <i class="fa fa-external-link" style="margin-right: 1px;"></i> {{ $link['nm_link'] }}
                                                        </a> 
                                                    @endforeach

                                                </line>
                                            @endif
                                        </div>
                                    </div>

                                </div>




                            </div>
                            <!-- FIM Nav tabs CONSULTORIO -->
                        @endif

                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        const idAgendamento = @js($agendamento->cd_agendamento);
        const dadosAgendamento = @js($agendamento->cd_agendamento);
        const viewHistoriaPregressa = @js(Auth::user()->sn_historia_pregressa);
        const viewAnamnese = @js(Auth::user()->sn_anamnese);
        const viewExameFisico = @js(Auth::user()->sn_exame_fisico);
        const viewHipoteseDiag = @js(Auth::user()->sn_hipotese_diag);
        const viewConduta= @js(Auth::user()->sn_conduta);
        const idFormulario = 'inicial';
    </script>

    {{--
    <script src="{{ asset('/js/rpclinica/consultorio-anamnese.js') }}"></script>
    <script src="{{ asset('/js/rpclinica/consultorio-paciente.js') }}"></script>
    <script src="{{ asset('/js/rpclinica/consultorio-documento.js') }}"></script>
    <script src="{{ asset('/js/rpclinica/consultorio-receita.js') }}"></script>
    --}}

    <script src="{{ asset('/js/rpclinica/consultorio-paciente.js') }}"></script>

    @if($tabelas['tp_prontuario'] == 'consultorio')
        <script src="{{ asset('/js/rpclinica/consultorio-geral_novo.js') }}"></script>
    @endif

    @if($tabelas['tp_prontuario'] == 'oftalmo')
        <script src="{{ asset('/js/rpclinica/consultorio-oftalmologia.js') }}"></script>
    @endif

    <script src="{{ asset('/assets/plugins/jstree/jstree.min.js') }}"></script>
    <script src="{{ asset('/assets/js/pages/jstree.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
            $('select').select2();

        })
    </script>
@endsection
