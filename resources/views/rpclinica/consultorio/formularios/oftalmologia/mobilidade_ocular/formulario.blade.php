

<div class="col-md-7">
    
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
            <div class="form-group " style=" ">
                <div class="col-sm-12" style="  ">
                    <label for="input-help-block" class="control-label">Profissional:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group  " >
                <div class="col-sm-4"  >
                    <label for="input-Default " class="  control-label   ">Data do Exame:</label>
                    <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame" style="height: 34px;">
                    @if($errors->has('dt_exame'))
                    <div class="error">{{ $errors->first('dt_exame') }}</div>
                    @endif
                </div>

                <div class="col-sm-4" >
                    <label for="input-Default" class="  control-label ">Data da Liberação:</label>
                    <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao" style="height: 34px;">
                    @if($errors->has('dt_liberacao'))
                    <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                    @endif
                </div> 
 
                <div class="col-sm-4"  >
                    <label for="input-help-block" class="control-label">Exame:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                  
            </div>
 
            <div class="form-group  " > 
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label"> Perto (SC):</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label">Longe (SC):</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label">Perto Infra (SC):</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
            </div>
 
            <div class="form-group  " > 
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label"> Perto (CC):</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label">Longe (CC):</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label">Perto Infra (CC):</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group  " > 
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label"> Teste de 4 dioptrias:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label">Fixação binocular:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label">Preferência:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
            </div>



            <div class="row">
                <div class="form-content">
                    <div class="col-sm-6">
                        <label for="input-Default"
                            class="control-label">Rotações
                            binoculares:</label>
                        <!-- <div class="bg-block-oldgame-plus"></div> -->
                        <div class="bg-block-bin">
                            <div class="row" style="">
                                <div class="col-sm-12"
                                    style="padding: 0; display: flex;justify-content: center;">
                                    <div class="col-sm-4">
                                        <select class="form-control "
                                            name="cd_profissional">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-4">
                                        <select class="form-control "
                                            name="cd_profissional">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row"
                                style="padding-top: 42px;padding-bottom: 42px;">
                                <div class="col-sm-12"
                                    style="padding: 0; display: flex;justify-content: center;">
                                    <div class="col-sm-4">
                                        <select class="form-control "
                                            name="cd_profissional">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-4">
                                        <select class="form-control "
                                            name="cd_profissional">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col-sm-12"
                                    style="padding: 0; display: flex;justify-content: center;">
                                    <div class="col-sm-4">
                                        <select class="form-control "
                                            name="cd_profissional">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-4">
                                        <select class="form-control "
                                            name="cd_profissional">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="input-Default" class="control-label"
                            style="padding-top: 22.5px;"></label>
                        <!-- <div class="bg-block-oldgame-plus"></div> -->
                        <div class="bg-block-bin">
                            <div class="row" style="">
                                <div class="col-sm-12"
                                    style="padding: 0; display: flex;justify-content: center;">
                                    <div class="col-sm-12"
                                        style="padding: 0; display: flex;justify-content: center;">
                                        <div class="col-sm-4">
                                            <select
                                                class="form-control "
                                                name="cd_profissional">
                                                <option value="">
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4">
                                            <select
                                                class="form-control "
                                                name="cd_profissional">
                                                <option value="">
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row"
                                style="padding-top: 42px;padding-bottom: 42px;">
                                <div class="col-sm-12"
                                    style="padding: 0; display: flex;justify-content: center;">
                                    <div class="col-sm-12"
                                        style="padding: 0; display: flex;justify-content: center;">
                                        <div class="col-sm-4">
                                            <select
                                                class="form-control "
                                                name="cd_profissional">
                                                <option value="">
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4">
                                            <select
                                                class="form-control "
                                                name="cd_profissional">
                                                <option value="">
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col-sm-12"
                                    style="padding: 0; display: flex;justify-content: center;">
                                    <div class="col-sm-12"
                                        style="padding: 0; display: flex;justify-content: center;">
                                        <div class="col-sm-4">
                                            <select
                                                class="form-control "
                                                name="cd_profissional">
                                                <option value="">
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-4">
                                            <select
                                                class="form-control "
                                                name="cd_profissional">
                                                <option value="">
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">

                <div class="form-content">
                    <div class="col-sm-6">
                        <label for="input-Default" style="margin-top: 10px;"  class="control-label">Outras  Medidas:</label>
                         
                        <div class="bg-block-others">
                            <div class="row" style="">
                                <div class="col-sm-12"
                                    style="padding: 0; display: flex;justify-content: center;">
                                    <input type="text"
                                        class="form-control"
                                        name="cd_profissional"
                                        style="width: 70px;height: 35px;"
                                        value="">

                                    <input type="text"
                                        class="form-control"
                                        name="cd_profissional"
                                        style="width: 70px;height: 35px;"
                                        value="">

                                    <input type="text"
                                        class="form-control"
                                        name="cd_profissional"
                                        style="width: 70px;height: 35px;"
                                        value="">
                                </div>
                            </div>
                            <div class="row"
                                style="padding-top: 42px;padding-bottom: 42px;">
                                <div class="col-sm-12"
                                    style="padding: 0; display: flex;justify-content: center;">
                                    <input type="text"
                                        class="form-control"
                                        name="cd_profissional"
                                        style="width: 70px;height: 35px;"
                                        value="">

                                    <input type="text"
                                        class="form-control"
                                        name="cd_profissional"
                                        style="width: 70px;height: 35px;"
                                        value="">

                                    <input type="text"
                                        class="form-control"
                                        name="cd_profissional"
                                        style="width: 70px;height: 35px;"
                                        value="">
                                </div>
                            </div>
                            <div class="row" style="">
                                <div class="col-sm-12"
                                    style="padding: 0; display: flex;justify-content: center;">
                                    <input type="text"
                                        class="form-control"
                                        name="cd_profissional"
                                        style="width: 70px;height: 35px;"
                                        value="">
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>

            <div class="form-group  " > 
                <div class="col-sm-6" style=" ">
                    <label for="input-help-block" class="control-label"> Teste de 4 dioptrias:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                <div class="col-sm-6" style=" ">
                    <label for="input-help-block" class="control-label">Fixação binocular:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div> 
            </div>

            <div class="form-group  " >  
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label">Circulos:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label">Bichos:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
                <div class="col-sm-4" style=" ">
                    <label for="input-help-block" class="control-label">Teste de Cores:</label>
                    <select class="form-control " name="cd_profissional" style="width: 100%">
                        <option value="">SELECIONE</option>
                    </select>
                    @if($errors->has('cd_profissional'))
                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;">
                <div class="col-md-12">
                    <label for="input-placeholder" class=" control-label"
                        style="  padding-top: 0px;">Comentário:</label>
                    <textarea rows="6" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;">
                <div class="col-md-12">
                    <label for="input-placeholder" class=" control-label"
                        style="  padding-top: 0px;">Conduta:</label>
                    <textarea rows="6" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
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