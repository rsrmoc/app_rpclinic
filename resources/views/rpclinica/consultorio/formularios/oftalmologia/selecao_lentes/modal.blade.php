    
       
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
                

                            <form > 
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
                                        <select class="form-control " name="cd_profissional" style="width: 100%">
                                            <option value="">SELECIONE</option>
                                        </select>
                                        @if($errors->has('cd_profissional'))
                                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-sm-4" style=" ">
                                        <label for="input-help-block" class="control-label">Grau Esférico:</label>
                                        <select class="form-control " name="cd_profissional" style="width: 100%">
                                            <option value="">SELECIONE</option>
                                        </select>
                                        @if($errors->has('cd_profissional'))
                                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-sm-4" style=" ">
                                        <label for="input-help-block" class="control-label">Grau Cilíndrico:</label>
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
                                        <label for="input-help-block" class="control-label">Grau Longe:</label>
                                        <select class="form-control " name="cd_profissional" style="width: 100%">
                                            <option value="">SELECIONE</option>
                                        </select>
                                        @if($errors->has('cd_profissional'))
                                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-sm-4" style=" ">
                                        <label for="input-help-block" class="control-label">Eixo:</label>
                                        <select class="form-control " name="cd_profissional" style="width: 100%">
                                            <option value="">SELECIONE</option>
                                        </select>
                                        @if($errors->has('cd_profissional'))
                                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-sm-4" style=" ">
                                        <label for="input-help-block" class="control-label">Adição:</label>
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