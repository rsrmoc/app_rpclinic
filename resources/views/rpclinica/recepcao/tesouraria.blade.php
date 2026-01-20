@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Tela da Tesouraria</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('tesouraria') }}">Atendimento Particular</a></li>
            </ol> 
        </div>
    </div>

    <style>
        .panel-body-livre {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #2ecc71;
        }

        .info-box .color-livre i {
            color: #2ecc71;
        }

        .whastValido {
            color: #2ecc71;
        }

        .whastInvalido {
            color: #ee6414;
        }

        .whastNeutro {
            color: #333;

        }

        .panel-body-agendado {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #7a6fbe;
        }

        .info-box .color-agendado i {
            color: #7a6fbe;
        }

        .panel-body-confirmado {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #12AFCB;
        }

        .info-box .color-confirmado i {
            color: #12AFCB;
        }

        .panel-body-aguardando {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #FF9800;
        }

        .info-box .color-aguardando i {
            color: #FF9800;
        }

        .panel-body-atendido {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #f6d433;
        }

        .info-box .color-atendido i {
            color: #f6d433;
        }

        .panel-body-cancelado {
            box-shadow: 0 2px 1px rgba(0, 0, 0, 0.05);
            transition: box-shadow 0.2s ease-in-out;
            border-top: 3px solid #f25656;
        }

        .info-box .color-cancelado i {
            color: #f25656;
        }

        .label-black {
            background: #34425a;
        }

        <style>.ModalAgendamento .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
        }

        .ModalAgendamento .modal-dialog {
            position: fixed;
            margin: 0;
            width: 100%;
            height: 100%;
            padding: 0;
        }

        .ModalAgendamento .modal-content {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            border: 1px solid #ddd;
            border-radius: 0;
            box-shadow: none;
        }

        .ModalAgendamento .modal-header {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: 60px;
            padding: 10px;
            background: #f1f3f5;
            border: 0;
        }

        .ModalAgendamento .modal-title {
            font-weight: 300;
            font-size: 2em;
            color: #444444;
            line-height: 30px;
        }

        .ModalAgendamento .modal-body {
            position: absolute;
            top: 50px;
            bottom: 0px;
            width: 100%;
            overflow: auto;
            background: #f1f3f5;
        }

        .ModalAgendamento .modal-footer {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            /*padding: 2px;*/
            background: #f1f3f5;
        }

        .ModalAgendamento .form-group {
            margin-bottom: 10px;
        }

        .form-control[disabled],
        .form-control[readonly],
        fieldset[disabled] .form-control {
            background-color: #f1f3f5;
            opacity: 1;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: 0px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            padding-right: 5px;
        }
        .stats-info ul li { 
            padding: 5px 0;
        }
    </style>

    <style>
        .btn-group>.btn {
            padding: 6px 10px;
        }

        .btn-default.active {
            background-color: #b7ebe2;
            font-weight: 900;
        }
        .label-recebido{
            background-color: #5cb85c;
        }
        label { 
            margin-bottom: 0px; 
        }
        .redNot{
            color: #a94442;
            font-weight: bold;
        }
    </style>

    <div id="app" x-data="app">
        <div id="main-wrapper">

            <div class="col-md-12 ">

                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified" role="tablist"> 
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active fade in" id="tabAgendamnento">

                                <div class="panel panel-white">
                                    <div class="panel-body">
                                        <div class="row">

                                            <form x-on:submit.prevent="getAtendimentos" id="form-horario" class="col-md-3"
                                                style="margin-bottom: 22px">

                                                <div id="loading-dados-mes" style="display: none">
                                                    <x-loading message="Buscando dados do mês..." />
                                                </div>
                                                <div id="calendar"></div>

                                                <input type="hidden" name="data" id="data-input" />
                                                <input type="hidden" name="tela" value="agendamento" />
 
                                                <hr/>
 
                                                    <label>Profissional:</label>
                                                    <div class="form-data" style="margin-bottom: 10px">
                                                        <select class="form-control" name="profissional" style="width: 100%;">
                                                            <option value="">Todos profissionais</option>
                                                            @foreach ($profissionais as $profissional)
                                                                <option value="{{ $profissional->cd_profissional }}">
                                                                    {{ $profissional->nm_profissional }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>  
 
                                                     
                                                    <div class="stats-info" style="border: 1px solid #dce1e4;">
                                                        <div class="panel-heading text-center" style="    padding-bottom: 5px">
                                                            <h4 class="panel-title text-center" style="    float: none;">Resumo do dia</h4>
                                                        </div>
                                                        <div class="panel-body" style="    padding: 0 5px 20px;">
                                                                
                                                            <template x-if="loading">
                                                                <div style="text-align: center;font-style: italic; float: none; padding-top: 25px; padding-bottom: 15px;">
                                                                    <i class='fa fa-spinner fa-spin' aria-hidden='true'></i><br>
                                                                    Carregando Informações
                                                                </div>
                                                            </template>

                                                            <template x-if="!loading">

                                                                <ul class="list-unstyled">
                                                                    <li>Produção
                                                                        <div class="text-info pull-right" style="font-weight: bold;">
                                                                            <span x-html="(resumo.Conta) ?  formatValor(resumo.Conta) + '&nbsp;&nbsp; [ ' + resumo.qtdConta + ' ] '  : formatValor(0) "></span>
                                                                          
                                                                        </div>
                                                                    </li>
                                                                    <li>Atend. Quitados
                                                                        <div class="text-success pull-right" style="font-weight: bold;">
                                                                            <span x-html="(resumo.Financeiro) ?  formatValor(resumo.Financeiro) + '&nbsp;&nbsp; [ ' + resumo.qtdFinanceiro + ' ] '  : formatValor('0')"></span>
                                                                           
                                                                        </div>
                                                                    </li>
                                                                    <li>Atend. Pendentes
                                                                        <div class="text-danger pull-right" style="font-weight: bold;">
                                                                            <span x-html="(resumo.qtdPendente) ? (  formatValor(resumo.Pendente) + '&nbsp;&nbsp; [ ' + resumo.qtdPendente + ' ] ' ) : formatValor(0)  "></span>
                                                                            
                                                                        </div>
                                                                    </li>
                                                                    <br>
                                                                    <template x-for="item in resumo.Formas">
                                                                        <li>
                                                                            <span x-html="item.nm_forma_pag"> </span>
                                                                            <div class="text-info pull-right" style="font-weight: bold;">
                                                                                <span x-html=" (  formatValor(item.valor.replace('.', '').replace(',', '.')) + '&nbsp;&nbsp; [ ' + item.qtde + ' ] ' ) "></span>
                                                                            
                                                                            </div>
                                                                        </li>
                                                                    </template>

                                                                    <li > 
                                                                        <div class="text-primary  pull-right" 
                                                                            style="font-weight: bold; font-size: 18px; margin-right: 5px;">
                                                                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                                                        </div>
                                                                    </li>
                                                                      
                                                                </ul>

                                                            </template>
                                                            
                                                        </div>
                                                    </div>
                                          
                                            </form>

                                            <div class="col-md-9">
                                                <template x-if="messageDanger">
                                                    <div class="alert alert-danger">
                                                        <span x-text="messageDanger"></span>
                                                        <button x-on:click="messageDanger = null" type="button"
                                                            class="close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                </template>

                                                <table class="table table-striped table-hover">
                                                    <thead>
                                                        <tr class="active">
                                                            <th class="text-center">Atendimento</th>
                                                            <th>Paciente</th>
                                                            <th>Convênio</th>
                                                            <th>Profissional</th> 
                                                            <th class="text-right">Valor Conta</th>
                                                            <th class="text-right">Valor Financeiro</th>  
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr x-show="loading">
                                                            <td colspan="8">
                                                                <div class="line">
                                                                    <div class="loading"></div>
                                                                    <span style="font-size: 1.2em; font-style: italic;">Atualizando Atendimentos...</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <template x-if="horarios">
                                                            <template x-for="(horario, index) in horarios">

                                                                <tr style="cursor: pointer" 
                                                                    x-on:click="clickAtendimento(horario,index)">

                                                                    <th class="text-center">
                                                                        <span style="font-size: 1.2em; font-style: italic;"
                                                                            class="btn btn-default btn-rounded btn-xs " 
                                                                            x-html="horario.cd_agendamento"></span>  
                                                                    </th>

                                                                    <td>
                                                                        <span
                                                                            x-text="horario.paciente?.nm_paciente"></span>
                                                                        <span
                                                                            style="font-size: 12px; font-style: italic;"><br><b>Nome
                                                                                Social: </b>
                                                                            <span
                                                                                x-text="(horario.paciente?.nome_social) ? horario.paciente?.nome_social : ' -- '">
                                                                            </span>
                                                                        </span>
                                                                    </td>

                                                                    <td>
                                                                        <span  x-text="horario.convenio?.nm_convenio"></span>
                                                                        <span
                                                                        style="font-size: 12px; font-style: italic;"><br><b>Especialidade: </b>
                                                                        <span
                                                                            x-text="(horario.especialidade?.nm_especialidade) ? horario.especialidade?.nm_especialidade : ' -- '">
                                                                        </span>
                                                                    </span>
                                                                    </td>

                                                                    <td> 
                                                                        <span x-text="horario.profissional?.nm_profissional"></span>
                                                                        <span
                                                                        style="font-size: 12px; font-style: italic;"><br><b>Tipo Atend.: </b>
                                                                        <span
                                                                            x-text="(horario.tipo_atend?.nm_tipo_atendimento) ? horario.tipo_atend?.nm_tipo_atendimento : ' -- '">
                                                                        </span>
                                                                    </td>

                                                                    <td class="text-right  " >
                                                                        <span style="  font-style: italic; font-weight: 600" x-html="vlConta(horario.itens,'F')"></span><br>
                                                                        <template x-if="(horario.situacao_conta=='F')">
                                                                            <code style="  font-style: italic; font-weight: 600; color: #0062a5;background-color: #bcd7fa;"> Conta Fechada </code>
                                                                        </template>
                                                                        <template x-if="(horario.situacao_conta=='A')">
                                                                            <code style="  font-style: italic; font-weight: 600; color: #a50000; "> Conta Aberta </code>
                                                                        </template>
                                                                        
                                                                    </td>
                                                                    
                                                                    <td class="text-right "  >
                                                                        <span style="  font-style: italic; font-weight: 600" class="text-info" x-html="valorFinanceiro(horario.boleto,'F')">  </span> <br>
                                                                        <template x-if="horario.recebido=='N'">
                                                                            <span style="  font-style: italic; font-weight: 600" class="text-danger"  > Pendente </span>
                                                                        </template>
                                                                        <template x-if="horario.recebido=='S'">
                                                                            <span style="  font-style: italic; font-weight: 600" class="text-success" > Liberado </span>
                                                                        </template>
                                                                    </td>
                                                                    
                                                              

                                                                </tr>
                                                            </template>
                                                        </template>
                                                    </tbody>
                                                </table>
                                                <template x-if="horarios == null">
                                                    <p class="text-center" style="padding: 1.5em">

                                                        <img src="{{ asset('assets\images\calendario.png') }}"> <br>
                                                        Não há atendimentos para esse dia
                                                    </p>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>

            <div class="ModalAgendamento">
                @include('rpclinica.recepcao.agendamento.modal_tesouraria')
            </div>


        </div>


    </div>

    </div>
    </div>
@endsection

@section('scripts')
    <style>
        .box-btn-float {
            position: fixed;
            bottom: 2em;
            right: 2em;
            z-index: 999;
        }

        .text-aguardando {
            color: #FF9800;
        }

        .box-btn-float button {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 3.5em;
            width: 3.5em;
            background-color: #22baa0;
            color: #ffffff;
            border: none;
            border-radius: 100%;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }

        .box-btn-float button i {
            margin: 0;
            font-size: 2rem;
        }

        .box-btn-float button:hover,
        .box-btn-float button:focus {
            background-color: #1be1bf;
            transform: translateX(0px) scale(1.1);
            transition: transform 0.3s;

        }

        .text-success {
            font-weight: bold;
        }

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

        #cadastro-consulta .tabpanel .nav.nav-tabs li {

            text-align: center;
        }

        #cadastro-consulta .tabpanel .nav.nav-tabs li.active {
            font-weight: bold;
        }
    </style>

    <script src="{{ asset('js/simple-calendar/simple-calendar.js') }}"></script>
    <script src="{{ asset('js/rpclinica/tesouraria.js') }}"></script>
    
    <script> 

        function moeda(a, e, r, t) {
            let n = "",
                h = j = 0,
                u = tamanho2 = 0,
                l = ajd2 = "",
                o = window.Event ? t.which : t.keyCode;
            if (13 == o || 8 == o)
                return !0;
            if (n = String.fromCharCode(o),
                -1 == "0123456789".indexOf(n))
                return !1;
            for (u = a.value.length,
                h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++);
            for (l = ""; h < u; h++) - 1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
            if (l += n, 0 == (u = l.length) && (a.value = ""), 1 == u && (a.value = "0" + r + "0" + l), 2 == u && (a.value = "0" + r + l), u > 2) {
                for (ajd2 = "",
                    j = 0,
                    h = u - 3; h >= 0; h--)
                    3 == j && (ajd2 += e,
                        j = 0),
                    ajd2 += l.charAt(h),
                    j++;
                for (a.value = "",
                    tamanho2 = ajd2.length,
                    h = tamanho2 - 1; h >= 0; h--)
                    a.value += ajd2.charAt(h);
                a.value += r + l.substr(u - 2, u)
            }
            return !1
        }

    </script>

@endsection
