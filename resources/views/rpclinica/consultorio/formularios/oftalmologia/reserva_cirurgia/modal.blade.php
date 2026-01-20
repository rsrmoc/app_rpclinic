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
     
        <div class="form-group ">
          <div class="col-sm-12">
            <label for="input-help-block" class="control-label">Cirurgia:</label>
            <input type="text" class="form-control " value="{{$item->cirurgia?->nm_exame}}">
          </div>
        </div>
        <div class="form-group ">
          <div class="col-sm-12">
            <label for="input-help-block" class="control-label">Cirurgião:</label>
            <input type="text" class="form-control " value="{{$item->cirurgiao?->nm_profissional}}">
          </div>
        </div>
        <div class="form-group ">
          <div class="col-sm-12">
            <label for="input-help-block" class="control-label">OPMEs:</label>
            <div style=" width: 100%; min-height: 60px;" class="form-control ">  
              @foreach($item->opme as $key => $val)
              <span class="label label-default" style="padding: 4px; margin-right: 5px; color: #4E5E6A"> {{ $val->produtos?->nm_produto }}</span>
              @endforeach
             
            </div>
          </div>
        </div>
        <div class="form-group ">
          <div class="col-md-12">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Comentário:</label>
            <textarea rows="4" class="form-control" name="comentario">{{ $item->comentarios }}</textarea>
          </div>
        </div>
      </form>
    </div>
  </div>
</fieldset>
@endforeach
