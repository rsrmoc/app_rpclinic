@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">
        <h3>Relação de Agendas</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('agenda.listar') }}">Relação</a></li>
            </ol>
        </div>
    </div>

 
    <div id="main-wrapper" x-data="app">
        <div class="col-md-12 ">
            <div class="panel panel-white"><br>
                <div class="panel-heading clearfix" style="padding-bottom: 4px;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-7">
                            <h4 class="panel-title"> </h4>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-5">
                            <form method="GET" id="searchList">
                                <div class="input-group m-b-sm">
                                    <input type="text" name="b" class="form-control"
                                        placeholder="Pesquisar por ID, código ou nome...">
                                    <span class="input-group-btn">
                                        <a href="{{ route('agenda.create') }}"
                                            class="btn btn-success btn-addon m-b-sm"><span class="item"><span
                                                    aria-hidden="true" class="icon-note"></span>&nbsp;Novo</span></a>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="display table dataTable table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Agenda</th>
                                    <th>Profissional</th>
                                    <th>Especialidade</th>
                                    <th>Procedimento</th>
                                    <th>Local de Atendimento</th>
                                    <th class="text-center" style="width: 80px;">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($agendas as $agenda)
                                    <tr id="agenda-{{ $agenda->cd_agenda }}">
                                        <th>{{ $agenda->cd_agenda }}</th>
                                        <th>{{ $agenda->nm_agenda }}</th>
                                        <td>{{ (empty($agenda->profissional?->nm_profissional)? 'TODOS': $agenda->profissional?->nm_profissional) }}</td>
                                        <td>{{ (empty($agenda->especialidade?->nm_especialidade)? 'TODOS': $agenda->especialidade?->nm_especialidade) }}</td>
                                        <td>{{ (empty($agenda->procedimento?->nm_proc)? 'TODOS': $agenda->procedimento?->nm_proc)  }}</td>
                                        <td>{{ (empty($agenda->local?->nm_local)? 'TODOS': $agenda->local?->nm_local)  }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <!--
                                                <button class="btn btn-primary" x-on:click="openModalGeracao({{ $agenda->cd_agenda }},11)">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                -->
                                                <a href="{{ route('agenda.edit', ['agenda' => $agenda->cd_agenda]) }}" class="btn btn-success" >
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <button onclick="delete_cadastro('{{ route('agenda.delete', ['agenda' => $agenda->cd_agenda]) }}', '#agenda-{{ $agenda->cd_agenda }}')"
                                                    class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="cadastro-geracao">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="absolute-loading" x-show="loadingGeracao">
                        <div class="line">
                            <div class="loading"></div>
                            <span>Carregando...</span>
                        </div>
                    </div>

                    <div class="modal-header m-b-sm">
                        <div class="line" style="justify-content: space-between">
                            <template x-if="loadingGetHorarios">
                                <x-loading message="Buscando horários..." />
                            </template>

                            <template x-if="!loadingGetHorarios">
                                <h4 class="modal-title" x-text="`${agendaHorarios?.escalas.cd_dia ? agendaHorarios?.escalas.cd_dia.toUpperCase(): '' } [${agendaHorarios?.escalas.cd_agenda}] [${agendaHorarios?.escalas.cd_escala_agenda}]`"></h4>
                            </template>
                            <button type="button" class="close m-l-sm" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true"
                                        class="icon-close"></span></span>
                            </button>
                        </div>
                    </div>

                    <div class="modal-body">

                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li role="presentation" class="active"><a href="#tabGeracao" role="tab" data-toggle="tab" aria-expanded="true">Geração de Agenda</a></li>
                                <li role="presentation"><a href="#tabExcluir" role="tab" data-toggle="tab">Exclução por Periodo</a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tabGeracao">
                                    <div style="display: flex; justify-content: space-between; align-items: center">
                                        <h3 x-show="!loadingGetHorarios"
                                            style="font-weight: 300; text-align: center; margin: 0"
                                            x-text="`${formatDate(agendaHorarios?.escalas.dt_inicial)} - ${formatDate(agendaHorarios?.escalas.dt_fim)} ( ${timeCut(agendaHorarios?.escalas.hr_inicial)} - ${timeCut(agendaHorarios?.escalas.hr_final)} ) [ ${calcIntervalo(agendaHorarios?.escalas.intervalo)} ]`"></h3>

                                        <div>
                                            <template x-if="!agendaHorarios?.escalas.escala_gerada && !loadingGetHorarios">
                                                <button type="button" class="btn btn-success" x-on:click="gerarAgendamentos">Gerar agendamentos</button>
                                            </template>
                                        </div>
                                    </div>

                                    <template x-if="agendaHorarios?.escalas.escala_gerada">
                                        <div class="alert alert-warning" style="margin-top: 20px;">
                                            <i class="fa fa-info-circle"></i>&ensp;Escala Gerada com sucesso! 
                                            [ <b>Usuario: </b> <span x-text="agendaHorarios?.escalas.usuario?.email"></span>  ]  [ <b>Data: </b><span x-text="formatDate(agendaHorarios?.escalas.dt_geracao)"></span> ]
                                        </div>
                                    </template>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <h3 style="font-weight: 300; text-align: center;">Horarios da Agenda</h3>
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr class="active">
                                                        <th>Bloqueia</th>
                                                        <th>Horário</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="horario in agendaHorarios?.horarios ?? []">
                                                        <tr class="">
                                                            <td>
                                                                <input type="checkbox" class="flat-red"
                                                                    x-model="inputsGeracao.horarios_bloqueados" x-bind:value="horario.horario" />
                                                            </td>

                                                            <td x-text="timeCut(horario.horario)"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>

                                        </div>

                                        <div class="col-sm-8">

                                            <h3 style="font-weight: 300; text-align: center;">Feriados</h3>
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr class="active">
                                                        <th>Bloqueia</th>
                                                        <th>Data</th>
                                                        <th>Feriado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="feriado in agendaHorarios?.feriados">
                                                        <tr class="">
                                                            <td>
                                                                <input type="checkbox" class="flat-red"
                                                                    x-model="inputsGeracao.feriados" x-bind:value="feriado.dt_feriado" />
                                                            </td>
                                                            <td x-text="formatDate(feriado.dt_feriado)"></td>
                                                            <td x-text="feriado.nm_feriado"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>


                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tabExcluir">

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h3 style="font-weight: 300; text-align: center;">Exlução de Datas</h3>
                                            <form x-on:submit.prevent="pesquisaExclusao" class="row" id="form-exclusao">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Data Inicial: <span class="red normal">*</span></label>
                                                        <input type="date" class="form-control required" name="data_inicial"
                                                            aria-required="true" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Data Final: <span class="red normal">*</span></label>
                                                        <input type="date" class="form-control required" name="data_final"
                                                            aria-required="true" required>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-success col-md-2" style="margin-top: 23px;">Pesquisar</button>
                                            </form>

                                            <template x-if="loadingExclusao">
                                                <x-loading message="Buscando datas..." />
                                            </template>

                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <tr class="active">
                                                        <th>Exluir</th>
                                                        <th>Data</th>
                                                        <th>Ocupados</th>
                                                        <th>Livres</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="valores, data in dadosExclusao">
                                                        <tr class="">
                                                            <td>
                                                                <template x-if="valores.ocupados == 0">
                                                                    <input type="checkbox"  class="flat-red" x-bind:value="data" x-model="datasParaExluir" />
                                                                </template>
                                                            </td>
                                                            <td x-text="formatDate(data)"></td>
                                                            <td x-text="valores.ocupados"></td>
                                                            <td x-text="valores.livres"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>

                                            <template x-if="datasParaExluir.length > 0">
                                                <div>
                                                    <div class="alert alert-danger">
                                                        <template x-for="data, indice in datasParaExluir">
                                                            <span x-text="`${formatDate(data)}${datasParaExluir.length -1 != indice ? ', ': ''}`"></span>
                                                        </template>
                                                    </div>

                                                    <div style="display: flex; justify-content: flex-end; align-items: center">
                                                        <template x-if="loadingExclusaoToggle">
                                                            <x-loading message="" />
                                                        </template>

                                                        <button class="btn btn-danger" x-on:click="excluirDatas" x-bind:disabled="loadingExclusaoToggle">
                                                            <i class="fa fa-trash"></i>&ensp;Excluir datas
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- Main Wrapper -->
@endsection

@section('scripts')
    <script src="{{ asset('/js/rpclinica/agenda-listar.js') }}"></script>
@endsection
