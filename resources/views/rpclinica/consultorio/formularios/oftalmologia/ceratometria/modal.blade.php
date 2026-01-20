@foreach($historico as $item)
<fieldset disabled>
    <div class="panel panel-default" style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid #ddd !important; ">
        <div class="panel-heading"  style="padding: 12px; height: 60px;">
            <h3 class="panel-title"> 
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
            </h3>
        </div>
        <div class="panel-body" style="padding-top: 1.5em">
            <form>
                <div class="row">
                    <div class="col-sm-4" style="padding-right: 5px;">
                        <label for="input-Default" class="control-label center">Data do Exame: <span class="red normal">*</span></label>
                        <input type="text" class="form-control input-sm text-center" name="dt_exame" value="{{ date('m/d/Y', strtotime($item->dt_exame)) }}">
                    </div>

                    <div class="col-sm-4" style="padding-right: 5px; padding-left: 5px;">
                        <label for="input-Default" class="control-label">Data da Liberação:</label>
                        <input type="text" class="form-control input-sm text-center" name="dt_liberacao" value=" {{ ($item->dt_liberacao) ?  date('m/d/Y', strtotime($item->dt_liberacao)) : null}}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <h5>Olho Direito:</h5>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Curva1:</label>
                                <input type="text" class="form-control input-sm text-center" name="od_curva1_ceratometria" value="{{$item->od_curva1_ceratometria}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="control-label">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-center" name="od_curva1_milimetros" value="{{$item->od_curva1_milimetros}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px;">
                                <label for="input-Default" class="control-label">Eixo1:</label>
                                <input type="text" class="form-control input-sm text-center" name="od_eixo1_ceratometria" value="{{$item->od_eixo1_ceratometria}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Curva2:</label>
                                <input type="text" class="form-control input-sm text-center" name="od_curva2_ceratometria" value="{{$item->od_curva2_ceratometria}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="control-label">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-center" name="od_curva2_milimetros" value="{{$item->od_curva2_milimetros}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px;">
                                <label for="input-Default" class="control-label">Eixo2:</label>
                                <input type="text" class="form-control input-sm text-center" name="od_eixo2_ceratometria" value="{{$item->od_eixo2_ceratometria}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Média:</label>
                                <input type="text" class="form-control input-sm text-center" name="od_media_ceratometria" value="{{$item->od_media_ceratometria}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="control-label">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-center" name="od_media_milimetros" value="{{$item->od_media_milimetros}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Cilíndro(-):</label>
                                <input type="text" class="form-control input-sm text-center" name="od_cilindro_neg" value="{{$item->od_cilindro_neg}}">
                            </div>
                            <div class="col-sm-4 col-md-offset-4" style="padding-left: 5px;">
                                <label for="input-Default" class="control-label">Eixo(-):</label>
                                <input type="text" class="form-control input-sm text-center" name="od_eixo_neg" value="{{$item->od_eixo_neg}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Cilíndro(+):</label>
                                <input type="text" class="form-control input-sm text-center" name="od_cilindro_pos" value="{{$item->od_cilindro_pos}}">
                            </div>
                            <div class="col-sm-4 col-md-offset-4" style="padding-left: 5px;">
                                <label for="input-Default" class="control-label">Eixo(+):</label>
                                <input type="text" class="form-control input-sm text-center" name="od_eixo_pos" value="{{$item->od_eixo_pos}}">
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 ">
                        <h5>Olho Esquerdo:</h5>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Curva1:</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_curva1_ceratometria" value="{{$item->oe_curva1_ceratometria}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="control-label">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_curva1_milimetros" value="{{$item->oe_curva1_milimetros}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px;">
                                <label for="input-Default" class="control-label">Eixo1:</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_eixo1_ceratometria" value="{{$item->oe_eixo1_ceratometria}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Curva2:</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_curva2_ceratometria" value="{{$item->oe_curva2_ceratometria}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="control-label">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_curva2_milimetros" value="{{$item->oe_curva2_milimetros}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px;">
                                <label for="input-Default" class="control-label">Eixo2:</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_eixo2_ceratometria" value="{{$item->oe_eixo2_ceratometria}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Média:</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_media_ceratometria" value="{{$item->oe_media_ceratometria}}">
                            </div>
                            <div class="col-sm-4" style="padding-left: 5px; padding-right: 5px;">
                                <label for="input-Default" class="control-label">&nbsp;</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_media_milimetros" value="{{$item->oe_media_milimetros}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Cilíndro(-):</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_cilindro_neg" value="{{$item->oe_cilindro_neg}}">
                            </div>
                            <div class="col-sm-4 col-md-offset-4" style="padding-left: 5px;">
                                <label for="input-Default" class="control-label">Eixo(-):</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_eixo_neg" value="{{$item->oe_eixo_neg}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4" style="padding-right: 5px;">
                                <label for="input-Default" class="control-label">Cilíndro(+):</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_cilindro_pos" value="{{$item->oe_cilindro_pos}}">
                            </div>
                            <div class="col-sm-4 col-md-offset-4" style="padding-left: 5px;">
                                <label for="input-Default" class="control-label">Eixo(+):</label>
                                <input type="text" class="form-control input-sm text-center" name="oe_eixo_pos" value="{{$item->oe_eixo_pos}}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <label for="input-placeholder" class="control-label" style="padding-top: 0px;">Comentário:</label>
                        <textarea rows="3" class="form-control" name="obs">{{$item->obs}}</textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</fieldset>
@endforeach