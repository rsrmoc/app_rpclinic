
<div class="col-md-7 col-sm-7 col-lg-6 col-xs-12  ">

    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> {{ $formulario->nm_formulario }}
                    </h3>
                    <div class="panel-control">
                        <a href="javascript:void(0);" data-toggle="tooltip"  x-on:click="deleteSelecaoLentes(1)"  data-placement="top" title=""
                            data-original-title="Exluir"><i class="icon-close"></i></a>
                            
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Imprimir"><i class="icon-printer"></i></a>
    
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            
            <div role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active" class=""><a href="#tabLentes" style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">Lentes</a></li> 
                    <li role="presentation" ><a href="#tabSimplificada" style="border-bottom:0px; margin-right: 0px;" class="OpTab" role="tab" data-toggle="tab" aria-expanded="true">Lente Simplificada</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">

                    <div role="tabpanel" class="tab-pane fade  active in" id="tabLentes">

            

                        <form class="form-horizontal" x-on:submit.prevent="storeSelecaoLentes" id="form_SELECAO_LENTES"  method="post" >
                            @csrf
                            <div class="form-group " style="margin-bottom: 5px;">
                                <div class="col-sm-6" style=" ">
                                    <label for="input-help-block" class="control-label">Lentes:</label>
                                    <select class="form-control " name="cd_profissional" style="width: 100%">
                                        <option value="">SELECIONE</option>
                                    </select>
                                    @if($errors->has('cd_profissional'))
                                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-6" style=" ">
                                    <label for="input-help-block" class="control-label">Olho:</label>
                                    <select class="form-control " name="cd_profissional" style="width: 100%">
                                        <option value="">SELECIONE</option>
                                    </select>
                                    @if($errors->has('cd_profissional'))
                                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group " style="margin-bottom: 5px;">
                                <div class="col-sm-4" style=" ">
                                    <label for="input-help-block" class="control-label">Grau:</label>
                                    <input type="text" class="form-control input-sm text-center" >
                                    @if($errors->has('cd_profissional'))
                                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-4" style=" ">
                                    <label for="input-help-block" class="control-label">Grau Esférico:</label>
                                    <input type="text" class="form-control input-sm text-center" >
                                    @if($errors->has('cd_profissional'))
                                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-4" style=" ">
                                    <label for="input-help-block" class="control-label">Grau Cilíndrico:</label>
                                    <input type="text" class="form-control input-sm text-center" >
                                    @if($errors->has('cd_profissional'))
                                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group " style="margin-bottom: 5px;">
                                <div class="col-sm-4" style=" ">
                                    <label for="input-help-block" class="control-label">Grau Longe:</label>
                                    <input type="text" class="form-control input-sm text-center" >
                                    @if($errors->has('cd_profissional'))
                                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-4" style=" ">
                                    <label for="input-help-block" class="control-label">Eixo:</label>
                                    <input type="text" class="form-control input-sm text-center" >
                                    @if($errors->has('cd_profissional'))
                                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                    @endif
                                </div>
                                <div class="col-sm-4" style=" ">
                                    <label for="input-help-block" class="control-label">Adição:</label>
                                    <input type="text" class="form-control input-sm text-center" >
                                    @if($errors->has('cd_profissional'))
                                    <div class="error">{{ $errors->first('cd_profissional') }}</div>
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



        </div>
    </div>

</div>

 
<div class="col-md-2 col-sm-2 col-xs-12 col-lg-3">
        
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
                <h4 class="panel-title" x-on:click="modalSelecaoLentes(1)" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px;   "> 
                        <i class="fa fa-list"  ></i>  25/08/2022 - Nome do Profissional 
                </h4>
            </div>
     
            <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; " >
                <h4 class="panel-title" x-on:click="modalSelecaoLentes(1)" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px;   "> 
                        <i class="fa fa-list"  ></i>  15/05/2021 - Nome do Profissional 
                </h4>
            </div>
    
        </div>
     
    </div>

</div>