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
        <h3>ALteração de Atendimento (Simplificado)</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('atendimento') }}">Relação de Atendimentos</a></li>
            </ol>
        </div>
    </div>
    <div id="app" x-data="app">
        <div id="main-wrapper">
            <div class="col-md-12 ">
                <div class="panel panel-white">
                    <div class="panel-body">
                        <form role="form" action="{{ route('atendimento.update',$atendimento) }}" method="post" role="form">
                            @csrf
                            <div class="row">
 
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fname">Atendimento: <span class="red normal">*</span></label>
                                                                         
                                        <input type="text" class="form-control required"  
                                        name="atendimento" value="{{ old('atendimento',$atendimento->cd_agendamento) }}"
                                        readonly maxlength="100" aria-required="true"   required>
                                           
                                        @if($errors->has('atendimento'))
                                            <div class="error">{{ $errors->first('atendimento') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label>Paciente <span class="red normal">*</span></label>
                                    <div class="form-group">
                                        <select class="form-control m-b-sm" style="width: 100%"
                                            id="agendamento-paciente" name="cd_paciente" required>
                                                <option value="{{ $atendimento->cd_paciente }}">{{ $atendimento->paciente->nm_paciente }}</option>
                                        </select> 
                                    </div>
                                </div> 
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="fname">CPF: <span class="red normal">*</span></label>
                                        <input type="text" class="form-control required"  id="cpf" name="cpf"
                                        value="{{ old('cpf',$atendimento->paciente->cpf ) }}" maxlength="100" aria-required="true" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="fname">Data Nascimento: <span class="red normal"></span></label>
                                    <input type="date" class="form-control required" id="nasc" name="nasc"
                                    value="{{ old('nasc',$atendimento->paciente->dt_nasc) }}" maxlength="100" aria-required="true" >
                                </div>   
                                <div class="col-md-2">
                                    <label>Celular: <span class="red normal"></span></label>
                
                                     
                                    <div class="input-group m-b-sm">
                                        <input class="form-control" x-mask="(99) 99999-9999"  name="celular"  
                                        id="agendamento-celular" value="{{ old('celular', $atendimento->paciente->celular) }}" >
                                        <span class="input-group-addon" style=" padding: 2px 10px;" x-on:click="validarZap">
                                            <i aria-hidden="true" id="Zap" 
                                            style="margin-right: 0px; font-size: 24px; padding: 4px px 8px; cursor: pointer;" 
                                            x-bind:class=" classWhast"  ></i>
                                        </span>
                                    </div>
                                    <input type="hidden" name="SituacaoWhast" x-model="SituacaoWhast" >
                                    <input type="hidden" name="foneWhast" x-model="foneWhast" value="">

                                
                                </div>
                            </div>

                            <div class="row"> 
    
                                <div class="col-md-2"> 
                                    <div class="form-group">
                                        <label for="fname">Data Atendimento: <span class="red normal">*</span></label>                                     
                                            <input type="date" class="form-control required"   name="dt_atend" maxlength="10"
                                            value="{{ old('dt_atend',$atendimento->dt_agenda) }}"  aria-required="true" required> 
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="fname">Convênio: <span class="red normal">*</span></label>
                                        <select name="convenio"
                                            class="form-control"
                                            aria-invalid="false">
                                            <option value="">Selecione</option>
                                            @foreach ($convenio as $linha)
                                                <option value="{{ $linha->cd_convenio }}" @if(old('convenio',$atendimento->cd_convenio)==$linha->cd_convenio) selected @endif>{{ $linha->nm_convenio }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="fname">Prof.Executante: <span class="red normal">*</span></label>
                                        <select name="cd_profissional"
                                            class="form-control"
                                            aria-invalid="false">
                                            <option value="">Selecione</option>
                                            @foreach ($profissionais as $linha)
                                                <option value="{{ $linha->cd_profissional }}" 
                                                     @if(old('cd_profissional',$atendimento->cd_profissional)==$linha->cd_profissional) selected @endif>{{ $linha->nm_profissional }}</option>
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
                                            <label for="fname">Exames: <span class="red normal">*</span></label>
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
                                            <label for="fname">Olho: <span class="red normal">*</span></label>
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
                                            <label for="fname">Observação: <span class="red normal"></span></label>
                                            <input type="text" x-model="formExame.obs" class="form-control required"  >
                                        </div>
                                    </div>
     

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <button type="button" style="margin-top: 23px; width: 100%;"
                                            x-on:click="add" class="btn btn-default"><i class="fa fa-plus"></i>  </button>
                                        </div>
                                    </div>  
                                </div>

                                <div class="adicionados" id="tabela">
                                    @if(isset($atendimento->itens))
                                        @foreach($atendimento->itens as $idx => $exa)
                                            <div class="row" id="{{$exa->cd_agendamento_item}}">  
                                                <input type="hidden" name="cd_item[]" value="{{$exa->cd_agendamento_item}}">
                                                <div class="col-md-4 form-group ">
                                                    <input type="hidden" name="cd_exame[]" value="{{$exa->cd_exame}}"><span class="form-control">{{$exa->exame->nm_exame}}</span>
                                                </div>
                                                <div class="col-md-2 form-group ">
                                                    <input type="hidden" name="olho[]" value="{{$exa->olho}}"><span class="form-control">{{ ucfirst($exa->olho) }}</span>
                                                </div>
                                                <div class="col-md-5 form-group ">
                                                    <input type="hidden" name="obs[]" value="{{$exa->obs_exame}}"><span class="form-control">{{$exa->obs_exame}}</span>
                                                </div>
                                                <div class="botao col-md-1 ">
                                                    <button type="button" class="btn btn-default" style="color: #a94442; background-color: #f3f2f2;" x-on:click="remover('{{$exa->cd_agendamento_item}}')" ><i class="fa fa-close "></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div> 
                            </div>
                            {{ $errors->first('cd_exame') }}
                            <br><br> 
                            <div class="box-footer">
                                <input type="submit" class="btn btn-success" value="Salvar" />
                                <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>

        </div><!-- Main Wrapper -->
    </div><!-- app -->
    
@endsection

@section('scripts')

 <script>
    const profLogado = @js(auth()->guard('rpclinica')->user()->cd_profissional);
    const atendPaciente = @js($atendimento); 
    const listaExame = @js($listaExame);
 </script>
 <script src="{{ asset('js/rpclinica/atendimento-add.js') }}"></script>
 
@endsection
