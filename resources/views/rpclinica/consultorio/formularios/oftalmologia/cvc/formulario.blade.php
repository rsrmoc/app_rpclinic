


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
        
        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active" class=""><a href="#tabCadExa" style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">Cadastro do Exame</a></li> 
                <li role="presentation" ><a href="#tabFotoExa" style="border-bottom:0px; margin-right: 0px;" class="OpTab" role="tab" data-toggle="tab" aria-expanded="true">Imagens do Exame</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">

                <div role="tabpanel" class="tab-pane fade  active in" id="tabCadExa">

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
  
                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">OD:</label>
                                <textarea rows="4" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                            </div>
                        </div>
         
                        <div class="form-group" style="margin-bottom: 5px;">
                            <div class="col-md-12">
                                <label for="input-placeholder" class=" control-label"
                                    style="  padding-top: 0px;">OE:</label>
                                <textarea rows="4" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
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

                <div role="tabpanel" class="tab-pane fade" id="tabFotoExa">
                    <form class="form-horizontal" method="post"  action="{{ route('auto.refracao.salve',$agendamento->cd_agendamento) }}">
                        @csrf
                        <div class="form-group " style="margin-bottom: 5px;">
                            <div class="col-sm-8" style="padding-right: 5px;">
                                <label for="input-help-block" class="control-label">Arquivo:</label>
                                <input type="file" class="form-control" name="dt_exame" >
                                @if($errors->has('cd_profissional'))
                                <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                @endif
                            </div>
                            <div class="col-sm-4"  >
                                <label for="input-help-block" class="control-label">&nbsp;</label>
                                <input type="submit" class="btn btn-success" style="width: 100%;" value="Salvar">
                            </div>
                                
                        </div>
          
                        <div class="panel panel-white">
                            <div class="panel-heading">
                                <div class="col-md-12">
                                    <h3 class="panel-title">[ ADMIN ] 25/08/2024 14:50 </h3>
                                    <div class="panel-control">
                                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title=""
                                            data-original-title="Exluir"><i class="icon-close"></i></a> 
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <img  src="{{ asset('assets/images/zoom/small/image2.jpg') }}"  style="width: 100%;">
                            </div>
                        </div>
        
                    </form>
                       
                </div>

              
            </div>
        </div>



    </div>
</div>

</div>

<div class="col-md-3">
    @include('rpclinica.consulta_formularios.historicos.geral',[ 'errors' => $errors ])
</div>