@extends('rpclinica.layout.layout')

@section('content')

<style>
    .server-load>.server-stat {
        display: inline-block;
        margin-right: 20px;
        margin-bottom: 10px;
    }

    .h1,
    .h2,
    .h3,
    h1,
    h2,
    h3 {
        margin-top: 0px;
        margin-bottom: 0px;
    }

    .list-paciente-info {
        margin-bottom: 3px;
    }

    /* Glassmorphism - Page Title Spacing */
    .page-title {
        margin-top: 70px !important;
        padding-top: 20px !important;
    }

    .page-title h3 {
        color: white !important;
    }

    /* Table Dark Theme */
    .table {
        background: transparent !important;
        color: #e2e8f0 !important;
    }

    .table>thead>tr>th {
        background: rgba(45, 212, 191, 0.15) !important;
        color: #2dd4bf !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
        font-weight: 600 !important;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th {
        background: transparent !important;
        color: #cbd5e1 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    .table>tbody>tr:hover>td,
    .table>tbody>tr:hover>th {
        background: rgba(45, 212, 191, 0.08) !important;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }

    .table-striped>tbody>tr:nth-of-type(even) {
        background-color: transparent !important;
    }

    /* Panel Adjustments */
    .panel-white {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    /* Form Input */
    .form-control {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }

    .form-control::placeholder {
        color: #64748b !important;
    }

    /* Breadcrumb */
    .breadcrumb {
        background: transparent !important;
        color: #64748b !important;
    }

    .breadcrumb li,
    .breadcrumb li a {
        color: #64748b !important;
    }

    /* Buttons */
    .btn-info {
        background: rgba(45, 212, 191, 0.2) !important;
        border-color: #2dd4bf !important;
        color: #2dd4bf !important;
    }

    .btn-success {
        background: #2dd4bf !important;
        border-color: #2dd4bf !important;
    }

    /* Modal Styling */
    .modal-content {
        background: #1e293b !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }

    .modal-header {
        background: rgba(45, 212, 191, 0.1) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .modal-header .modal-title {
        color: white !important;
    }

    .modal-header .close {
        color: white !important;
        opacity: 0.8 !important;
    }

    .modal-body {
        background: #1e293b !important;
    }

    .modal-body label {
        color: #e2e8f0 !important;
    }

    .modal-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    /* Summernote Editor Styling */
    .note-editor.note-frame {
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    .note-editor .note-toolbar {
        background: #334155 !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .note-editor .note-toolbar .note-btn {
        background: transparent !important;
        color: #e2e8f0 !important;
        border-color: rgba(255, 255, 255, 0.2) !important;
    }

    .note-editor .note-toolbar .note-btn:hover {
        background: rgba(45, 212, 191, 0.2) !important;
    }

    .note-editor .note-editing-area {
        background: #0f172a !important;
    }

    .note-editor .note-editing-area .note-editable {
        background: #0f172a !important;
        color: #e2e8f0 !important;
    }

    .note-editor .note-statusbar {
        background: #334155 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    /* Select/Dropdown in Modal */
    .modal-content select.form-control {
        background: #0f172a !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    .modal-content select.form-control option {
        background: #1e293b !important;
        color: white !important;
    }

    /* Panel Footer */
    .panel-footer {
        background: rgba(45, 212, 191, 0.05) !important;
        border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    /* Tabs in Modal */
    .nav-tabs {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }

    .nav-tabs>li>a {
        color: #94a3b8 !important;
        background: transparent !important;
        border: none !important;
    }

    .nav-tabs>li.active>a,
    .nav-tabs>li.active>a:hover,
    .nav-tabs>li.active>a:focus {
        color: #2dd4bf !important;
        background: rgba(45, 212, 191, 0.1) !important;
        border: none !important;
        border-bottom: 2px solid #2dd4bf !important;
    }

    .tab-content {
        background: transparent !important;
    }

    /* Select2 Styling */
    .select2-container {
        z-index: 10060 !important;
    }

    .select2-dropdown {
        background: #1e293b !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        z-index: 10060 !important;
    }

    .select2-container--default .select2-selection--single {
        background: #0f172a !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        height: 36px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: white !important;
        line-height: 34px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px !important;
    }

    .select2-results__option {
        background: #1e293b !important;
        color: white !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background: #2dd4bf !important;
        color: #0f172a !important;
    }

    .select2-search__field {
        background: #0f172a !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    /* Fix modal z-index for dropdowns */
    .modal {
        z-index: 10050 !important;
    }

    .modal-backdrop {
        z-index: 10040 !important;
    }
</style>
<div class="page-title">
    <div class="row">
        <div class="col-md-10 ">
            <h3>Relação de Pacientes</h3>
            <div class="page-breadcrumb">
                <ol class="breadcrumb">
                    <li><a href="{{ route('paciente.listar') }}">Relação</a></li>
                </ol>
            </div>
        </div>
        <div class="col-md-2" style="text-align: right; ">
            <div class="row">
                <div class="col-md-6 col-md-offset-6" style="text-align: right;">

                    <div class="btn-group">

                        <a href="{{ route('paciente.create') }}" class="btn btn-default" data-toggle="tooltip"
                            data-placement="top" title="" data-original-title="Cadastrar Paciente">
                            <span aria-hidden="true" class="icon-note"></span>
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </div>


</div>

<div id="main-wrapper" x-data="app">
    <div class="col-md-12 ">
        <div class="panel glass-panel" style="background-color: rgba(30, 41, 59, 0.45) !important; border: 1px solid rgba(255,255,255,0.1) !important;"><br>
            <div class="panel-heading clearfix" style="padding-bottom: 4px;">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-7">
                        <h4 class="panel-title"> </h4>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-5">
                        <div style="display: flex; gap: 12px">
                            <form method="GET" id="searchList" style="width: 100%">
                                <div class="input-group m-b-sm">
                                    <input type="text" name="b" class="form-control"
                                        placeholder="Pesquisar por CPF ou Nome...">
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
            <br>


            <div class="modal fade " id="documentoPaciente" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true"><span class="glyphicon glyphicon-remove-circle"
                                        aria-hidden="true"></span></span></button>
                            <h4 class="modal-title" id="myLargeModalLabel"
                                x-html="(pacienteSelected?.nm_paciente ? pacienteSelected.nm_paciente : 'Paciente') + '<small><i> ' + (pacienteSelected?.cd_paciente ?? '') + ' </i></small>'">
                            </h4>
                        </div>
                        <div class="modal-body">

                            <form class="form-horizontal" x-show="loadingTela=='doc'" x-on:submit.prevent="storeDocumento" id="form_DOCUMENTO"
                                method="post">
                                @csrf
                                <input type="hidden" name="cdDocumento" x-model="Anamnese.cdDocumento">
                                <div class="form-group" style="margin-bottom: 5px;">

                                    <div class="col-md-6">
                                        <label class="mat-label"><strong>Lista de Documentos</strong> <span
                                                class="red normal">*</span></label>
                                        <select class="form-control selectDois" style="width: 100%;" id="modeloDocumento"
                                            name="cd_formulario">
                                            <option value="">...</option>
                                            @foreach ($docProfissional as $key => $value)
                                            <option value="{{ $value->cd_formulario }}">
                                                {{ $value->nm_formulario }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mat-label"><strong>Titulo</strong> <span
                                                class="red normal">*</span></label>
                                        <input type="text" class="form-control required" style="height: 30px;"
                                            name="titulo" maxlength="100" aria-required="true"
                                            x-model="Anamnese.Titulo" required>
                                    </div>
                                    <div class="col-md-12" style="margin-top: 5px;">
                                        <textarea rows="10" class="form-control" name="documento" id="editor-formulario" x-model="Anamnese.Documento"></textarea>

                                    </div>
                                </div>
                                <div class="panel-footer  ">

                                    <button type="submit" class="btn btn-success" x-html="buttonSalvar"
                                        x-bind:disabled="buttonDisabled"> </button>

                                    <button type="button" class="btn btn-primary " x-on:click="cancelarEdicao"
                                        x-show="(snEdicaoDoc==true)"><i class="fa fa-mail-reply"></i> Cancelar
                                        Edição</button>

                                    <code
                                        style="margin-left: 30px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);     border-radius: 3px;"
                                        x-html="'Documento ' + Anamnese.Titulo + ' {codigo: ' + Anamnese.cdDocumento + '} esta sendo editado!'"
                                        x-show="(snEdicaoDoc==true)">
                                    </code>

                                </div>
                            </form>

                            <form class="form-horizontal" x-show="loadingTela=='msg'" x-on:submit.prevent="storeMsg" id="form_MSG"
                                method="post">
                                @csrf
                                <input type="hidden" name="cdDocumento" x-model="msg.doc">
                                <div class="form-group" style="margin-bottom: 5px;">


                                    <div class="col-md-6">
                                        <label class="mat-label"><strong>Celular</strong> <span
                                                class="red normal">*</span></label>
                                        <input type="text" class="form-control required" style="height: 30px;"
                                            name="celular" maxlength="100" onkeypress="mask(this, mphone);" onblur="mask(this, mphone);" x-model="msg.celular" aria-required="true" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="mat-label"><strong>Documento</strong> <span class="red normal">*</span></label>
                                        <input type="text" class="form-control required" style="height: 30px;" name="titulo" maxlength="100"
                                            aria-required="true" x-model="msg.titulo" readonly required>
                                    </div>
                                    <div class="col-md-1" style="margin-top: 23px;">
                                        <button type="button" x-on:click="loadingTela='doc'" class="btn btn-flickr m-b-xs"><span aria-hidden="true" class="icon-arrow-left"></span></button>
                                    </div>
                                    <div class="col-md-12" style="margin-top: 5px;">
                                        <textarea rows="5" class="form-control" x-model="msg.msg" name="msg"></textarea>
                                    </div>
                                </div>
                                <div class="panel-footer  ">

                                    <button type="submit" class="btn btn-success" x-html="buttonEnviar"
                                        x-bind:disabled="buttonDisabled"> </button>


                                </div>
                            </form>
                            <br>
                            <div role="tabpanel">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs nav-justified" role="tablist">
                                    <li role="presentation" class="active"><a href="#tabDocumentos" role="tab" data-toggle="tab" aria-expanded="false">Relação de Documentos</a></li>
                                    <li role="presentation"><a href="#tabEnvios" role="tab" data-toggle="tab" aria-expanded="true">Relação de Envios</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade  active in" id="tabDocumentos">

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

                                                        <tr>
                                                            <th><span x-text="doc.cd_documento_paciente"></span></th>
                                                            <td><span x-text="doc.titulo"></span></td>
                                                            <td><span x-text="doc.profissional?.nm_profissional"></span></td>
                                                            <td><span x-text="doc.data_hora"></span></td>
                                                            <td class="text-center">
                                                                <div class="btn-group btn-xs">
                                                                    <button type="button"
                                                                        class="btn btn-default btn-addon m-b-sm btn-rounded btn-xs dropdown-toggle"
                                                                        data-toggle="dropdown" aria-expanded="false"
                                                                        style="margin-bottom: 0px; font-size: 1.3rem !important; color: #7a6fbe; font-weight: 600;">
                                                                        &nbsp;Opções &nbsp; <span class="caret"></span>
                                                                        &nbsp;
                                                                    </button>
                                                                    <ul class="dropdown-menu" role="menu">

                                                                        <li>
                                                                            <a x-bind:href="'/rpclinica/json/imprimirDocumentoPaciente/' + doc
                                                                                    .cd_documento_paciente"
                                                                                target="_blank"
                                                                                style="color: #22baa0;font-weight: 600;">
                                                                                <i class="fa fa-print"
                                                                                    style="margin-left:4px; margin-right: 4px;
                                                                                        "></i>
                                                                                &nbsp;Imprimir
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="#" x-on:click="teste(doc)"
                                                                                style="color: #12AFCB;font-weight: 600;">
                                                                                <i class="fa fa-share-alt"
                                                                                    style="margin-left:4px; margin-right: 4px;
                                                                                        "></i>
                                                                                &nbsp;Compartilhar
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="#" x-on:click="editDocumento(doc)"
                                                                                style="color: #7a6fbe; font-weight: 600;">
                                                                                <i class="fa fa-edit"
                                                                                    style="margin-left:4px; margin-right: 4px;  "></i>
                                                                                &nbsp;Editar
                                                                            </a>
                                                                        </li>

                                                                        <li class="divider" style="margin:0;"></li>

                                                                        <li>
                                                                            <a href="#"
                                                                                x-on:click="excluirDocumento(doc.cd_documento_paciente)"
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
                                                </template>

                                            </tbody>
                                        </table>

                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tabEnvios">

                                        <table class="table table-striped">
                                            <thead>
                                                <tr class="active">
                                                    <th>Codigo</th>
                                                    <th>Celular</th>
                                                    <th>Documento</th>
                                                    <th>Usuario</th>
                                                    <th>Data</th>
                                                    <th>Msg</th>
                                                </tr>

                                            </thead>

                                            <tbody>


                                                <template x-if="dadosEnvios.length > 0">
                                                    <template x-for="(doc,indice) in dadosEnvios">

                                                        <tr>
                                                            <th><span x-text="doc.cd_paciente_envio"></span></th>
                                                            <td><span x-text="doc.celular"></span></td>
                                                            <td><span x-text="doc.paciente_documento?.titulo"></span></td>
                                                            <td><span x-text="doc.usuario.nm_usuario"></span></td>
                                                            <td><span x-text="doc.data_hora"></span></td>
                                                            <td><span x-html="'<b>ID: ' + doc.id_msg + '</b><br>' + doc.msg"></span></td>
                                                        </tr>

                                                    </template>
                                                </template>

                                            </tbody>
                                        </table>

                                    </div>

                                </div>
                            </div>





                        </div>

                    </div>
                </div>
            </div>


            <div class="panel-body">
                <div class="table-responsive">
                    <table class="display table dataTable table-striped">
                        <thead>
                            <tr>
                                <th>#ID</th>
                                <th>Foto</th>
                                <th>Nome / Dt.Nasc.</th>
                                <th>Mãe / Pai</th>
                                <th>CPF / RG</th>
                                <th>Categoria / Cartão</th>
                                <th class="text-center">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pacientes as $paciente)
                            <tr id="pac-{{ $paciente->cd_paciente }}" style="cursor: pointer;">
                                <th>{{ $paciente->cd_paciente }}</th>
                                <td>
                                    <img src="{{ $paciente->foto_url ? $paciente->foto_url : asset('assets/images/avatarPaciente.png') }}"
                                        loading="lazy" style="width: 52px; height: 52px; border-radius: 50px" />
                                </td>
                                <td>
                                    {!! $paciente->nm_paciente !!}
                                    {!! $paciente->nome_social
                                    ? '<span style="font-size: 12px; font-style: italic;"><br><b>Nome Social: </b>' . $paciente->nome_social . '</span>'
                                    : null !!}
                                    <div class="page-breadcrumb">
                                        <ol class="breadcrumb" style="font-size: 12px;">
                                            <i class="fa fa-calendar"></i> {!! $paciente->data_nasc !!}
                                        </ol>
                                    </div>
                                </td>
                                <td>
                                    {!! $paciente->nm_mae ? '' . substr($paciente->nm_mae, 0, 25) : ' -- ' !!}
                                    <div class="page-breadcrumb">
                                        <ol class="breadcrumb" style="font-size: 12px;">
                                            <span aria-hidden="true" class="icon-user"></span>
                                            {!! $paciente->nm_pai ? '' . substr($paciente->nm_pai, 0, 25) : ' Não Info. ' !!}
                                        </ol>
                                    </div>
                                </td>
                                <td>
                                    CPF: {!! $paciente->cpf ? '' . $paciente->cpf : ' -- ' !!}
                                    <div class="page-breadcrumb">
                                        <ol class="breadcrumb" style="font-size: 12px;">
                                            <i class="fa fa-folder-open-o"></i> RG:
                                            {{ $paciente->rg ? '' . $paciente->rg : ' -- ' }}
                                        </ol>
                                    </div>
                                </td>
                                <td>
                                    {!! $paciente['convenio']?->nm_convenio ? '' . $paciente['convenio']?->nm_convenio : ' Não Info. ' !!}
                                    <div class="page-breadcrumb">
                                        <ol class="breadcrumb" style="font-size: 12px;">
                                            <i class="fa fa-credit-card"></i>
                                            {!! $paciente->cartao ? '' . $paciente->cartao : ' Não Info. ' !!}
                                        </ol>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">

                                        <a href="#" x-on:click="openModalDocumento({{ $paciente }})"
                                            data-toggle="modal" data-target="#documentoPaciente"
                                            class="btn btn-info" style="margin-right: 5px;">
                                            <i class="fa fa-file-text-o"></i>
                                        </a>

                                        <a href="{{ route('paciente.edit', ['paciente' => $paciente->cd_paciente]) }}"
                                            class="btn btn-success">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    Nenhum paciente
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>






                    <div class="box-footer clearfix"></div>
                </div>

                <div style="display: flex; justify-content: center">
                    {{ $pacientes->links() }}
                </div>
            </div>

        </div>
    </div>


    @if (auth()->guard('rpclinica')->user()->cd_profissional)
    <div class="modal fade" id="modal-historico-paciente">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title">Histórico do paciente</h4>
                </div>

                <div class="modal-body">
                    <div
                        style="border: 1px solid rgba(255, 255, 255, 0.1); padding: 5px; margin-bottom: 10px; background: rgba(255, 255, 255, 0.05); padding-top: 12px">
                        <div class="row" style="padding-bottom: 10px; ">
                            <div class=" col-md-3 ">
                                <div style="display: flex; justify-content: center">
                                    <img id="fotodopaciente " x-bind:src="pacienteSelected?.foto_url"
                                        height="80% " class="img-perfil img-circle img-responsive "
                                        style="width: 126px; height: 126px">
                                </div>
                            </div>
                            <div class="col-md-9 ">
                                <ul class="list-paciente-info">
                                    <li>
                                        <strong class="m-t-sm">
                                            <span aria-hidden="true" class="icon-user"></span> Paciente:
                                        </strong>

                                        <span x-text="pacienteSelected?.nm_paciente"></span>
                                    </li>

                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class="icon-calendar"></span> Data de
                                            nascimento:
                                        </strong>

                                        <span
                                            x-text="pacienteSelected?.dt_nasc ? formatDate(pacienteSelected?.dt_nasc): ''"></span>
                                    </li>

                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class="icon-question"></span> Sexo:
                                        </strong>

                                        <span
                                            x-text="pacienteSelected?.sexo ? pacienteSelected?.sexo == 'H' ? 'Masculino' : 'Feminino': ''"></span>
                                    </li>
                                </ul>

                                <ul class="list-paciente-info">
                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class="icon-note"></span> RG
                                        </strong>

                                        <span x-text="pacienteSelected?.rg"></span>
                                    </li>

                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class=" icon-paper-clip"></span> CPF
                                        </strong>

                                        <span x-text="pacienteSelected?.cpf"></span>
                                    </li>

                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class=" icon-tag"></span> Estado Civil
                                        </strong>

                                        <span x-text="valuesEstadoCivil[pacienteSelected?.estado_civil]"></span>
                                    </li>

                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class="icon-call-end"></span> Celular
                                        </strong>

                                        <span x-text="pacienteSelected?.celular"></span>
                                    </li>

                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class="icon-user"></span> Nome do Pai
                                        </strong>

                                        <span x-text="pacienteSelected?.nm_pai"></span>
                                    </li>

                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class="icon-user-female"></span> Nome da Mãe
                                        </strong>

                                        <span x-text="pacienteSelected?.nm_mae"></span>
                                    </li>
                                </ul>

                                <ul class="list-paciente-info">
                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class="icon-credit-card"></span> Numero do
                                            Cartão:
                                        </strong>

                                        <span x-text="pacienteSelected?.cartao"></span>
                                    </li>

                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class="icon-book-open"></span> Convênio:
                                        </strong>

                                        <span x-text="pacienteSelected?.convenio?.nm_convenio"></span>
                                    </li>

                                    <li>
                                        <strong>
                                            <span aria-hidden="true" class="icon-calendar"></span> Último
                                            Atendimento:
                                        </strong>

                                        <span>--- </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <template x-if="historicoPrintAgendamento">
                        <div>
                            <div class="m-b-lg no-print">
                                <button class="btn btn-light" x-on:click="historicoPrintAgendamento = false">
                                    <i class="fa fa-long-arrow-left"></i> Voltar
                                </button>

                                <button class="btn btn-success" x-on:click="imprimirAnamnese">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>

                            <h3 class="m-b-lg">Agendamento <span
                                    x-text="formatDate(historicoAgendamentoSelected?.data_horario)"></span>
                            </h3>

                            <div class="row" style="margin-bottom: 8px">
                                <div class="col-md-3 col-xs-3">
                                    <h4>Data e horário:</h4>
                                    <span
                                        x-text="formatDate(historicoAgendamentoSelected?.data_horario, 'lll')"></span>
                                </div>

                                <div class="col-md-3 col-xs-3">
                                    <h4>Profissional</h4>
                                    <span
                                        x-text="historicoAgendamentoSelected?.profissional?.nm_profissional"></span>
                                </div>

                                <div class="col-md-3 col-xs-3">
                                    <h4>Especialidade</h4>
                                    <span
                                        x-text="historicoAgendamentoSelected?.especialidade?.nm_especialidade"></span>
                                </div>

                                <div class="col-md-3 col-xs-3">
                                    <h4>Local de atendimento</h4>
                                    <span x-text="historicoAgendamentoSelected?.agenda?.local?.nm_local"></span>
                                </div>
                            </div>

                            <hr />

                            <h4 class="m-b-md">Anamnese</h4>

                            <div>
                                <h5>Queixa princiapl</h5>
                                <p x-text="historicoAgendamentoSelected?.queixa_principal">
                                </p>

                                <div class="row">
                                    <div class="col-md-2 col-xs-2">
                                        <h5>Peso</h5>
                                        <p x-text="historicoAgendamentoSelected?.peso"></p>
                                    </div>

                                    <div class="col-md-2 col-xs-2">
                                        <h5>Altura</h5>
                                        <p x-text="historicoAgendamentoSelected?.altura">
                                        </p>
                                    </div>

                                    <div class="col-md-2 col-xs-2">
                                        <h5>IMC</h5>
                                        <p x-text="historicoAgendamentoSelected?.imc"></p>
                                    </div>

                                    <div class="col-md-2 col-xs-2">
                                        <h5>Temperatura</h5>
                                        <p x-text="historicoAgendamentoSelected?.temperatura">
                                        </p>
                                    </div>

                                    <div class="col-md-2 col-xs-2">
                                        <h5>CID</h5>
                                        <p x-text="historicoAgendamentoSelected?.cid"></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-xs-3">
                                        <h5>Pressão arterial sistótica</h5>
                                        <p x-text="historicoAgendamentoSelected?.arterial_sistotica">
                                        </p>
                                    </div>

                                    <div class="col-md-3 col-xs-3">
                                        <h5>Pressão arterial diastólica</h5>
                                        <p x-text="historicoAgendamentoSelected?.arterial_diastolica">
                                        </p>
                                    </div>

                                    <div class="col-md-3 col-xs-3">
                                        <h5>Frequência respiratoria</h5>
                                        <p x-text="historicoAgendamentoSelected?.frequencia_respiratoria">
                                        </p>
                                    </div>

                                    <div class="col-md-3 col-xs-3">
                                        <h5>Frequência cardiaca</h5>
                                        <p x-text="historicoAgendamentoSelected?.frequencia_cardiaca">
                                        </p>
                                    </div>
                                </div>

                                <hr />

                                <h5>Anamnese</h5>
                                <p x-html="historicoAgendamentoSelected?.anamnese"></p>

                                <hr />

                                <h5>Exame Fisico</h5>
                                <p x-html="historicoAgendamentoSelected?.exame_fisico"></p>

                                <hr />

                                <h5>Hipotese Diagnóstica</h5>
                                <p x-html="historicoAgendamentoSelected?.hipotese_diagnostica"></p>

                                <hr />

                                <h5>Conduta</h3>
                                    <p x-html="historicoAgendamentoSelected?.conduta"></p>

                                    <hr />

                                    <h3>Documentos</h3>

                                    <template x-for="documento in historicoAgendamentoSelected?.documentos">
                                        <div>
                                            <div class="line bg-light"
                                                style="justify-content: space-between; padding: 6px 12px">
                                                <h5 x-text="documento.nm_formulario"></h5>

                                                <button class="btn btn-success"
                                                    x-on:click="imprimirDocumento(documento.cd_documento)">
                                                    <i class="fa fa-print"></i>
                                                </button>
                                            </div>

                                            <div x-html="documento.conteudo"></div>
                                        </div>
                                    </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="!historicoPrintAgendamento">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Data</th>
                                    <th>Prestador</th>
                                    <th>Especialidade</th>
                                    <th>Situação</th>
                                    <th class="text-center">Detalhes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="agendamento in pacienteSelected?.agendamentos">
                                    <tr>
                                        <th scope="row"
                                            x-text="agendamento.cd_agendamento.toString().padStart(5, '0')"></th>

                                        <td x-text="formatDate(agendamento.data_horario)"></td>

                                        <td x-text="agendamento?.profissional?.nm_profissional ?? ' -- '"></td>

                                        <td x-text="agendamento?.especialidade?.nm_especialidade ?? ' -- '"></td>

                                        <td>
                                            <span class="label"
                                                x-bind:class="classLabelSituacao[agendamento.situacao]"
                                                x-text="agendamento.situacao"></span>
                                        </td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success"
                                                    x-on:click="openModalHistoricoAgendamento(agendamento.cd_agendamento)">
                                                    <i class="fa fa-flickr"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <template x-if="pacienteSelected?.agendamentos.length == 0">
                                    <tr>
                                        <td colspan="6" class="text-center">Nenhum histórico</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </template>
                </div>

                <div class="modal-footer"></div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-resumo-paciente">
        <div class="modal-dialog modal-lg">
            <form x-on:submit.prevent="submitConsulta" class="modal-content" x-ref="formIniciarConsulta">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Resumo do paciente</h4>
                </div>

                <div class="modal-body">
                    <div
                        style="border: 1px solid #ddd; padding: 5px; margin-bottom: 10px; background: #fbfbfb; padding-top: 12px">
                        <div class="row" style="padding-bottom: 10px; ">
                            <div class=" col-md-2 " style="text-align: center;">
                                <div style="display: flex; justify-content: center">
                                    <img id="fotodopaciente " x-bind:src="pacienteSelected?.foto_url"
                                        height="80% " class="img-perfil img-circle img-responsive "
                                        style="width: 126px; height: 126px;text-align: center;">
                                </div>
                            </div>
                            <div class="col-md-10 ">
                                <div class="server-load">
                                    <table width=100%>
                                        <tr>
                                            <td style=" width: 55% ">
                                                <div class="server-stat">
                                                    <span>Paciente</span>
                                                </div>
                                            </td>
                                            <td style=" width: 25% ">
                                                <div class="server-stat">
                                                    <span>Data Nasc.</span>
                                                </div>
                                            </td>
                                            <td style=" width: 20% ">
                                                <div class="server-stat">
                                                    <span>Sexo</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h2 x-text="pacienteSelected?.nm_paciente"></h2>
                                            </td>
                                            <td>
                                                <h2
                                                    x-text="pacienteSelected?.dt_nasc ? formatDate(pacienteSelected?.dt_nasc): '' ">
                                                </h2>
                                            </td>
                                            <td>
                                                <h2
                                                    x-text="pacienteSelected?.sexo ? pacienteSelected?.sexo == 'H' ? 'Masculino' : 'Feminino' : '' ">
                                                </h2>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <ul class="list-paciente-info" style="margin-top: 10px;">
                                    <li>
                                        <strong>RG</strong><br>
                                        <span x-text="pacienteSelected?.rg"></span>
                                    </li>

                                    <li>
                                        <strong>CPF</strong><br>
                                        <span x-text="pacienteSelected?.cpf"></span>
                                    </li>

                                    <li>
                                        <strong> Celular</strong><br>
                                        <span x-text="pacienteSelected?.celular"></span>
                                    </li>

                                    <li>
                                        <strong> Nome do Pai </strong><br>
                                        <span x-text="pacienteSelected?.nm_pai"></span>
                                    </li>
                                </ul>

                                <ul class="list-paciente-info">


                                    <li>
                                        <strong> Nome da Mãe</strong><br>
                                        <span x-text="pacienteSelected?.nm_mae"></span>
                                    </li>

                                    <li>
                                        <strong> Convênio:</strong><br>
                                        <span x-text="pacienteSelected?.convenio?.nm_convenio"></span>
                                    </li>
                                    <li>
                                        <strong> Numero do Cartão: </strong><br>
                                        <span x-text="pacienteSelected?.cartao"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <hr />

                    <input type="hidden" name="paciente" x-bind:value="pacienteSelected?.cd_paciente" />

                    <div class="row">
                        <div class="col-md-5">
                            <label>Convênio <span class="text-danger">*</span></label>

                            <div class="form-group">
                                <select class="form-control" style="width: 100%" name="convenio" id="convenio"
                                    required>
                                    <option value="">SELECIONE</option>
                                    @foreach ($convenios as $convenio)
                                    <option value="{{ $convenio->cd_convenio }}">{{ $convenio->nm_convenio }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label>Cartão</label>

                            <div class="form-group">
                                <input type="text" class="form-control" id="cartao" name="cartao" />
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label>Tipo: <span class="red normal">*</span></label>
                            <div class="form-group">
                                <select class="form-control" style="width: 100%" name="tipo"
                                    id="agendamento-tipo" required>
                                    <option value="">SELECIONE</option>
                                    <option value="consulta">Consulta</option>
                                    <option value="retorno">Retorno</option>
                                    <option value="encaixe">Encaixe</option>
                                    <option value="exame">Exame</option>
                                    <option value="terapia">Terapia</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Procedimento <span class="text-danger">*</span></label>
                            <select class="form-control" style="width: 100%" name="procedimento"
                                id="procedimento" required>
                                <option value="">SELECIONE</option>
                                @foreach ($procedimentos as $procedimento)
                                <option value="{{ $procedimento->cd_proc }}">{{ $procedimento->nm_proc }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Especialidade <span class="text-danger">*</span></label>
                            <select class="form-control" style="width: 100%" name="especialidade"
                                id="especialidade" required>
                                <option value="">SELECIONE</option>
                                @foreach ($especialidades as $especialidade)
                                <option value="{{ $especialidade->cd_especialidade }}">
                                    {{ $especialidade->nm_especialidade }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Local de atendimento <span class="text-danger">*</span></label>
                            <select class="form-control" style="width: 100%" name="local" id="local"
                                required>
                                <option value="">SELECIONE</option>
                                @foreach ($locaisAtendimento as $local)
                                <option value="{{ $local->cd_local }}">{{ $local->nm_local }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="line" style="justify-content: flex-end">
                        <template x-if="loadingConsulta">
                            <x-loading message="Iniciando consulta..." />
                        </template>

                        <button type="submit" class="btn btn-success" x-bind:disabled="loadingConsulta">Iniciar
                            consulta</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

@if (auth()->guard('rpclinica')->user()->cd_profissional)
@section('scripts')
<style>
    #calendar .datepicker.datepicker-inline {
        width: 100%;
    }

    #calendar .datepicker.datepicker-inline table {
        margin: 0 auto;
        width: 100%;
    }

    .select2-container.select2-container--default.select2-container--open,
    .swal2-container {
        z-index: 9999;
    }
</style>
<script>
    if(typeof CKEDITOR !== 'undefined') {
        CKEDITOR.config.baseFloatZIndex = 20000;
    }
    const pacientes = @js($pacientes);
    const documentos = @js($docProfissional);
</script>
<script src="{{ asset('js/rpclinica/paciente-listar.js') }}"></script>
<script>
    //select 2
    $('#modeloDocumento').select2({
        dropdownParent: $('#documentoPaciente')
    });

    $(function() {
        const $modalDocumento = $('#documentoPaciente');
        if ($modalDocumento.length) {
            $modalDocumento.appendTo('body');
            $modalDocumento.on('shown.bs.modal', function() {
                // const $backdrop = $('.modal-backdrop').last();
                // $backdrop.css('z-index', 9998);
                // $modalDocumento.css('z-index', 9999);
                if ($.fn.unblock) {
                    $modalDocumento.unblock();
                }
            });
        }
    });

    function mask(o, f) {
        setTimeout(function() {
            var v = mphone(o.value);
            if (v != o.value) {
                o.value = v;
            }
        }, 1);
    }

    function mphone(v) {
        var r = v.replace(/\D/g, "");
        r = r.replace(/^0/, "");
        if (r.length > 10) {
            r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
        } else if (r.length > 5) {
            r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
        } else if (r.length > 2) {
            r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
        } else {
            r = r.replace(/^(\d*)/, "($1");
        }
        return r;
    }
</script>
@endsection
@endif