@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Feriados</h3>
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


                    <div class="panel-body">
                        <div role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-justified" role="tablist">
                                <li role="presentation" class="active"><a href="#tabManual" role="tab" data-toggle="tab">Cadastro Manual</a></li>
                                <li role="presentation"><a href="#tabAPI" role="tab" data-toggle="tab">Importação ( API )</a></li> 
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">

                                <div role="tabpanel" class="tab-pane active fade in" id="tabManual">
                                  
                                    <form role="form" action="{{ route('feriados.store') }}" method="post" role="form">
                                        @csrf
                                        <br><br>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group @if($errors->has('nm_feriado')) has-error @endif">  
                                                    <label>Nome do Feriado: <span class="red normal">*</span></label>
                                                    <input type="text"
                                                        class="form-control required"
                                                        required
                                                        value="{{ old('nm_feriado') }}"
                                                        name="nm_feriado"
                                                        maxlength="100"
                                                        aria-required="true">
                                                        @if($errors->has('nm_feriado'))
                                                            <div class="error">{{ $errors->first('nm_feriado') }}</div>
                                                        @endif 
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group @if($errors->has('dt_feriado')) has-error @endif">  
                                                    <label>Data do Feriado: <span class="red normal"></span></label>
                                                    <input type="date"
                                                        class="form-control required"
                                                        required
                                                        value="{{ old('dt_feriado') }}"
                                                        name="dt_feriado"
                                                        maxlength="10"
                                                        aria-required="true">
                                                        @if($errors->has('dt_feriado'))
                                                            <div class="error">{{ $errors->first('dt_feriado') }}</div>
                                                        @endif 
                                                </div>
                                            </div>
                 
                                            <div class="col-md-2">
                                                <div class="form-group @if($errors->has('tp_feriado')) has-error @endif">  
                                                        <label>Tipo do Feriado: <span class="red normal"></span></label>
                                                        <select class="form-control" required="" name="tp_feriado">
                                                            <option value="">Selecione</option>
                                                            <option value="FE" @if(old('tp_feriado')  == 'FE') selected @endif>Feriado</option>
                                                            <option value="FA" @if(old('tp_feriado')  == 'FA') selected @endif>Facultativo</option> 
                                                        </select>
                                                        @if($errors->has('tp_feriado'))
                                                            <div class="error">{{ $errors->first('tp_feriado') }}</div>
                                                        @endif 
                                                </div>
                                            </div>
                                   
                                            <div class="col-md-2">
                                                <div class="form-group @if($errors->has('nivel')) has-error @endif">  
                                                    <label>Nivel do Feriado: <span class="red normal"></span></label>
                                                        <select class="form-control" required="" name="nivel">
                                                            <option value="">Selecione</option>
                                                            <option value="NACIONAL" @if(old('nivel')  == 'NACIONAL') selected @endif>Nacional</option>
                                                            <option value="ESTADUAL" @if(old('nivel')  == 'ESTADUAL') selected @endif>Estadual</option> 
                                                        </select>
                                                        @if($errors->has('nivel'))
                                                            <div class="error">{{ $errors->first('nivel') }}</div>
                                                        @endif 
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <div class="form-group @if($errors->has('sn_bloqueado')) has-error @endif">  
                                                    <label>Bloqueia Agenda: <span class="red normal">*</span></label>
                                                    <select class="form-control" required="" name="sn_bloqueado">
                                                        <option value="">Selecione</option>
                                                        <option value="S" @if(old('sn_bloqueado')  == 'S') selected @endif>Sim</option>
                                                        <option value="N" @if(old('sn_bloqueado')  == 'N') selected @endif>Não</option> 
                                                    </select>
                                                        @if($errors->has('sn_bloqueado'))
                                                            <div class="error">{{ $errors->first('sn_bloqueado') }}</div>
                                                        @endif 
                                                </div>
                                            </div>
                                             
                                        </div>
                                        <br><br>
                                        <div class="box-footer">
                                            <input type="submit" class="btn btn-success" value="Salvar" />
                                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                                        </div>
                                    </form>
                                       
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tabAPI">
                                    
                                    <form role="form" action="{{ route('feriados.api') }}" method="post" role="form">
                                        @csrf
                                        <br><br>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group @if($errors->has('ano')) has-error @endif">  
                                                    <label>Ano do Feriado: <span class="red normal">*</span></label>
                                                    <input type="text"
                                                        class="form-control required"
                                                        required
                                                        value="{{ old('ano') }}"
                                                        name="ano"
                                                        maxlength="4"
                                                        aria-required="true">
                                                        @if($errors->has('ano'))
                                                            <div class="error">{{ $errors->first('ano') }}</div>
                                                        @endif 
                                                </div>
                                            </div>
                                        </div>
                                        <br><br>
                                        <div class="box-footer">
                                            <input type="submit" class="btn btn-success" value="Salvar" />
                                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                                        </div>
                                    </form>

                                </div>
                                
                                
                            </div>
                        </div>
                    </div>



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
