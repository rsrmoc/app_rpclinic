@foreach($historico as $item)
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
        <div class="form-group row " style="margin-bottom: 5px;">
          <div class="col-sm-4" style="padding-right: 5px;">
            <label for="input-Default " class="  control-label   ">Data do Exame:</label>
            <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame" value="{{$item->dt_exame}}">
            @if($errors->has('dt_exame'))
            <div class="error">{{ $errors->first('dt_exame') }}</div>
            @endif
          </div>

          <div class="col-sm-4" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-Default" class="  control-label ">Data da Liberação:</label>
            <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao" value="{{ $item->dt_liberacao }}">
            @if($errors->has('dt_liberacao'))
            <div class="error">{{ $errors->first('dt_liberacao') }}</div>
            @endif
          </div>

          <div class="col-sm-4" style="  padding-left: 5px;">
            <label for="input-Default" class="  control-label bold ">DP:</label>
            <input type="text" class="form-control input-sm text-right" name="dp" value="{{ $item->dp }}">
            @if($errors->has('dp'))
            <div class="error">{{ $errors->first('dp') }}</div>
            @endif
          </div>
        </div>

        <div class="row" style="margin-bottom: 5px;">
          <div class="col-sm-6">
            <h5>Auto Refração Dinâmica:</h5>
          </div>
          <div class="col-sm-6"><label style="margin-top: 5px; display: flex">
              <div class=""><span style="margin-right:5px;"><input type="checkbox" name="receita_dinamica" @if($item->rd_receita=='1' ) checked @endif></span></div> Receita
            </label>
            @if($errors->has('receita_dinamica'))
            <div class="error">{{ $errors->first('receita_dinamica') }}</div>
            @endif
          </div>
        </div>

        <div class="form-group row" style="margin-bottom: 5px;">
          <div class="col-sm-2" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OD
              DE</label>
            <input type="text" class="form-control input-sm " name="od_de_dinamica" value="{{ $item->ard_od_de }}">
            @if($errors->has('od_de_dinamica'))
            <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm " name="od_dc_dinamica" value="{{ $item->ard_od_dc }}"">
            @if($errors->has('od_dc_dinamica'))
            <div class=" error">{{ $errors->first('od_dc_dinamica') }}</div>
          @endif
        </div>
        <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
          <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
          <input type="text" class="form-control input-sm " name="od_eixo_dinamica" value="{{ $item->ard_od_eixo }}">
          @if($errors->has('od_eixo_dinamica'))
          <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
          @endif
        </div>
        <div class="col-sm-2" style=" padding-left: 5px;">
          <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
          <input type="text" class="form-control input-sm " name="od_reflexo_dinamica" value="{{ $item->ard_od_av }}">
          @if($errors->has('od_reflexo_dinamica'))
          <div class="error">{{ $errors->first('od_reflexo_dinamica') }}</div>
          @endif
        </div>
        <div class="col-sm-2" style=" padding-left: 5px;">
          <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Adição:</label>
          <input type="text" class="form-control input-sm " name="od_reflexo_dinamica" value="{{ $item->ard_od_add }}">
          @if($errors->has('od_reflexo_dinamica'))
          <div class="error">{{ $errors->first('od_reflexo_dinamica') }}</div>
          @endif
        </div>
        <div class="col-sm-2" style=" padding-left: 5px;">
          <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
          <input type="text" class="form-control input-sm " name="od_reflexo_dinamica" value="{{ $item->ard_od_add_av }}">
          @if($errors->has('od_reflexo_dinamica'))
          <div class="error">{{ $errors->first('od_reflexo_dinamica') }}</div>
          @endif
        </div>
    </div>

    <div class="row" style="margin-bottom: 5px;">
      <div class="col-sm-6">
        <h5>Auto Refração Estática:</h5>
      </div>
      <div class="col-sm-6"><label style="margin-top: 5px; display: flex">
          <div class=""><span style="margin-right:5px;"><input type="checkbox" name="receita_dinamica" @if($item->re_receita=='1' ) checked @endif></span></div> Receita
        </label>
        @if($errors->has('receita_dinamica'))
        <div class="error">{{ $errors->first('receita_dinamica') }}</div>
        @endif
      </div>
    </div>

    <div class="row" style="margin-bottom: 5px;">
      <div class="col-sm-2" style="padding-right: 5px;">
        <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OD
          DE</label>
        <input type="text" class="form-control input-sm " name="od_de_dinamica" value="{{ $item->are_od_de }}">
        @if($errors->has('od_de_dinamica'))
        <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
        @endif
      </div>
      <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
        <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
        <input type="text" class="form-control input-sm " name="od_dc_dinamica" value="{{ $item->are_od_dc }}">
        @if($errors->has('od_dc_dinamica'))
        <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
        @endif
      </div>
      <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
        <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
        <input type="text" class="form-control input-sm " name="od_eixo_dinamica" value="{{ $item->are_od_eixo }}">
        @if($errors->has('od_eixo_dinamica'))
        <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
        @endif
      </div>
      <div class="col-sm-2" style=" padding-left: 5px;">
        <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
        <input type="text" class="form-control input-sm " name="od_reflexo_dinamica" value="{{ $item->are_od_av }}">
        @if($errors->has('od_reflexo_dinamica'))
        <div class="error">{{ $errors->first('od_reflexo_dinamica') }}</div>
        @endif
      </div>
    </div>

    <div class="form-group row" style="margin-bottom: 5px;">
      <div class="col-sm-2" style="padding-right: 5px;">
        <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OE
          DE</label>
        <input type="text" class="form-control input-sm " name="od_de_dinamica" value="{{ $item->are_oe_de }}">
        @if($errors->has('od_de_dinamica'))
        <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
        @endif
      </div>
      <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
        <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
        <input type="text" class="form-control input-sm " name="od_dc_dinamica" value="{{ $item->are_oe_dc }}">
        @if($errors->has('od_dc_dinamica'))
        <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
        @endif
      </div>
      <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
        <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
        <input type="text" class="form-control input-sm " name="od_eixo_dinamica" value="{{ $item->are_oe_eixo }}">
        @if($errors->has('od_eixo_dinamica'))
        <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
        @endif
      </div>
      <div class="col-sm-2" style=" padding-left: 5px;">
        <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
        <input type="text" class="form-control input-sm " name="od_reflexo_dinamica" value="{{ $item->are_oe_av }}">
        @if($errors->has('od_reflexo_dinamica'))
        <div class="error">{{ $errors->first('od_reflexo_dinamica') }}</div>
        @endif
      </div>

    </div>

    <div class="form-group row" style="margin-bottom: 5px;">
      <div class="col-md-12">
        <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Comentário:</label>
        <textarea rows="3" class="form-control" name="comentario">{{$item->obs}}</textarea>
      </div>
    </div>

    </form>
  </div>
  </div>
</fieldset>
@endforeach
