    <div class="panel-body">
      <div role="tabpanel">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active" class=""><a href="#tabCadModal" style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">Cadastro do Exame</a></li>
          <li role="presentation" class="" class=""><a href="#tabImgModal" style="border-bottom:0px; margin-right: 0px;" role="tab" data-toggle="tab" aria-expanded="false">Imagens do Exame</a></li>

        </ul>
        <!-- Tab panes -->
        <div class="tab-content" style="padding: 0px;">

          <div role="tabpanel" class="tab-pane fade  active in" id="tabCadModal">
            @foreach($historico['form'] as $item)
            <fieldset disabled>
              <div class="panel panel-default" style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid #ddd !important; ">

              <div class="panel-heading" style="padding: 12px; height: 60px;">
                  <h3 class="panel-title"style="width: 100%;"> 
                      
                      <table width="100%">
                          <tr>
                            <td  width="50%" style="text-align: left"> 
                              <span style=" margin-right: 15px; font-style: italic" >{{($item->profissional?->nm_profissional) ? $item->profissional?->nm_profissional : ' -- '}} </span>
                              <br>
                              <span style=" margin-right: 15px;font-weight: 300;" >[ {{$item->cd_agendamento}} ] {{ ($item->dt_exame) ? date('m/d/Y', strtotime($item->dt_exame)) : ' -- ' }} </span>
                              
                            </td>
                            <td style="text-align: right"> 
                              <span style=" margin-right: 15px; font-style: italic" >Usuario: {{ ($item->nm_usuario) ? $item->nm_usuario : ' -- ' }}</span>
                              <br>
                          
                              <span style=" margin-right: 15px;font-weight: 300;" >{{   ($item->dt_cad_exame) ? date('m/d/Y', strtotime($item->dt_cad_exame)) : ' -- ' }} </span>
                              
                            </td>
                          </tr>
                        </table>
                  </h3>
              </div>

                <div class="panel-body" style="padding-top: 1.5em">
                  
                  <form>
                    <div class="form-group  " style="margin-bottom: 5px;">
                      <div class="col-sm-4" style="padding-right: 5px;">
                        <label for="input-Default " class="  control-label   ">Data do Exame:</label>
                        <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame" value="{{ $item->dt_exame }}">
                        @if ($errors->has('dt_exame'))
                        <div class="error">{{ $errors->first('dt_exame') }}</div>
                        @endif
                      </div>

                      <div class="col-sm-4" style="  padding-left: 5px;">
                        <label for="input-Default" class="  control-label ">Data da Liberação:</label>
                        <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao" value="{{ $item->dt_liberacao }}">
                        @if ($errors->has('dt_liberacao'))
                        <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group " style="margin-bottom: 5px;">
                      <div class="col-sm-6" style=" ">
                        <label style="padding: 0">
                          <input type="checkbox" name="midriase_od" @if($item->midriase_od=='1' ) checked @endif>
                          Midríase OD
                        </label>
                      </div>
                      <div class="col-sm-6" style=" ">
                        <label style="padding: 0">
                          <label style="padding: 0">
                            <input type="checkbox" name="midriase_od" @if($item->normal_od=='1' ) checked @endif>
                            Normal
                          </label>
                      </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Fundoscopia OD:</label>
                        <textarea rows="3" class="form-control" name="comentario">{{ $item->od }}</textarea>
                      </div>
                    </div>

                    <div class="form-group " style="margin-bottom: 5px;">
                      <div class="col-sm-6" style=" ">
                        <label style="padding: 0">
                          <input type="checkbox" name="midriase_od" @if($item->midriase_oe=='1' ) checked @endif>
                          Midríase OD
                        </label>
                      </div>
                      <div class="col-sm-6" style=" ">
                        <label style="padding: 0">
                          <label style="padding: 0">
                            <input type="checkbox" name="midriase_od" @if($item->normal_oe=='1' ) checked @endif>
                            Normal
                          </label>
                      </div>
                    </div>


                    <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Fundoscopia OE:</label>
                        <textarea rows="3" class="form-control" name="comentario" value="{{$item->oe}}">{{ $item->oe }}</textarea>
                      </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 5px;">
                      <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Comentário:</label>
                        <textarea rows="3" class="form-control" name="comentario">{{ $item->obs }}</textarea>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </fieldset>
            @endforeach
    </div>


    <div role="tabpanel" class="tab-pane fade  " id="tabImgModal">
    @if(isset($historico['array_img']))
      @foreach($historico['array_img'] as $item)
        <form>
          <div class="panel panel-white">
           

            <div class="panel-heading" style="padding: 12px; height: 60px;">
                <h3 class="panel-title"style="width: 100%;"> 
                    
                    <table width="100%">
                        <tr>
                          <td  width="50%" style="text-align: left"> 
                            <span style=" margin-right: 15px; font-style: italic" >{{($item['usuario']) ? $item['usuario'] : ' -- '}} </span>
                            <br>
                            <span style=" margin-right: 15px;font-weight: 300;" >[ {{$item['cd_agendamento']}} ] {{ ($item['data']) ? date('m/d/Y', strtotime($item['data'])) : ' -- ' }} </span>
                            
                          </td>
                           
                        </tr>
                      </table>
                </h3>
            </div>

            <div class="panel-body" style="text-align: center">
            <img x-bind:src="'{{$item['conteudo_img']}}'"  class="img-fluid" style="max-width: 100%;" >
            </div>
          </div>

        </form>
      @endforeach
    @endif

    </div>


    </div>
    </div>

    </div>
