@extends('rpclinica.layout.layout')

@section('content')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            padding-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            border-right: 0px;
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
    </style>
    <div class="page-title">
        <h3>Cadastro de Atendimento (Simplificado)</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('atendimento') }}">Criação de Atendimento</a></li>
            </ol>
        </div>
    </div>
    <div id="app" x-data="app">
        <div id="main-wrapper">
            <div class="col-md-12 ">
                <div class="panel panel-white">
                    <div class="panel-body">
                        <form role="form" action="{{ route('atendimento.create') }}" method="post" role="form">
                            @csrf
                            @if (session()->has('msg'))
                                <div class="alert alert-success alert-dismissible" role="alert"> 
                                <span style="font-size: 17px; font-style: italic"> Atendimento cadastrado com sucesso! &nbsp {!!session('msg') !!}
                                </div> 
                            @endif
                            <div class="row">
                                @if ($empresa->atend_externo == 'S')
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="fname">Atendimento: <span class="red normal">*</span></label>

                                            <input type="text" class="form-control required"
                                                x-on:change="getAtendimento();" name="atendimento"
                                                value="{{ old('atendimento') }}" maxlength="100" aria-required="true"
                                                x-model="atendimento" required>

                                            @if ($errors->has('atendimento'))
                                                <div class="error">{{ $errors->first('atendimento') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-{{ $empresa->atend_externo == 'S' ? 4 : 6 }}">
                                    <label>Paciente <span class="red normal">*</span></label>
                                    <div class="form-group">
                                        <select class="form-control m-b-sm" style="width: 100%" id="agendamento-paciente"
                                            name="cd_paciente" style="width: 100%;" required>
                                            <option value="">Selecione</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fname">CPF: <span class="red normal">*</span></label>
                                        <input type="text" class="form-control required" id="cpf" name="cpf"
                                            value="{{ old('cpf') }}" maxlength="100" aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="fname">Data Nascimento: <span class="red normal"></span></label>
                                    <input type="date" class="form-control required" id="nasc" name="nasc"
                                        value="{{ old('nasc') }}" maxlength="100" aria-required="true">
                                </div>

                                <div class="col-md-2">
                                    <label>Celular: <span class="red normal"></span></label>

                                    <div class="input-group m-b-sm">
                                        <input class="form-control" x-mask="(99) 99999-9999" name="celular"
                                            id="agendamento-celular">
                                        <span class="input-group-addon" style=" padding: 2px 10px;" x-on:click="validarZap">
                                            <i aria-hidden="true" id="Zap"
                                                style="margin-right: 0px; font-size: 24px; padding: 4px px 8px; cursor: pointer;"
                                                x-bind:class="classWhast"></i>
                                        </span>
                                    </div>

                                    <input type="hidden" name="SituacaoWhast" x-model="SituacaoWhast" value="">
                                    <input type="hidden" name="foneWhast" x-model="foneWhast" value="">

                                </div>

                            </div>
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fname">Data Atendimento: <span class="red normal">*</span></label>
                                        <input type="date" class="form-control required" x-model="dt_atend"
                                            name="dt_atend" value="{{ old('dt_atend') }}" maxlength="100"
                                            aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="fname">Convênio: <span class="red normal">*</span></label>
                                        <select name="convenio" required class="form-control" aria-invalid="false" style="width: 100%;">
                                            <option value="">Selecione</option>
                                            @foreach ($convenio as $linha)
                                                <option value="{{ $linha->cd_convenio }}"
                                                    @if (old('cd_profissional') == $linha->cd_convenio) selected @endif>
                                                    {{ $linha->nm_convenio }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="fname">Prof.Executante: <span class="red normal">*</span></label>
                                        <select name="cd_profissional" class="form-control" 
                                        aria-invalid="false" style="width: 100%;" required>
                                            <option value="">Selecione</option>
                                            @foreach ($profissionais as $linha)
                                                <option value="{{ $linha->cd_profissional }}"
                                                    @if (old('cd_profissional') == $linha->cd_profissional) selected @endif>
                                                    {{ $linha->nm_profissional }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
 
                            </div>
                            <div class="timeline-options" style="margin-top: 10px; margin-bottom: 10px;">
                                <div class="panel-title">Itens do Atendimento</div>
                            </div>

                            <div id="itens_exame">
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fname" style="font-weight: bold;">Exames: <span class="red normal">*</span></label>
                                            <select name="cd_exame" id="cd_exame" style="width: 100%;" class="form-control"
                                                aria-invalid="false">
                                                <option value="">Selecione</option>
                                                @foreach ($exames as $linha)
                                                    <option value="{{ $linha->cd_exame }}"
                                                        @if (old('cd_exame') == $linha->cd_exame) selected @endif>
                                                        {{ $linha->nm_exame }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="fname" style="font-weight: bold;">Olho: <span class="red normal">*</span></label>
                                            <select name="olho" id="olho" class="form-control" style="width: 100%;"
                                                aria-invalid="false">
                                                <option value="">Selecione</option>
                                                <option value="ambos">Ambos</option>
                                                <option value="direito">Direito</option>
                                                <option value="esquerdo">Esquerdo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="fname" style="font-weight: bold;">Observação: <span class="red normal"></span></label>
                                            <input type="text"  x-model="formExame.obs" class="form-control required"  >
                                        </div>
                                    </div>
     

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button type="button" style="margin-top: 23px; width: 100%; background: #f1f1f1;border-color: #dce1e4;"
                                            x-on:click="add" class="btn btn-default"><i class="fa fa-plus"></i>  </button>
                                        </div>
                                    </div>  
                                </div>

                                <div class="adicionados" id="tabela"></div>

 

                            </div>
                            <span class="red">{{ $errors->first('cd_exame') }}</span>
                            <br><br> 
                            <div class="box-footer">
                                <input type="submit" class="btn btn-success" value="Salvar" />
                                <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" /> 
                            </div>
                        </form>
                        <template x-if="loading">
                            <div class="alert alert-danger alert-dismissible fade in" role="alert"
                                style="margin-top: 30px;">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                                <h4>Esse numero de Atendimento já encontrasse cadastrado em nossa base de dados!</h4>
                                <p style="margin-top: 0px;"><b>Atendimento:</b> <span x-text="atendimento"></span> </p>
                                <p style="margin-top: 0px;"><b>Data:</b> <span x-text="dt_atend"></span> </p>
                                <p style="margin-top: 0px;"><b>Paciente:</b> <span x-text="paciente"></span> </p>
                                <p style="margin-top: 0px;"><b>Data Nascimento:</b> <span x-text="nasc"></span> </p>
                                <p style="margin-top: 0px;"><b>CPF:</b> <span x-text="cpf"></span> </p>
                                <p>
                                    <a x-bind:href="'/rpclinica/atendimento-edit/' + atendimento"
                                        class="btn btn-default">Editar Atendimento</a>
                                </p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div><!-- Main Wrapper -->
    </div><!-- app -->

@endsection

@section('scripts')
    <script>
        const profLogado = @js(auth()->guard('rpclinica')->user()->cd_profissional); 
        const atendPaciente = @js('0'); 
        const listaExame = [];
        const dt_atual = @js(date('Y-m-d'));
    </script>
    <script src="{{ asset('js/rpclinica/atendimento-add.js') }}"></script>
@endsection
