@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Tipo de Atendimento</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Cadastrar</a></li>
            </ol>
        </div>
    </div>



    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @error('error')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <form role="form" action="{{ route('tipo.atend.store') }}" method="post" role="form">
                        @csrf

                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="fname">Nome: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control  " required id="nm_tipo" value="{{ old('nm_tipo') }}"
                                        name="nm_tipo" maxlength="100" aria-required="true">
                                </div>
                            </div> 
                            <!--    
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Procedimento(s): <span class="red normal"></span></label>

                                    <select class="form-control"  multiple="multiple" tabindex="-1" style="display: none; width: 100%" name="procedimento[]" >
                                        <option value="">SELECIONE</option>
                                        @foreach ($procedimentos as $procedimento)
                                            <option value="{{ $procedimento->cd_proc }}">{{ $procedimento->nm_proc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            -->
                        </div>
                        <!--
                        <div class="row">
                            <div class="col-md-2  ">
                                <div class="m-b-sm form-group">

                                    <label class="m-r-sm">
                                        <div class="checker">
                                            <span id="check-agendamento-receb">
                                                <input type="checkbox" name="conta"  id="ate-conta"  value="S" />
                                            </span>
                                        </div> Gerar Conta de Faturamento
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="m-b-sm form-group">

                                    <label class="m-r-sm">
                                        <div class="checker">
                                            <span id="check-agendamento-receb">
                                                <input type="checkbox" name="consulta"  id="ate-consulta"  @if (old('consulta') == 'S') checked @endif value="S" />
                                            </span>
                                        </div> Atendimento de Consulta
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="m-b-sm form-group">

                                    <label class="m-r-sm">
                                        <div class="checker">
                                            <span id="check-agendamento-receb">
                                                <input type="checkbox" name="exame"  id="ate-exame"  @if (old('exame') == 'S') checked @endif value="S" />
                                            </span>
                                        </div> Atendimento de Exame
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="m-b-sm form-group">

                                    <label class="m-r-sm">
                                        <div class="checker">
                                            <span id="check-agendamento-receb">
                                                <input type="checkbox" name="cirurgia"  id="ate-cirurgia"  value="S" />
                                            </span>
                                        </div> Atendimento de Cirurgia
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="m-b-sm form-group">

                                    <label class="m-r-sm">
                                        <div class="checker">
                                            <span id="check-agendamento-receb">
                                                <input type="checkbox" name="retorno"  id="ate-retorno"  value="S" />
                                            </span>
                                        </div> Atendimento de Retorno
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="m-b-sm form-group">

                                    <label class="m-r-sm">
                                        <div class="checker">
                                            <span id="check-agendamento-receb">
                                                <input type="checkbox" name="telemedicina"  id="ate-telemedicina"  value="S" />
                                            </span>
                                        </div> Atendimento de Telemedicina
                                    </label>
                                </div>
                            </div>
                        </div>
                        -->

                        <div class="row" style="padding-bottom: 30px; padding-top: 20px;">

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-bg" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-bg') checked @endif   value="event-bg" >
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-am" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-am') checked @endif   value="event-am" >
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-ac" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-ac') checked @endif   value="event-ac" >
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-ae" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-ae') checked @endif   value="event-ae" >
                                    </label>
                                </div>
                            </div>


                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-lc" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-lc') checked @endif   value="event-lc" >
                                    </label>
                                </div>
                            </div>


                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-le" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-le') checked @endif   value="event-le" >
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-ll" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-ll') checked @endif   value="event-ll" >
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-rx" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-rx') checked @endif   value="event-rx" >
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-vc" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-vc') checked @endif   value="event-vc" >
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-ve" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-ve') checked @endif   value="event-ve" >
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-rs" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-rs') checked @endif   value="event-rs" >
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <div class="input-group m-b-sm">
                                    <label  class="event-vm" style="
                                    border: 1px solid; border-radius: 5px; padding: 10px; padding-bottom: 15px; padding-left: 15px;">
                                        <input type="radio" name="cor" @if(old('cor')=='event-vm') checked @endif   value="event-vm" >
                                    </label>
                                </div>
                            </div>

                        </div>


                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Salvar" />
                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!-- Main Wrapper -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('select').select2();
        });
    </script>
@endsection
