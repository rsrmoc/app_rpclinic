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
        <div class="form-group row" style="margin-bottom: 5px;">
          <div class="col-sm-6" style=" ">
            <label for="input-help-block" class="control-label">Tipo de Lente:</label>
            <select class="form-control" name="cd_profissional" style="width: 100%">
              <option value="escolher" {{ $item->tipo_lente == 'escolher' ? 'selected' : '' }}>A Escolher</option>
              <option value="multifocais" {{ $item->tipo_lente == 'multifocais' ? 'selected' : '' }}>Multifocais</option>
              <option value="uso_com_lc" {{ $item->tipo_lente == 'uso_com_lc' ? 'selected' : '' }}>Uso com LC</option>
            </select>

            @if ($errors->has('cd_profissional'))
            <div class="error">{{ $errors->first('cd_profissional') }}</div>
            @endif
          </div>
        </div>

        <div class="form-group row " style="margin-bottom: 5px;">
          <div class="col-md-12">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Orientação:</label>
            <textarea rows="3" class="form-control" name="comentario">{{ $item->orientacao }}</textarea>
          </div>
        </div>

        <div class="form-group row" style="margin-bottom: 0px;">
          <div class="col-sm-12" style="padding-right: 5px;">
            <h5 style="margin-bottom: 0px;">Para Longe:</h5>
          </div>
        </div>

        <div class="form-group row" style="margin-bottom: 5px;">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OD
              DE</label>
            <input type="text" class="form-control input-sm " name="od_de_dinamica" value="{{ $item->longe_od_de }}">
            @if ($errors->has('od_de_dinamica'))
            <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm " name="od_dc_dinamica" value="{{ $item->longe_od_dc }}" a>
            @if ($errors->has('od_dc_dinamica'))
            <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm " name="od_eixo_dinamica" value="{{ $item->longe_od_eixo }}" a>
            @if ($errors->has('od_eixo_dinamica'))
            <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Adição:</label>
            <input type="text" class="form-control input-sm " name="od_reflexo_dinamica" value="{{ $item->longe_od_add }}" a>
            @if ($errors->has('od_reflexo_dinamica'))
            <div class="error">{{ $errors->first('od_reflexo_dinamica') }}</div>
            @endif
          </div>
        </div>
        <div class="form-group row" style="margin-bottom: 5px;">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OE
              DE</label>
            <input type="text" class="form-control input-sm " name="od_de_dinamica" value="{{ $item->longe_oe_de }}" a>
            @if ($errors->has('od_de_dinamica'))
            <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm " name="od_dc_dinamica" value="{{ $item->longe_oe_dc }}" a>
            @if ($errors->has('od_dc_dinamica'))
            <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm " name="od_eixo_dinamica" value="{{ $item->longe_oe_eixo }}" a>
            @if ($errors->has('od_eixo_dinamica'))
            <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Adição:</label>
            <input type="text" class="form-control input-sm " name="od_reflexo_dinamica" value="{{ $item->longe_oe_add }}" a>
            @if ($errors->has('od_reflexo_dinamica'))
            <div class="error">{{ $errors->first('od_reflexo_dinamica') }}</div>
            @endif
          </div>
        </div>

        <div class="form-group row" style="margin-bottom: 0px;">
          <div class="col-sm-12" style="padding-right: 5px;">
            <h5 style="margin-bottom: 0px;">Para Perto:</h5>
          </div>
        </div>

        <div class="form-group row" style="margin-bottom: 5px;">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OD
              DE</label>
            <input type="text" class="form-control input-sm " name="od_de_dinamica" value="{{ $item->perto_od_de }}" a>
            @if ($errors->has('od_de_dinamica'))
            <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm " name="od_dc_dinamica" value="{{ $item->perto_od_dc }}" a>
            @if ($errors->has('od_dc_dinamica'))
            <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm " name="od_eixo_dinamica" value="{{ $item->perto_od_eixo }}" a>
            @if ($errors->has('od_eixo_dinamica'))
            <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
            @endif
          </div>
        </div>

        <div class="form-group row" style="margin-bottom: 5px;">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OE
              DE</label>
            <input type="text" class="form-control input-sm " name="od_de_dinamica" value="{{ $item->perto_oe_de }}" a>
            @if ($errors->has('od_de_dinamica'))
            <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm " name="od_dc_dinamica" value="{{ $item->perto_oe_dc }}" a>
            @if ($errors->has('od_dc_dinamica'))
            <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm " name="od_eixo_dinamica" value="{{ $item->perto_oe_eixo }}" a>
            @if ($errors->has('od_eixo_dinamica'))
            <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
            @endif
          </div>
        </div>

        <div class="form-group row" style="margin-bottom: 0px;">
          <div class="col-sm-12" style="padding-right: 5px;">
            <h5 style="margin-bottom: 0px;">Intermediário:</h5>
          </div>
        </div>

        <div class="form-group row" style="margin-bottom: 5px;">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OD
              DE</label>
            <input type="text" class="form-control input-sm " name="od_de_dinamica" value="{{ $item->inter_od_de }}" a>
            @if ($errors->has('od_de_dinamica'))
            <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm " name="od_dc_dinamica" value="{{ $item->inter_od_dc }}" a>
            @if ($errors->has('od_dc_dinamica'))
            <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm " name="od_eixo_dinamica" value="{{ $item->inter_od_eixo }}" a>
            @if ($errors->has('od_eixo_dinamica'))
            <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
            @endif
          </div>
        </div>
        <div class="form-group row" style="margin-bottom: 5px;">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OE
              DE</label>
            <input type="text" class="form-control input-sm " name="od_de_dinamica" value="{{ $item->inter_oe_de }}" a>
            @if ($errors->has('od_de_dinamica'))
            <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm " name="od_dc_dinamica" value="{{ $item->inter_oe_dc }}" a>
            @if ($errors->has('od_dc_dinamica'))
            <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm " name="od_eixo_dinamica" value="{{ $item->inter_oe_eixo }}" a>
            @if ($errors->has('od_eixo_dinamica'))
            <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
            @endif
          </div>
        </div>

        <div class="form-group row" style="margin-bottom: 5px;">
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
