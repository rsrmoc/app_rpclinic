@foreach($historico as $item)
<fieldset disabled>
  <div class="panel panel-default" style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid #ddd !important; ">
    <div class="panel-heading" style="padding: 12px; height: 60px;">
      <h3 class="panel-title" style="width: 100%;"> 
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
        <div class="row">
          <div class="col-sm-4" style="padding-right: 5px;">
            <label for="input-Default" class="control-label center">Data do Exame: <span class="red normal">*</span></label>
            <input type="text" class="form-control input-sm text-center" name="dt_exame" value="{{ date('d/m/Y',strtotime($item->dt_exame)) }}">
 
          </div>

          <div class="col-sm-4" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-Default" class="control-label">Data da Liberação:</label>
            <input type="text" class="form-control input-sm text-center" name="dt_liberacao" value="{{ ($item->dt_liberacao) ? date('d/m/Y',strtotime($item->dt_liberacao)) : null}}">
    
          </div>

          <div class="col-sm-4" style="padding-left: 5px;">
            <label for="input-Default" class="control-label bold">DP:</label>
            <input type="text" class="form-control input-sm text-right" name="dp" value="{{$item->dp}}">
 
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <h5>Auto Refração Dinâmica:  </h5>
          </div>
          <div class="col-sm-4">
            <label style="margin-top: 5px;">
              <input type="checkbox" name="receita_dinamica" @if ($item->receita_dinamica == '1') checked @endif  > Receita
            </label> 
          </div>
        </div>

        <div class="row">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class="control-label bold" style="font-size: 0.9em;">OD DE</label>
            <input type="text" class="form-control input-sm text-right" name="od_de_dinamica" value="{{$item->od_de_dinamica}}">
 
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">DC</label>
            <input type="text" class="form-control input-sm text-right" name="od_dc_dinamica" value="{{$item->od_dc_dinamica}}">
 
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">Eixo</label>
            <input type="text" class="form-control input-sm text-right" x-mask:dynamic="$money($input, ',')" name="od_eixo_dinamica" value="{{$item->od_eixo_dinamica}}">
 
          </div>
          <div class="col-sm-3" style="padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">Reflexo OD:</label>
            <input type="text" class="form-control input-sm text-right" x-mask:dynamic="$money($input, ',')" name="od_reflexo_dinamica" value="{{$item->od_reflexo_dinamica}}">
 
          </div>
        </div>

        <div class="row">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class="control-label bold" style="font-size: 0.9em;">OE DE</label>
            <input type="text" class="form-control input-sm text-right" name="oe_de_dinamica" x-mask:dynamic="$money($input, ',')" value="{{$item->oe_de_dinamica}}">
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">DC</label>
            <input type="text" class="form-control input-sm text-right" value="{{$item->oe_dc_dinamica}}">
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">Eixo</label>
            <input type="text" class="form-control input-sm text-right" name="oe_eixo_dinamica" value="{{$item->oe_eixo_dinamica}}">
          </div>
          <div class="col-sm-3" style="padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">Reflexo OE:</label>
            <input type="text" class="form-control input-sm text-right" name="oe_reflexo_dinamica" value="{{$item->oe_reflexo_dinamica}}">
          </div>
        </div>

        <div class="row">
          <div class="col-sm-4">
            <h5>Auto Refração Estática:</h5>
          </div>
          <div class="col-sm-6">
           
            <label style="margin-top: 5px;">
              <input type="checkbox" name="receita_estatica" @if ($item->receita_estatica == '1') checked @endif  > Receita
            </label> 
          </div>
        </div>

        <div class="row">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">OD DE </label>
            <input type="text" class="form-control input-sm text-right" name="od_de_estatica" value="{{$item->od_de_estatica}}">
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">DC</label>
            <input type="text" class="form-control input-sm text-right" name="od_dc_estatica" value="{{$item->od_dc_estatica}}">
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">Eixo</label>
            <input type="text" class="form-control input-sm text-right" name="od_eixo_estatica" value="{{$item->od_eixo_estatica}}">
          </div>
          <div class="col-sm-3" style="padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">Reflexo OD:</label>
            <input type="text" class="form-control input-sm text-right" name="od_reflexo_estatica" value="{{$item->od_reflexo_estatica}}">
          </div>
        </div>

        <div class="row">
          <div class="col-sm-3" style="padding-right: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">OE DE </label>
            <input type="text" class="form-control input-sm text-right" name="oe_de_estatica" value="{{$item->oe_de_estatica}}">
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">DC</label>
            <input type="text" class="form-control input-sm text-right" name="oe_dc_estatica" value="{{$item->oe_dc_estatica}}">
          </div>
          <div class="col-sm-3" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">Eixo</label>
            <input type="text" class="form-control input-sm text-right" name="oe_eixo_estatica" value="{{$item->oe_eixo_estatica}}">
          </div>
          <div class="col-sm-3" style="padding-left: 5px;">
            <label for="input-placeholder" class="control-label" style="font-size: 0.9em;">Reflexo OE:</label>
            <input type="text" class="form-control input-sm text-right" name="oe_reflexo_estatica" value="{{$item->oe_reflexo_estatica}}">
          </div>
        </div>
      </form>
    </div>
  </div>
</fieldset>
@endforeach
