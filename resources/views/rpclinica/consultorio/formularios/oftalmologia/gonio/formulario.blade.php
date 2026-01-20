

<div class="col-md-7">

<link href=" {{ asset('assets/css/custom.css') }} " rel="stylesheet" type="text/css" />
<div class="panel panel-white ui-sortable-handle">
    <div class="panel-heading">
        <div class="row">
            <div class="col-md-12">
                <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> {{ $formulario->nm_formulario }}
                </h3>
             
            </div>
        </div>
    </div>
    <div class="panel-body">
        
        <form class="form-horizontal" method="post"
        action="{{ route('auto.refracao.salve',$agendamento->cd_agendamento) }}">
            @csrf
            <div class="form-group " style="margin-bottom: 5px;">
                <div class="col-sm-12" style=" ">
                    <label for="input-help-block" class="control-label">Profissional:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group  " style="margin-bottom: 5px;">
                <div class="col-sm-4" style="padding-right: 5px;">
                    <label for="input-Default " class="  control-label   ">Data do Exame:</label>
                    <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame"
                        value="{{ old('dt_exame') }}">
                    @if($errors->has('dt_exame'))
                    <div class="error">{{ $errors->first('dt_exame') }}</div>
                    @endif
                </div>

                <div class="col-sm-4" style="  padding-left: 5px;">
                    <label for="input-Default" class="  control-label ">Data da Liberação:</label>
                    <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao"
                        value="{{ old('dt_liberacao') }}">
                    @if($errors->has('dt_liberacao'))
                    <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                    @endif
                </div> 
 
            </div>

       

            <div class="form-group  "
            style="margin-bottom: 5px; text-align: center;">
            <!-- <img src="gonio.jpg"> -->
            <div class="col-sm-12"
                style="padding-right: 5px; padding-left: 5px;">
                <div class="row">
                    <div class="form-content">
                        <div class="col-sm-6">
                            <label for="input-Default"
                                class="control-label">Olho
                                direito:</label>
                            <div class="bg-block">
                                <div class="row" style="padding: 7px;">
                                    <div class="col-sm-12">
                                        <select class="form-control"
                                            name="cd_profissional"
                                            style="width: 90px; margin-left: auto;margin-right: auto;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="padding: 14px;">
                                    <div class="col-sm-6"
                                        style="transform: translateX(-21px);">
                                        <select class="form-control"
                                            name="cd_profissional"
                                            style="width: 90px; margin-left: auto;margin-right: auto;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6"
                                        style="transform: translateX(21px);">
                                        <select class="form-control"
                                            name="cd_profissional"
                                            style="width: 90px; margin-left: auto;margin-right: auto;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="padding: 7px;">
                                    <div class="col-sm-12">
                                        <select class="form-control"
                                            name="cd_profissional"
                                            style="width: 90px; margin-left: auto;margin-right: auto;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="input-Default"
                                class="control-label">Olho
                                esquerdo:</label>
                            <div class="bg-block">
                                <div class="row" style="padding: 7px;">
                                    <div class="col-sm-12">
                                        <select class="form-control"
                                            name="cd_profissional"
                                            style="width: 90px; margin-left: auto;margin-right: auto;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="padding: 14px;">
                                    <div class="col-sm-6"
                                        style="transform: translateX(-21px);">
                                        <select class="form-control"
                                            name="cd_profissional"
                                            style="width: 90px; margin-left: auto;margin-right: auto;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6"
                                        style="transform: translateX(21px);">
                                        <select class="form-control"
                                            name="cd_profissional"
                                            style="width: 90px; margin-left: auto;margin-right: auto;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" style="padding: 7px;">
                                    <div class="col-sm-12">
                                        <select class="form-control"
                                            name="cd_profissional"
                                            style="width: 90px; margin-left: auto;margin-right: auto;">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Comentário:</label>
                        <textarea rows="6" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

  
  

            <div class="panel-footer">
                <input type="submit" class="btn btn-success" value="Salvar">
                <input type="reset" class="btn btn-default" value="Limpar">
            </div>

        </form>

    </div>
</div>

</div>

<div class="col-md-3">
    @include('rpclinica.consulta_formularios.historicos.geral',[ 'errors' => $errors ])
</div>