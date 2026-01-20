    
       
            <div class="panel-body">
 
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active" class=""><a href="#tabCadModal" style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">Cadastro do Exame</a></li> 
                        <li role="presentation" class="" class=""><a href="#tabImgModal" style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">Imagens do Exame</a></li> 
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
                    
                                    <div class="col-sm-4" style="  padding-left: 5px;  ">
                                        <label for="input-Default" class="  control-label bold ">DP:</label>
                                        <input type="text" class="form-control input-sm text-right" name="dp" value="{{ old('dp') }}">
                                        @if($errors->has('dp'))
                                        <div class="error">{{ $errors->first('dp') }}</div>
                                        @endif
                                    </div>
                                </div>
        
                                <div class="form-group " style="margin-bottom: 5px;">
                                    <div class="col-sm-12" style=" ">
                                        <label for="input-help-block" class="control-label">Segmento:</label>
                                        <select class="form-control " name="cd_profissional" style="width: 100%">
                                            <option value="">SELECIONE</option>
                                        </select>
                                        @if($errors->has('cd_profissional'))
                                        <div class="error">{{ $errors->first('cd_profissional') }}</div>
                                        @endif
                                    </div>
                                </div>
                
                                <div class="col-sm-12" style=" ">
                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                        <label style="padding: 0">
                                            <div class="checker">
                                                <span>
                                                    <div class="checker"><span><input type="checkbox" name="normal_od"  ></span></div>
                                                </span>
                                            </div> Normal
                                        </label>
                                    </div> 
                                </div>
        
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <div class="col-md-12">
                                        <label for="input-placeholder" class=" control-label"
                                            style="  padding-top: 0px;">Biomicroscopia OD:</label>
                                        <textarea rows="4" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                                    </div>
                                </div>
                
                                <div class="col-sm-12" style=" ">
                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                        <label style="padding: 0">
                                            <div class="checker">
                                                <span>
                                                    <div class="checker"><span><input type="checkbox" name="normal_od"  ></span></div>
                                                </span>
                                            </div> Normal
                                        </label>
                                    </div> 
                                </div>
        
                                <div class="form-group" style="margin-bottom: 5px;">
                                    <div class="col-md-12">
                                        <label for="input-placeholder" class=" control-label"
                                            style="  padding-top: 0px;">Biomicroscopia OE:</label>
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
                    
                          
                
                            </form>
                            

                        </div>
                        <div role="tabpanel" class="tab-pane fade  " id="tabImgModal">
                            <form  >
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
                        
                        <div role="tabpanel" class="tab-pane fade  " id="tabLauModal">
                           #Laudo
                        </div>
                    </div> 
                </div>

            </div>