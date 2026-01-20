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
                <div class="form-group  " style="margin-bottom: 5px;">
                    <div class="col-sm-4" style="padding-right: 5px;">
                        <label for="input-Default " class="  control-label   ">Data do Exame:</label>
                        <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame"
                            value="{{$item->dt_exame}}">
                        @if($errors->has('dt_exame'))
                        <div class="error">{{ $errors->first('dt_exame') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
                        <label for="input-Default" class="  control-label ">OD (mmHg):</label>
                        <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
                            value="'{{$item->pressao_od}}'">
                        @if($errors->has('dt_liberacao'))
                        <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                        @endif
                    </div>

                    <div class="col-sm-4" style="  padding-left: 5px; ">
                        <label for="input-Default" class="  control-label ">OE (mmHg):</label>
                        <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
                            value="'{{$item->pressao_oe}}'">
                        @if($errors->has('dt_liberacao'))
                        <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                        @endif
                    </div>

                </div>

                <div class="form-group " style=" ">
                    <div class="col-sm-12" style=" ">
                        <label for="input-help-block" class="control-label">Equipamento:</label>
                        <select class="form-control " name="cd_profissional" style="width: 100%">
                            <option value="{{$item->nm_equipamento}}">{{$item->nm_equipamento}}</option>
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
                        <textarea rows="3" class="form-control" name="comentario">{{$item->obs}}</textarea>
                    </div>'
                </div>

            </form>

        </div>
    </div>
</fieldset>
@endforeach

{{-- <div class="panel panel-default" style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid #ddd !important; ">
    <div class="panel-heading" style="padding: 12px; height: 40px;">
        <h3 class="panel-title"> <span style=" margin-right: 15px;">*98562* [ 01/08/2024 ] </span> Nome do profissional </h3>
    </div>
    <div class="panel-body" style="padding-top: 1.5em">

        <form  >
         
            <div class="form-group  " style="margin-bottom: 5px;">
                <div class="col-sm-4" style="padding-right: 5px;">
                    <label for="input-Default " class="  control-label   ">Data do Exame:</label>
                    <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame"
                        value="{{ old('dt_exame') }}">
@if($errors->has('dt_exame'))
<div class="error">{{ $errors->first('dt_exame') }}</div>
@endif
</div>

<div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
    <label for="input-Default" class="  control-label ">OD (mmHg):</label>
    <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
        value="{{ old('dt_liberacao') }}">
    @if($errors->has('dt_liberacao'))
    <div class="error">{{ $errors->first('dt_liberacao') }}</div>
    @endif
</div>

<div class="col-sm-4" style="  padding-left: 5px; ">
    <label for="input-Default" class="  control-label ">OE (mmHg):</label>
    <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
        value="{{ old('dt_liberacao') }}">
    @if($errors->has('dt_liberacao'))
    <div class="error">{{ $errors->first('dt_liberacao') }}</div>
    @endif
</div>

</div>

<div class="form-group " style=" ">
    <div class="col-sm-12" style=" ">
        <label for="input-help-block" class="control-label">Equipamento:</label>
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
        <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
    </div>
</div>

</form>

</div>
</div>


<div class="panel panel-default" style="margin-top: 1.5em; margin-bottom: 0; border: 1px solid #ddd !important; ">
    <div class="panel-heading" style="padding: 12px; height: 40px;">
        <h3 class="panel-title"> <span style=" margin-right: 15px;">*98562* [ 01/08/2024 ] </span> Nome do profissional </h3>
    </div>
    <div class="panel-body" style="padding-top: 1.5em">

        <form>


            <div class="form-group  " style="margin-bottom: 5px;">
                <div class="col-sm-4" style="padding-right: 5px;">
                    <label for="input-Default " class="  control-label   ">Data do Exame:</label>
                    <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame"
                        value="{{ old('dt_exame') }}">
                    @if($errors->has('dt_exame'))
                    <div class="error">{{ $errors->first('dt_exame') }}</div>
                    @endif
                </div>

                <div class="col-sm-4" style="  padding-left: 5px; padding-right: 5px;">
                    <label for="input-Default" class="  control-label ">OD (mmHg):</label>
                    <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
                        value="{{ old('dt_liberacao') }}">
                    @if($errors->has('dt_liberacao'))
                    <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                    @endif
                </div>

                <div class="col-sm-4" style="  padding-left: 5px; ">
                    <label for="input-Default" class="  control-label ">OE (mmHg):</label>
                    <input type="text" class="form-control input-sm text-center" name="dt_liberacao"
                        value="{{ old('dt_liberacao') }}">
                    @if($errors->has('dt_liberacao'))
                    <div class="error">{{ $errors->first('dt_liberacao') }}</div>
                    @endif
                </div>

            </div>

            <div class="form-group " style=" ">
                <div class="col-sm-12" style=" ">
                    <label for="input-help-block" class="control-label">Equipamento:</label>
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
                    <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                </div>
            </div>

        </form>

    </div>
</div> --}}