

<div class="col-md-7">

    <div class="panel panel-white ui-sortable-handle">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> {{ $formulario->nm_formulario }}
                    </h3>
                    <div class="panel-control">
                        <a href="javascript:void(0);" x-on:click="deleteAcuidade(1)" data-toggle="tooltip" data-placement="top" title=""
                        data-original-title="Exluir"><i class="icon-close"></i></a>
                        
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title=""
                            data-original-title="Imprimir"><i class="icon-printer"></i></a>


                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">

            <form class="form-horizontal" x-on:submit.prevent="storeAcuidade" id="form_ACUIDADE" method="post" >
                @csrf
                <div class="form-group " style="margin-bottom: 5px;">
                    <div class="col-sm-12" style=" ">
                        <label for="input-help-block" class="control-label">Profissional:</label>
                        <select class="form-control " name="cd_profissional">
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
                        <input type="datetime-local" style="height: 33px;"  class="form-control input-sm text-center" name="dt_exame"
                            value="{{ old('dt_exame') }}">
                        @if($errors->has('dt_exame'))
                        <div class="error">{{ $errors->first('dt_exame') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-4" style="padding-right: 5px; padding-left: 5px;">
                        <label for="input-Default" class="  control-label ">Data da Liberação:</label>
                        <input type="datetime-local" style="height: 33px;" class="form-control input-sm text-center" name="dt_liberacao"
                            value="{{ old('dt_liberacao') }}">
                        @if($errors->has('dt_liberacao'))
                        <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-4" style="  padding-left: 5px; ">
                        <label for="input-Default" class="  control-label bold ">Tipo de Lente:</label>
                        <select class="form-control " name="cd_profissional">
                            <option value="">Selecione</option>
                            <option value="OC">Lente de Óculos</option>
                            <option value="CO">Lente de Contato</option>
                            <option value="AM">Ambos</option>
                            <option value="NE">Nenhum</option>
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>
                </div>
                <label for="input-Default" class="control-label  " style="font-weight: 600;   text-align: center; ">Olho Direito:</label>
                <div class="form-group " style="margin-bottom: 5px;">
                    <div class="col-sm-3" style="padding-right: 5px;"  >
                        <label for="input-help-block" class="control-label">AV Longe:</label>
                        <select class="form-control " name="cd_profissional">
                            <option value="">Selecione</option>
                            <option value="20/15">20/15</option>
                            <option value="20/20">20/20</option>
                            <option value="20/25">20/25</option>
                            <option value="20/30">20/30</option>
                            <option value="20/40">20/40</option>
                            <option value="20/50">20/50</option>
                            <option value="20/60">20/60</option>
                            <option value="20/80">20/80</option>
                            <option value="20/100">20/100</option>
                            <option value="20/150">20/150</option>
                            <option value="20/300">20/300</option>
                            <option value="20/400">20/400</option>
                            <option value="CDa5M">CD a 5M</option>
                            <option value="CDa4M">CD a 4M</option>
                            <option value="CDa3M">CD a 3M</option>
                            <option value="CDa2M">CD a 2M</option>
                            <option value="CDa1M">CD a 1M</option>
                            <option value="CDa05">CD a 0,5</option>
                            <option value="MM">M M</option>
                            <option value="PL">PL</option>
                            <option value="SPL">SPL</option>
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px; " >
                        <label for="input-help-block" class="control-label">AV Perto:</label>
                        <select class="form-control " name="cd_profissional">
                            <option value="">Selecione</option>
                            <option value="J1">J1</option>
                            <option value="J2">J2</option>
                            <option value="J3">J3</option>
                            <option value="J4">J4</option>
                            <option value="J5">J5</option>
                            <option value="J6">J6</option>
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px; " >
                        <label for="input-help-block" class="control-label">AV Cores:</label>
                        <select class="form-control " name="cd_profissional">
                            <option value="">Selecione</option>
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-3" style="  padding-left: 5px; " >
                        <label for="input-help-block" class="control-label">AV Contraste:</label>
                        <select class="form-control " name="cd_profissional">
                            <option value="">Selecione</option>
                            <option value="20/20">20/20</option>
                            <option value="20/25">20/25</option>
                            <option value="20/30">20/30</option>
                            <option value="20/40">20/40</option>
                            <option value="20/50">20/50</option>
                            <option value="20/60">20/60</option>
                            <option value="20/80">20/80</option> 
                            <option value="20/150">20/150</option>
                            <option value="20/300">20/300</option>
                            <option value="20/400">20/400</option>
                            <option value="J1">J1</option>
                            <option value="J2">J2</option>
                            <option value="J3">J3</option>
                            <option value="J4">J4</option>
                            <option value="J5">J5</option>
                            <option value="J6">J6</option>
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>
                </div>
                <label for="input-Default" class="control-label  " style="font-weight: 600;   text-align: center; ">Olho Esquerdo:</label>
                <div class="form-group " style="margin-bottom: 5px;">
                    <div class="col-sm-3" style="padding-right: 5px;"  >
                        <label for="input-help-block" class="control-label">AV Longe:</label>
                        <select class="form-control " name="cd_profissional">
                            <option value="">Selecione</option>
                            <option value="20/15">20/15</option>
                            <option value="20/20">20/20</option>
                            <option value="20/25">20/25</option>
                            <option value="20/30">20/30</option>
                            <option value="20/40">20/40</option>
                            <option value="20/50">20/50</option>
                            <option value="20/60">20/60</option>
                            <option value="20/80">20/80</option>
                            <option value="20/100">20/100</option>
                            <option value="20/150">20/150</option>
                            <option value="20/300">20/300</option>
                            <option value="20/400">20/400</option>
                            <option value="CDa5M">CD a 5M</option>
                            <option value="CDa4M">CD a 4M</option>
                            <option value="CDa3M">CD a 3M</option>
                            <option value="CDa2M">CD a 2M</option>
                            <option value="CDa1M">CD a 1M</option>
                            <option value="CDa05">CD a 0,5</option>
                            <option value="MM">M M</option>
                            <option value="PL">PL</option>
                            <option value="SPL">SPL</option>
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px; " >
                        <label for="input-help-block" class="control-label">AV Perto:</label>
                        <select class="form-control " name="cd_profissional">
                            <option value="">Selecione</option>
                            <option value="J1">J1</option>
                            <option value="J2">J2</option>
                            <option value="J3">J3</option>
                            <option value="J4">J4</option>
                            <option value="J5">J5</option>
                            <option value="J6">J6</option>
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px; " >
                        <label for="input-help-block" class="control-label">AV Cores:</label>
                        <select class="form-control " name="cd_profissional">
                            <option value="">Selecione</option>
    
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-3" style="  padding-left: 5px; " >
                        <label for="input-help-block" class="control-label">AV Contraste:</label>
                        <select class="form-control " name="cd_profissional">
                            <option value="">Selecione</option>
                            <option value="20/20">20/20</option>
                            <option value="20/25">20/25</option>
                            <option value="20/30">20/30</option>
                            <option value="20/40">20/40</option>
                            <option value="20/50">20/50</option>
                            <option value="20/60">20/60</option>
                            <option value="20/80">20/80</option> 
                            <option value="20/150">20/150</option>
                            <option value="20/300">20/300</option>
                            <option value="20/400">20/400</option>
                            <option value="J1">J1</option>
                            <option value="J2">J2</option>
                            <option value="J3">J3</option>
                            <option value="J4">J4</option>
                            <option value="J5">J5</option>
                            <option value="J6">J6</option>
                        </select>
                        @if($errors->has('cd_profissional'))
                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                        @endif
                    </div>
                </div>


                <div class="form-group  " style="margin-bottom: 5px;  ">
                    <div class="col-md-12" >
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Comentário:</label>
                        <textarea rows="4" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                    </div>
                </div>

                <div class="panel-footer" style="">
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
                <h4 class="panel-title" x-on:click="modalAcuidade(1)" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px;   "> 
                        <i class="fa fa-list"  ></i>  25/08/2022 - Nome do Profissional 
                </h4>
            </div>
     
            <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; " >
                <h4 class="panel-title" x-on:click="modalAcuidade(1)" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px;   "> 
                        <i class="fa fa-list"  ></i>  15/05/2021 - Nome do Profissional 
                </h4>
            </div>
    
        </div>
     
    </div>

</div>