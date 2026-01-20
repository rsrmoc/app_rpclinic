@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Agenda</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('agenda.listar') }}">Relação de Agendas</a></li>
            </ol>
        </div>
    </div>

 

    <div id="main-wrapper" x-data="app">
        <div class="col-md-12 "> 
            
            <div role="tabpanel"  >
                <!-- Nav tabs -->
             
                <!-- Tab panes -->
                <div class="tab-content"> 
 
                    <div role="tabpanel" class="tab-pane active fade in" id="tabAgenda">
                        <div class="panel-body">
                            @error('error')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <form role="form" id="formAgenda" action="{{ route('agenda.store') }}" method="post" role="form">
                                @csrf

                                <input type="submit" class="btn btn-success" style="display: none" />

                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Descrição da Agenda: <span class="red normal">*</span></label>
                                            <input type="text" class="form-control required" value="{{ old('descricao') }}"
                                                name="descricao" maxlength="100" aria-required="true" required>
                                        </div>
                                    </div> 

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tela Executante: <span class="red normal">*</span></label>
                                            <select class="form-control" required name="tela_exec" id="tela_exec"
                                            style="display: none; width: 100%">
                                            <option value="">...</option>
                                            <option value="CO" @if (old('tela_exec') == "CO") selected @endif>Consultório</option>
                                            <option value="CE" @if (old('tela_exec') == "CE") selected @endif>Central de Laudos</option>
                                            <option value="AM" @if (old('tela_exec') == "AM") selected @endif>Ambos</option>
                                          
                                        </select>
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Profissional:</label>
                                            <div class="input-group">
                                                <select class="form-control" name="profissional" id="profissionais"
                                                    style="display: none; width: 100%">
                                                    <option value="">Todos</option>
                                                    @foreach ($profissionais as $profissional)
                                                        <option value="{{ $profissional->cd_profissional }}">
                                                            {{ $profissional->nm_profissional }}</option>
                                                    @endforeach
                                                </select>

                                                <span class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                                    title="Não editável">
                                                    <div class="checker">
                                                        <span>
                                                            <input type="checkbox" aria-label="..." name="profissional-editavel"
                                                                value="1">
                                                        </span>
                                                    </div>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tipo de Atendimento:</label>
                                            <div class="input-group">
                                                <select class="form-control" name="tipo_atend" id="tipo_atend"
                                                    style="display: none; width: 100%">
                                                    <option value="">Todos</option>
                                                    @foreach ($tipos as $procedimento)
                                                        <option value="{{ $procedimento->cd_tipo_atendimento }}">{{ $procedimento->nm_tipo_atendimento }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                                    title="Não editável">
                                                    <div class="checker">
                                                        <span>
                                                            <input type="checkbox" aria-label="..." name="tipo_atendimento-editavel"
                                                                value="1">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
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
                                                        <option value="{{ $especialidade->cd_especialidade }}">
                                                            {{ $especialidade->nm_especialidade }}</option>
                                                    @endforeach
                                                </select>

                                                <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                                    title="Não editável">
                                                    <div class="checker">
                                                        <span>
                                                            <input type="checkbox" aria-label="..." name="especialidade-editavel"
                                                                value="1">
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
                                                        <option value="{{ $local->cd_local }}">{{ $local->nm_local }}</option>
                                                    @endforeach
                                                </select>

                                                <div class="input-group-addon" data-toggle="tooltip" data-placement="top"
                                                    title="Não editável">
                                                    <div class="checker">
                                                        <span>
                                                            <input type="checkbox" aria-label="..."
                                                                name="local_etendimento-editavel" value="1">
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
 
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Itens de Agendamento:</label> 
                                                <select class="form-control" name="cd_exame[]"  multiple="multiple" id="profissionais"
                                                    style="display: none; width: 100%">
                                                    <option value="">Todos</option>
                                                    @foreach ($exames as $exame)
                                                        <option value="{{ $exame->cd_exame }}">
                                                            {{ $exame->nm_exame }}</option>
                                                    @endforeach
                                                </select> 
                                        </div>
                                    </div>  
                                </div>

                                <div class="row" >
                                    <div class="col-md-12">
                                        <label>Observação da Agenda: <span class="red normal"></span></label>
                                        <textarea id="w3review" class="form-control required" name="observacao" rows="4" cols="50">{{ old('observacao') }}</textarea>
                                    </div>
                                </div>
                         
                                <div class="row" >
                                    <div class="col-md-12">
                                        <div class="form-group" style="margin-top: 10px;">
                                            <label>
                                                <input type="checkbox" aria-label="..."
                                                    name="sn_agenda_aberta" 
                                                    value="S" > Agenda de escala Aberta
                                            </label>
                                        </div>
                                    </div>
                                </div>

                             
                            </form>
                        </div>

                        <div class="panel-footer">
                            <input type="submit" class="btn btn-success" x-on:click="submitAgenda" value="Salvar" />
                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                        </div>
                    </div>
             

                </div>
            </div>
        </div>

    </div><!-- Main Wrapper -->

    <script src=" {{ asset('assets/js/jquery.min.js') }} "></script>
    <script> 
        $(document).ready(function () { 
            $('#agenda-procedimento').select2({
                ajax: {
                    url: '/rpclinica/json/search-procedimento',
                    dataType: 'json',
                    processResults: (data) => {
                        let search = $('#agenda-procedimento').data('select2').results.lastParams?.term;
        

                        return {
                            results: data
                        };
                    }
                }
            });
         
        });
        
    </script> 
@endsection

@section('scripts')
    <script>
        const especialidades = @js($especialidades);
        const procedimentos = @js($tipos);
        const profissionais = @js($profissionais);
        const locais = @js($locais);
        const convenios = @js($convenios);
    </script>
    <script src="{{ asset('js/rpclinica/agenda.js') }}"></script>
@endsection
