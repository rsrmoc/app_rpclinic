    
       
            <div class="panel-body">
 
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active" class=""><a href="#tabCadModal" style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">Cadastro do Exame</a></li> 
                        <li role="presentation" ><a href="#tabLauModal" style="border-bottom:0px; margin-right: 0px;" class="OpTab" role="tab" data-toggle="tab" aria-expanded="true">Laudo</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content" style="padding: 0px;">
                        <div role="tabpanel" class="tab-pane fade  active in" id="tabCadModal">

                                 
               
                            <form  >
                                @csrf
                                <div class="form-group " style="margin-bottom: 5px;">
                                    <div class="col-sm-12" style=" ">
                                        <label for="input-help-block" class="control-label">Profissional:</label>
                                        <input type="text" class="form-control input-sm text-center" name="dt_liberacao">
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
                                    <div class="col-sm-4" style=" ">
                                        <label for="input-help-block" class="control-label">Olho Dominante:</label>
                                        <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao">
                                        @if($errors->has('cd_profissional'))
                                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                        @endif
                                    </div>
                                </div>
                
                
                    
                                <div class="form-group " style=" ">
                
                                    <div class="col-sm-4" style=" padding-right: 5px;">
                                        <label for="input-Default" class="  control-label ">OD (mm):</label>
                                        <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
                                            value="{{ old('dt_liberacao') }}">
                                        @if($errors->has('dt_liberacao'))
                                        <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                                        @endif
                                    </div>
                
                                    <div class="col-sm-4" style="  padding-left: 5px; ">
                                        <label for="input-Default" class="  control-label ">OE (mm):</label>
                                        <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
                                            value="{{ old('dt_liberacao') }}">
                                        @if($errors->has('dt_liberacao'))
                                        <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-sm-4" style="  padding-left: 5px; ">
                                        <label for="input-Default" class="  control-label ">Tamanho corneano (mm):</label>
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
                 
                
                            </form>
 
                            
                        </div>
                        <div role="tabpanel" class="tab-pane fade  " id="tabLauModal">
                           #Laudo
                        </div>
                    </div> 
                </div>

            </div>