

<div class="col-md-7">
    
    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> {{ $formulario->nm_formulario }}
                    </h3>
                    <div class="panel-control">
                        <a href="javascript:void(0);" x-on:click="deleteTonometriaPneumatica(1)" data-toggle="tooltip" data-placement="top" title=""
                        data-original-title="Exluir"><i class="icon-close"></i></a>
                        
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Imprimir"><i class="icon-printer"></i></a>
 
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            
            <form class="form-horizontal" x-on:submit.prevent="storeTonometriaPneumatica" id="form_TONOMETRIA_PNEUMATICA" method="post" >
                @csrf
                <div class="form-group " style="margin-bottom: 5px;">
                    <div class="col-sm-12" style=" ">
                        <label for="input-help-block" class="control-label">Profissional:</label>
                        <select class="form-control " name="cd_profissional" style="width: 100%">
                            <option value="{{ $agendamento->cd_profissional }}">{{ $agendamento->profissional->nm_profissional }}</option>
                        </select>
                        
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

                <div class="form-group " style=" ">
                    <div class="col-sm-12" style=" ">
                        <label for="input-help-block" class="control-label">Equipamento:</label>
                        <select class="form-control " name="cd_profissional" style="width: 100%">
                            <option value="">SELECIONE</option>
                            @foreach ($equipamento as $val )
                                <option value="{{ $val->cd_equipamento }}">{{ $val->nm_equipamento }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>
                </div>
                

                <div class="form-group " style=" ">

                    <div class="col-sm-4" style=" padding-right: 5px;">
                        <label for="input-Default" class="  control-label ">OD (mmHg):</label>
                        <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
                            value="{{ old('dt_liberacao') }}">
                        @if($errors->has('dt_liberacao'))
                        <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-4" style="  padding-left: 5px; ">
                        <label for="input-Default" class="  control-label ">OE (mmHg):</label>
                        <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
                            value="{{ old('dt_liberacao') }}">
                        @if($errors->has('dt_liberacao'))
                        <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                        @endif
                    </div>
                </div>


                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Comentário:</label>
                        <textarea rows="4" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
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
        
    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title">Histórico</h3> 
                </div>
            </div> 
        </div>
    </div> 
     
    <div class="panel-group"   role="tablist" aria-multiselectable="true">
    
        <div class="panel panel-default" style="    border-radius: 0px;"> 


            <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; " >
                <h4 class="panel-title" x-on:click="modalTonometriaPneumatica(1)" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px;   "> 
                        <i class="fa fa-list"  ></i>  25/08/2022 - Nome do Profissional 
                </h4>
            </div>
     
            <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; " >
                <h4 class="panel-title" x-on:click="modalTonometriaPneumatica(1)" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px;   "> 
                        <i class="fa fa-list"  ></i>  15/05/2021 - Nome do Profissional 
                </h4>
            </div>
    
        </div>
     
    </div>

</div>