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
                        <span style=" margin-right: 15px;font-weight: 300;" >[ {{$item->cd_agendamento}} ] {{ ($item->dt_anamnese) ? date('d/m/Y', strtotime($item->dt_anamnese)) : ' -- ' }} </span>
                        
                      </td>
                      <td style="text-align: right"> 
                        <span style=" margin-right: 15px; font-style: italic" >Usuario: {{ ($item->nm_usuario) ? $item->nm_usuario : ' -- ' }}</span>
                        <br>
                    
                        <span style=" margin-right: 15px;font-weight: 300;" >{{   ($item->dt_cad_anamnese) ? date('d/m/Y', strtotime($item->dt_cad_anamnese)) : ' -- ' }} </span>
                        
                      </td>
                    </tr>
                  </table>
            </h3>
        </div>
        <div class="panel-body" style="padding-top: 1.5em">
            <form>
             

                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Motivo da consulta:</label>
                        <textarea rows="3" class="form-control" name="comentario">{{ $item->motivo }}</textarea>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Historia Oftalmológica prévia:</label>
                        <textarea rows="3" class="form-control" name="comentario">{{ $item->historia }}</textarea>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Medicamentos:</label>
                        <textarea rows="3" class="form-control" name="comentario">{{ $item->medicamentos }}</textarea>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Alergias:</label>
                        <textarea rows="3" class="form-control" name="comentario">{{ $item->alergias }}</textarea>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 5px;">
                    <div class="col-md-12">
                        <label for="input-placeholder" class=" control-label"
                            style="  padding-top: 0px;">Conduta:</label>
                        <textarea rows="3" class="form-control" name="comentario">{{ $item->conduta }}</textarea>
                    </div>
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

        <form   >
            
            <div class="form-group  " style="margin-bottom: 5px;">
                <div class="col-sm-4" style="padding-right: 5px;">
                    <label for="input-Default " class="  control-label   ">Data da Anamnese:</label>
                    <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame"
                        value="{{ old('dt_exame') }}">
@if($errors->has('dt_exame'))
<div class="error">{{ $errors->first('dt_exame') }}</div>
@endif
</div>

</div>

<div class="form-group" style="margin-bottom: 5px;">
    <div class="col-md-12">
        <label for="input-placeholder" class=" control-label"
            style="  padding-top: 0px;">Motivo da consulta:</label>
        <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    <div class="col-md-12">
        <label for="input-placeholder" class=" control-label"
            style="  padding-top: 0px;">Historia Oftalmológica prévia:</label>
        <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    <div class="col-md-12">
        <label for="input-placeholder" class=" control-label"
            style="  padding-top: 0px;">Medicamentos:</label>
        <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    <div class="col-md-12">
        <label for="input-placeholder" class=" control-label"
            style="  padding-top: 0px;">Alergias:</label>
        <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    <div class="col-md-12">
        <label for="input-placeholder" class=" control-label"
            style="  padding-top: 0px;">Conduta:</label>
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
                    <label for="input-Default " class="  control-label   ">Data da Anamnese:</label>
                    <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame"
                        value="{{ old('dt_exame') }}">
                    @if($errors->has('dt_exame'))
                    <div class="error">{{ $errors->first('dt_exame') }}</div>
                    @endif
                </div>

            </div>

            <div class="form-group" style="margin-bottom: 5px;">
                <div class="col-md-12">
                    <label for="input-placeholder" class=" control-label"
                        style="  padding-top: 0px;">Motivo da consulta:</label>
                    <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;">
                <div class="col-md-12">
                    <label for="input-placeholder" class=" control-label"
                        style="  padding-top: 0px;">Historia Oftalmológica prévia:</label>
                    <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;">
                <div class="col-md-12">
                    <label for="input-placeholder" class=" control-label"
                        style="  padding-top: 0px;">Medicamentos:</label>
                    <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;">
                <div class="col-md-12">
                    <label for="input-placeholder" class=" control-label"
                        style="  padding-top: 0px;">Alergias:</label>
                    <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 5px;">
                <div class="col-md-12">
                    <label for="input-placeholder" class=" control-label"
                        style="  padding-top: 0px;">Conduta:</label>
                    <textarea rows="3" class="form-control" name="comentario">{{ old('comentario') }}</textarea>
                </div>
            </div>

        </form>

    </div>
</div> --}}