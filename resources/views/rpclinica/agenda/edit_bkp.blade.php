@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Edição de Agenda</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('agenda.listar') }}">Relação de Agendas</a></li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper" x-data="app">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @error('error')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    @if ($agenda->agendamento_gerado)
                        <div class="alert alert-warning">
                            <i class="fa fa-info-circle"></i>&ensp;Agendamentos gerados
                        </div>
                    @endif

                    <form role="form" id="formAgenda"
                        action="{{ route('agenda.update', ['agenda' => $agenda->cd_agenda]) }}" method="post"
                        role="form">
                        @csrf

                        <input type="submit" class="btn btn-success" style="display: none" />

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Descrição da Agenda: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required" value="{{ $agenda->nm_agenda }}"
                                        name="descricao" maxlength="100" aria-required="true" required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Atend. Sus:</label>
                                    <div class="input-group m-b-sm">
                                        <span class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                            title="Atende Paciente SUS">
                                            <div class="checker">
                                                <span class="">
                                                    <input type="checkbox" name="sn_sus" aria-label="..." value="1"
                                                        @if ($agenda->sn_sus == 'S') checked @endif />
                                                </span>
                                            </div>
                                        </span>
                                        <input type="text" placeholder="Qtde. SUS" name="qtde_sus"
                                            class="form-control" aria-label="..." value="{{ $agenda->qtde_sus }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Atend. Particular:</label>
                                    <div class="input-group m-b-sm">
                                        <span class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                            title="Atende Paciente Particular">
                                            <div class="checker">
                                                <span class="">
                                                    <input type="checkbox" name="sn_particular" aria-label="..."
                                                        value="1" @if ($agenda->sn_particular == 'S') checked @endif />
                                                </span>
                                            </div>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Qtde. Paticular"
                                            name="qtde_particular" aria-label="..."
                                            value="{{ $agenda->qtde_particular }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Atende Convênios:</label>
                                    <div class="input-group m-b-sm">
                                        <span class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                            title="Atende Paciente de Convênios">
                                            <div class="checker">
                                                <span class="">
                                                    <input type="checkbox" name="sn_convenio" aria-label="..."
                                                        value="1" @if ($agenda->sn_convenio == 'S') checked @endif>
                                                </span>
                                            </div>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Qtde. Convênio"
                                            name="qtde_convenio" aria-label="..." value="{{ $agenda->qtde_convenio }}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Profissional:</label>
                                    <div class="input-group">
                                        <select class="form-control" name="profissional" id="profissionais"
                                            style="display: none; width: 100%">
                                            <option value="">Todos</option>
                                            @foreach ($profissionais as $profissional)
                                                <option value="{{ $profissional->cd_profissional }}"
                                                    @if ($agenda->cd_profissional == $profissional->cd_profissional) selected @endif>
                                                    {{ $profissional->nm_profissional }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <span class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                            title="Não editável">
                                            <div class="checker">
                                                <span>
                                                    <input type="checkbox" aria-label="..." name="profissional-editavel"
                                                        value="1" @if ($agenda->profissional_editavel) checked @endif>
                                                </span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Procedimentos:</label>
                                    <div class="input-group">
                                        <select class="form-control" name="procedimento" id="procedimentos"
                                            style="display: none; width: 100%">
                                            <option value="">Todos</option>
                                            @foreach ($procedimentos as $procedimento)
                                                <option value="{{ $procedimento->cd_proc }}"
                                                    @if ($agenda->cd_proc == $procedimento->cd_proc) selected @endif>
                                                    {{ $procedimento->nm_proc }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                            title="Não editável">
                                            <div class="checker">
                                                <span>
                                                    <input type="checkbox" aria-label="..." name="procedimento-editavel"
                                                        value="1" @if ($agenda->procedimento_editavel) checked @endif>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Qtde. Encaixe:</label>
                                    <select class="form-control" name="qtde_encaixe"
                                        style="display: none; width: 100%">
                                        <option value="">Qtde</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($agenda->qtde_encaixe == $i) selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Especialidade:</label>
                                    <div class="input-group">
                                        <select class="form-control" name="especialidade" id="especialidades"
                                            style="display: none; width: 100%">
                                            <option value="">Todos</option>
                                            @foreach ($especialidades as $especialidade)
                                                <option value="{{ $especialidade->cd_especialidade }}"
                                                    @if ($agenda->cd_especialidade == $especialidade->cd_especialidade) selected @endif>
                                                    {{ $especialidade->nm_especialidade }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                            title="Não editável">
                                            <div class="checker">
                                                <span>
                                                    <input type="checkbox" aria-label="..." name="especialidade-editavel"
                                                        value="1" @if ($agenda->especialidade_editavel) checked @endif>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Local de Atendimento:</label>
                                    <div class="input-group">
                                        <select class="form-control" name="local_atendimento" id="local_atendimento"
                                            style="display: none; width: 100%">
                                            <option value="">Todos</option>
                                            @foreach ($locais as $local)
                                                <option value="{{ $local->cd_local }}"
                                                    @if ($agenda->cd_local_atendimento == $local->cd_local) selected @endif>
                                                    {{ $local->nm_local }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                            title="Não editável">
                                            <div class="checker">
                                                <span>
                                                    <input type="checkbox" aria-label="..."
                                                        name="local_etendimento-editavel" value="1"
                                                        @if ($agenda->local_atendimento_editavel) checked @endif>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Sessão:</label>
                                    <div class="input-group">
                                        <select class="form-control" name="qtde_sessao"
                                            style="display: none; width: 100%">
                                            <option value="">Qtde</option>
                                            @for ($i = 1; $i <= 50; $i++)
                                                <option value="{{ $i }}"
                                                    @if ($agenda->qtde_sessao == $i) selected @endif>{{ $i }}
                                                </option>
                                            @endfor
                                        </select>

                                        <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                            title="Agenda de Sessões?">
                                            <div class="checker">
                                                <span>
                                                    <input type="checkbox" aria-label="..." name="sn_sessao"
                                                        value="1" @if ($agenda->sn_sessao == 'S') checked @endif>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tipo de Agendamento:  <span class="red normal">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name="tipo_agendamento"
                                            style="display: none; width: 100%">
                                            <option value="">...</option>
                                            <option value="ESCALA"  @if (old('tipo_agendamento',$agenda->tipo_agendamento) == 'ESCALA') selected @endif>Escala</option>
                                            <option value="MANUAL"  @if (old('tipo_agendamento',$agenda->tipo_agendamento) == 'MANUAL') selected @endif>Manual</option> 
                                        
                                        </select>

                                        <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                            title="Permite agendamento Manual?">
                                            <div class="checker">
                                                <span>
                                                    <input type="checkbox" aria-label="..." name="sn_agenda_manual"
                                                        value="SIM" @if (old('sn_agenda_manual',$agenda->sn_agenda_manual) == 'SIM') checked @endif>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row" style="margin-bottom: 10px">
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="semana[]" value="segunda" class="flat-red"
                                            @if ($agenda->segunda) checked @endif
                                            @if ($agenda->agendamento_gerado) disabled @endif>
                                        Seg.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="semana[]" value="terca" class="flat-red"
                                            @if ($agenda->terca) checked @endif
                                            @if ($agenda->agendamento_gerado) disabled @endif>
                                        Ter.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="semana[]" value="quarta" class="flat-red"
                                            @if ($agenda->quarta) checked @endif
                                            @if ($agenda->agendamento_gerado) disabled @endif>
                                        Qua.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="semana[]" value="quinta" class="flat-red"
                                            @if ($agenda->quinta) checked @endif
                                            @if ($agenda->agendamento_gerado) disabled @endif>
                                        Qui.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="semana[]" value="sexta" class="flat-red"
                                            @if ($agenda->sexta) checked @endif
                                            @if ($agenda->agendamento_gerado) disabled @endif>
                                        Sex.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="semana[]" value="sabado" class="flat-red"
                                            @if ($agenda->sabado) checked @endif
                                            @if ($agenda->agendamento_gerado) disabled @endif>
                                        Sab.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="semana[]" value="domingo" class="flat-red"
                                            @if ($agenda->domingo) checked @endif
                                            @if ($agenda->agendamento_gerado) disabled @endif>
                                        Dom.
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Data Inicial: <span class="red normal">*</span></label>
                                    <input type="date" class="form-control required"
                                        value="{{ $agenda->data_inicial }}" name="data_inicial" maxlength="100"
                                        aria-required="true" required
                                        @if ($agenda->agendamento_gerado) disabled @endif>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Data Final: <span class="red normal">*</span></label>
                                    <input type="date" class="form-control required"
                                        value="{{ $agenda->data_final }}" name="data_final" maxlength="100"
                                        aria-required="true" required
                                        @if ($agenda->agendamento_gerado) disabled @endif>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Horário Inicial: <span class="red normal">*</span></label>
                                    <input type="time" class="form-control required"
                                        value="{{ substr($agenda->hr_inicial, 0, 5) }}" name="hora_inicial"
                                        maxlength="100" aria-required="true" required
                                        @if ($agenda->agendamento_gerado) disabled @endif>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Horário Final: <span class="red normal">*</span></label>
                                    <input type="time" class="form-control required"
                                        value="{{ substr($agenda->hr_final, 0, 5) }}" name="hora_final" maxlength="100"
                                        aria-required="true" required
                                        @if ($agenda->agendamento_gerado) disabled @endif>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Intervalo: <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="intervalo" @if ($agenda->agendamento_gerado) disabled @endif>
                                        <option value="">SELECIONE</option>
                                        @foreach ($intervalos as $intervalo)
                                            <option value="{{ $intervalo->cd_intervalo }}"
                                                @if (old('intervalo', $agenda->intervalo) == $intervalo->cd_intervalo) selected @endif>
                                                {{ $intervalo->mn_intervalo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tipo de Agenda: <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="tipo_agenda" @if ($agenda->agendamento_gerado) disabled @endif>
                                        <option value="">Selecione</option>
                                        <option value="consulta" @if ($agenda->tipo_agenda == 'consulta') selected @endif>
                                            Consulta</option>
                                        <option value="retorno" @if ($agenda->tipo_agenda == 'retorno') selected @endif>Retorno
                                        </option>
                                        <option value="encaixe" @if ($agenda->tipo_agenda == 'encaixe') selected @endif>Encaixe
                                        </option>
                                        <option value="exame" @if ($agenda->tipo_agenda == 'exame') selected @endif>Exame
                                        </option>
                                        <option value="terapia" @if ($agenda->tipo_agenda == 'terapia') selected @endif>Terapia
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div role="tabpanel" style="margin-top: 30px;">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li role="presentation" class="active"><a href="#tabEspecialidades" role="tab"
                                        data-toggle="tab">Especialidades</a></li>
                                <li role="presentation"><a href="#tabProcedimentos" role="tab"
                                        data-toggle="tab">Procedimentos</a></li>
                                <li role="presentation"><a href="#tabProfissional" role="tab"
                                        data-toggle="tab">Profissional</a></li>
                                <li role="presentation"><a href="#tabLocalAtendimento" role="tab"
                                        data-toggle="tab">Local de Atendimento</a></li>
                                <li role="presentation"><a href="#tabConvenios" role="tab"
                                        data-toggle="tab">Convênios</a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active fade in" id="tabEspecialidades">
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col-md-9 col-md-offset-1">
                                            <div class="form-group">
                                                <select class="form-control" style="width: 100%"
                                                    id="agenda-especialidade">
                                                    <option value="">Selecione a Especialidade</option>

                                                    @foreach ($especialidades as $especialidade)
                                                        <option value="{{ $especialidade->cd_especialidade }}">
                                                            {{ $especialidade->nm_especialidade }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-success col-md-1"
                                            x-on:click="addEspecialidade">Adicionar</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="table-responsive table-striped">
                                                <table class="display table dataTable table-striped">
                                                    <thead>
                                                        <tr class="active">
                                                            <th>#</th>
                                                            <th>Especialidade</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <template x-for="especialidade, indice in agendaEspecialidades">
                                                            <tr>
                                                                <td x-text="especialidade.cd_especialidade"></td>

                                                                <template x-if="loadingDelEspecialidade == indice">
                                                                    <td>
                                                                        <x-loading message="Excluindo especialidade..." />
                                                                    </td>
                                                                </template>

                                                                <template x-if="loadingDelEspecialidade != indice">
                                                                    <td x-text="especialidade.nm_especialidade"></td>
                                                                </template>

                                                                <td class="text-center">
                                                                    <div class="btn-group">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger"
                                                                            x-on:click="deleteEspecialidade(indice)">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </template>

                                                        <template x-if="loadingEspecialidade">
                                                            <tr>
                                                                <td colspan="3">
                                                                    <x-loading message="Adicionando especialidade..." />
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>

                                                <template x-if="agendaEspecialidades.length == 0">
                                                    <p class="text-center">Nenhuma especialidade</p>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tabProcedimentos">
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col-md-9 col-md-offset-1">
                                            <div class="form-group">
                                                <select class="form-control" style="width: 100%"
                                                    id="agenda-procedimento">
                                                    <option value="">Selecione o Procedimento</option>

                                                    @foreach ($procedimentos as $procedimento)
                                                        <option value="{{ $procedimento->cd_proc }}">
                                                            {{ $procedimento->nm_proc }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success col-md-1"
                                            x-on:click="addProcedimento">Adicionar</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="table-responsive">
                                                <table class="display table dataTable table-striped">
                                                    <thead>
                                                        <tr class="active">
                                                            <th>#</th>
                                                            <th>Procedimento</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template x-for="procedimento, indice in agendaProcedimentos">
                                                            <tr>
                                                                <td x-text="procedimento.cd_proc"></td>

                                                                <template x-if="loadingDelProcedimento == indice">
                                                                    <td>
                                                                        <x-loading message="Excluindo procedimento..." />
                                                                    </td>
                                                                </template>

                                                                <template x-if="loadingDelProcedimento != indice">
                                                                    <td x-text="procedimento.nm_proc"></td>
                                                                </template>

                                                                <td class="text-center">
                                                                    <div class="btn-group">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger"
                                                                            x-on:click="deleteProcedimento(indice)">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </template>

                                                        <template x-if="loadingProcedimento">
                                                            <tr>
                                                                <td colspan="3">
                                                                    <x-loading message="Adicionando procedimento..." />
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>

                                                <template x-if="agendaProcedimentos.length == 0">
                                                    <p class="text-center">Nenhum procedimento</p>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tabProfissional">
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col-md-9 col-md-offset-1">
                                            <div class="form-group">
                                                <select class="form-control" style="width: 100%"
                                                    id="agenda-profissional">
                                                    <option value="">Selecione o Profissional</option>

                                                    @foreach ($profissionais as $profissional)
                                                        <option value="{{ $profissional->cd_profissional }}">
                                                            {{ $profissional->nm_profissional }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-success col-md-1"
                                            x-on:click="addProfissional">Adicionar</button>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="table-responsive">
                                                <table class="display table dataTable table-striped">
                                                    <thead>
                                                        <tr class="active">
                                                            <th>#</th>
                                                            <th>Profissional</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <template x-for="profissional, indice in agendaProfissionais">
                                                            <tr>
                                                                <td x-text="profissional.cd_profissional"></td>

                                                                <template x-if="loadingDelProfissional == indice">
                                                                    <td>
                                                                        <x-loading message="Excluindo o profissional..." />
                                                                    </td>
                                                                </template>

                                                                <template x-if="loadingDelProfissional != indice">
                                                                    <td x-text="profissional.nm_profissional"></td>
                                                                </template>

                                                                <td class="text-center">
                                                                    <div class="btn-group">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger"
                                                                            x-on:click="deleteProfissional(indice)">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </template>

                                                        <template x-if="loadingProfissional">
                                                            <tr>
                                                                <td colspan="3">
                                                                    <x-loading message="Adicionando profissional..." />
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>

                                                <template x-if="agendaProfissionais.length == 0">
                                                    <p class="text-center">Nenhum profissional</p>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tabLocalAtendimento">
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col-md-9 col-md-offset-1">
                                            <div class="form-group">
                                                <select class="form-control" style="width: 100%" id="agenda-local">
                                                    <option value="">Selecione o Local de Atendimento</option>

                                                    @foreach ($locais as $local)
                                                        <option value="{{ $local->cd_local }}">{{ $local->nm_local }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success col-md-1"
                                            x-on:click="addLocal">Adicionar</button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="table-responsive">
                                                <table class="display table dataTable table-striped">
                                                    <thead>
                                                        <tr class="active">
                                                            <th>#</th>
                                                            <th>Local de Atendimento</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <template x-for="local, indice in agendaLocais">
                                                            <tr>
                                                                <td x-text="local.cd_local"></td>

                                                                <template x-if="loadingDelLocal == indice">
                                                                    <td>
                                                                        <x-loading message="Excluindo local..." />
                                                                    </td>
                                                                </template>

                                                                <template x-if="loadingDelLocal != indice">
                                                                    <td x-text="local.nm_local"></td>
                                                                </template>

                                                                <td class="text-center">
                                                                    <div class="btn-group">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger"
                                                                            x-on:click="deleteLocal(indice)">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </template>

                                                        <template x-if="loadingLocal">
                                                            <tr>
                                                                <td colspan="3">
                                                                    <x-loading message="Adicionando local..." />
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>

                                                <template x-if="agendaLocais.length == 0">
                                                    <p class="text-center">Nenhum local</p>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div role="tabpanel" class="tab-pane fade" id="tabConvenios">
                                    <div class="row" style="margin-top: 30px;">
                                        <div class="col-md-9 col-md-offset-1">
                                            <div class="form-group">
                                                <select class="form-control" style="width: 100%" id="agenda-convenio">
                                                    <option value="">Selecione o Convênio</option>

                                                    @foreach ($convenios as $convenio)
                                                        <option value="{{ $convenio->cd_convenio }}">
                                                            {{ $convenio->nm_convenio }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success col-md-1"
                                            x-on:click="addConvenio">Adicionar</button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-10 col-md-offset-1">
                                            <div class="table-responsive">
                                                <table class="display table dataTable table-striped table-striped">
                                                    <thead>
                                                        <tr class="active">
                                                            <th>#</th>
                                                            <th>Convênios</th>
                                                            <th class="text-center">Ação</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <template x-for="convenio, indice in agendaConvenios">
                                                            <tr>
                                                                <td x-text="convenio.cd_convenio"></td>

                                                                <template x-if="loadingDelConvenio == indice">
                                                                    <td>
                                                                        <x-loading message="Excluindo convenio..." />
                                                                    </td>
                                                                </template>

                                                                <template x-if="loadingDelConvenio != indice">
                                                                    <td x-text="convenio.nm_convenio"></td>
                                                                </template>

                                                                <td class="text-center">
                                                                    <div class="btn-group">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger"
                                                                            x-on:click="deleteConvenio(indice)">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </template>

                                                        <template x-if="loadingConvenio">
                                                            <tr>
                                                                <td colspan="3">
                                                                    <x-loading message="Adicionando convenio..." />
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>

                                                <template x-if="agendaConvenios.length == 0">
                                                    <p class="text-center">Nenhum convenio</p>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 50px;">
                            <div class="col-md-12">
                                <label>Observação da Agenda: <span class="red normal"></span></label>
                                <textarea id="w3review" class="form-control required" name="observacao" rows="4" cols="50">{{ $agenda->obs }}</textarea>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="panel-footer">
                    <input type="submit" class="btn btn-success" value="Salvar" x-on:click="submitAgenda" />
                    <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                </div>
            </div>
        </div>

    </div><!-- Main Wrapper -->
@endsection

@section('scripts')
    <script>
        const especialidades = @js($especialidades);
        const procedimentos = @js($procedimentos);
        const profissionais = @js($profissionais);
        const locais = @js($locais);
        const convenios = @js($convenios);
        const agenda = @js($agenda);
    </script>
    <script src="{{ asset('js/rpclinica/agenda.js') }}"></script>
@endsection
