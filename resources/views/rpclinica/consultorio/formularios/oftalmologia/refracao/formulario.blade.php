<div class="col-md-7 col-sm-7 col-lg-6 col-xs-12  ">
  <div class="panel panel-white ui-sortable-handle">
    <div class="panel-heading">
      <div class="row">
        <div class="col-md-12">
          <h3 class="panel-title"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Refração
          </h3>
          <div class="panel-control">
            <a href="javascript:void(0);" data-toggle="tooltip" x-on:click="deleteRefracao(REFRACAO.formData.cd_refracao)" data-placement="top" title="" data-original-title="Exluir"><i class="icon-close"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div class="panel-body" x-data="{ index: 0, showHistory: false}" >
      <form class="form-horizontal" x-on:submit.prevent="storeRefracao" id="form_REFRACAO" method="post" x-show="!showHistory"  >
        @csrf
        <div class="form-group " style="margin-bottom: 5px;">
          <div class="col-sm-12">
            <label for="input-help-block" class="control-label">Profissional:  <span class="red normal">*</span></label>
            <select class="form-control " name="cd_profissional" style="width: 100%">
              <option value="{{ $agendamento->cd_profissional }}">{{ $agendamento->profissional->nm_profissional }}</option>
            </select>
 
          </div>
        </div>

        <div class="form-group  " style="margin-bottom: 5px;">
          <div class="col-sm-4" style="padding-right: 5px;">
            <label for="input-Default " class="  control-label   ">Data do Exame:  <span class="red normal">*</span></label>
            <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame" x-model="REFRACAO.formData.dt_exame">
 
          </div>

          <div class="col-sm-4" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-Default" class="  control-label ">Data da Liberação:</label>
            <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao" x-model="REFRACAO.formData.dt_liberacao">
 
          </div>

          <div class="col-sm-4" style="  padding-left: 5px;">
            <label for="input-Default" class="  control-label bold ">DP:</label>
            <input type="text" class="form-control input-sm text-right" name="dp" x-model="REFRACAO.formData.dp" x-mask:dynamic="$money($input, ',')">
 
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-sm-6" style="">
            <h5>Auto Refração Dinâmica:</h5>
          </div>
          <div class="col-sm-6">
            <label  id="label_rd_receita" style="margin-top: 5px; display: flex"> 
                  <input type="checkbox" name="rd_receita" id="rd_receita"  >
                 Receita
            </label>
 
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-sm-2" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OD
              DE</label>
            <input type="text" class="form-control input-sm text-right " name="ard_od_de" x-model="REFRACAO.formData.ard_od_de" x-mask:dynamic="$money($input, ',')">
  
          </div>
          <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm text-right " name="ard_od_dc" x-model="REFRACAO.formData.ard_od_dc" x-mask:dynamic="$money($input, ',')">
   
          </div>
          <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm text-right " name="ard_od_eixo" x-model="REFRACAO.formData.ard_od_eixo" x-mask:dynamic="$money($input, ',')">
      
          </div>
          <div class="col-sm-2" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
            <select class="form-control " name="ard_od_av" id="ard_od_av"  >
              <option value=""></option>
              <option value="20/15">20/15</option>
              <option value="20/20">20/20</option>
              <option value="20/25">20/25</option>
              <option value="20/30">20/30</option>
              <option value="20/40">20/40</option>
              <option value="20/50">20/50</option>
              <option value="20/60">20/60</option>
              <option value="20/80">20/80</option>
              <option value="20/100">20/100</option>
              <option value="20/150">20/150</option>
              <option value="20/300">20/300</option>
              <option value="20/300">20/400</option>
              <option value="CD a 5M">CD a 5M</option>
              <option value="CD a 4M">CD a 4M</option>
              <option value="CD a 3M">CD a 3M</option>
              <option value="CD a 2M">CD a 2M</option>
              <option value="CD a 1M">CD a 1M</option>
              <option value="CD a 0,5">CD a 0,5</option>
              <option value="M M">M M</option>
              <option value="P L">P L</option>
              <option value="S P L">S P L</option>
            </select>
   
          </div>
          <div class="col-sm-2" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Adição:</label>
            <input type="text" class="form-control input-sm text-right" name="ard_od_add" x-model="REFRACAO.formData.ard_od_add" x-mask:dynamic="$money($input, ',')">
            @if($errors->has('od_reflexo_dinamica'))
            <div class="error">{{ $errors->first('od_reflexo_dinamica') }}</div>
            @endif
          </div>
          <div class="col-sm-2" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
            <select class="form-control " name="ard_od_add_av" id="ard_od_add_av">
              <option value=""></option>
              <option value="J1">J1</option>
              <option value="J2">J2</option>
              <option value="J3">J3</option>
              <option value="J4">J4</option>
              <option value="J5">J5</option>
              <option value="J6">J6</option>
            </select>
   
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-sm-2" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OE
              DE</label>
            <input type="text" class="form-control input-sm text-right" name="ard_oe_de" x-model="REFRACAO.formData.ard_oe_de" x-mask:dynamic="$money($input, ',')">
      
          </div>
          <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm text-right" name="ard_oe_dc" x-model="REFRACAO.formData.ard_oe_dc" x-mask:dynamic="$money($input, ',')">
        
          </div>
          <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm text-right" name="ard_oe_eixo" x-model="REFRACAO.formData.ard_oe_eixo" x-mask:dynamic="$money($input, ',')">
    
          </div>
          <div class="col-sm-2" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
            <select class="form-control " name="ard_oe_av" id="ard_oe_av"  >
              <option value=""></option>
              <option value="20/15">20/15</option>
              <option value="20/20">20/20</option>
              <option value="20/25">20/25</option>
              <option value="20/30">20/30</option>
              <option value="20/40">20/40</option>
              <option value="20/50">20/50</option>
              <option value="20/60">20/60</option>
              <option value="20/80">20/80</option>
              <option value="20/100">20/100</option>
              <option value="20/150">20/150</option>
              <option value="20/300">20/300</option>
              <option value="20/300">20/400</option>
              <option value="CD a 5M">CD a 5M</option>
              <option value="CD a 4M">CD a 4M</option>
              <option value="CD a 3M">CD a 3M</option>
              <option value="CD a 2M">CD a 2M</option>
              <option value="CD a 1M">CD a 1M</option>
              <option value="CD a 0,5">CD a 0,5</option>
              <option value="M M">M M</option>
              <option value="P L">P L</option>
              <option value="S P L">S P L</option>
            </select>
     
          </div>
          <div class="col-sm-2" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Adição:</label>
            <input type="text" class="form-control input-sm text-right" name="ard_oe_add" x-model="REFRACAO.formData.ard_oe_add" x-mask:dynamic="$money($input, ',')">
      
          </div>
          <div class="col-sm-2" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
            <select class="form-control " name="ard_oe_add_av" id="ard_oe_add_av"  >
              <option value=""></option>
              <option value="J1">J1</option>
              <option value="J2">J2</option>
              <option value="J3">J3</option>
              <option value="J4">J4</option>
              <option value="J5">J5</option>
              <option value="J6">J6</option>
            </select> 
          </div>
        </div>

        <div class="form-group">
          <div class="col-sm-6">
            <h5>Auto Refração Estática:</h5>
          </div>
          <div class="col-sm-6">
            <label id="label_re_receita"  style="margin-top: 5px; display: flex">
              
                  <input type="checkbox" name="re_receita" id="re_receita" >
                  Receita
            </label> 
          </div>
        </div>

        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-sm-2" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OD
              DE</label>
            <input type="text" class="form-control input-sm text-right" name="are_od_de" x-model="REFRACAO.formData.are_od_de" x-mask:dynamic="$money($input, ',')">
          
          </div>
          <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm text-right" name="are_od_dc" x-model="REFRACAO.formData.are_od_dc" x-mask:dynamic="$money($input, ',')">
       
          </div>
          <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm text-right " name="are_od_eixo" x-model="REFRACAO.formData.are_od_eixo" x-mask:dynamic="$money($input, ',')">
        
          </div>
          <div class="col-sm-2" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
            <select class="form-control " name="are_od_av" ID="are_od_av" >
              <option value=""></option>
              <option value="20/15">20/15</option>
              <option value="20/20">20/20</option>
              <option value="20/25">20/25</option>
              <option value="20/30">20/30</option>
              <option value="20/40">20/40</option>
              <option value="20/50">20/50</option>
              <option value="20/60">20/60</option>
              <option value="20/80">20/80</option>
              <option value="20/100">20/100</option>
              <option value="20/150">20/150</option>
              <option value="20/300">20/300</option>
              <option value="20/300">20/400</option>
              <option value="CD a 5M">CD a 5M</option>
              <option value="CD a 4M">CD a 4M</option>
              <option value="CD a 3M">CD a 3M</option>
              <option value="CD a 2M">CD a 2M</option>
              <option value="CD a 1M">CD a 1M</option>
              <option value="CD a 0,5">CD a 0,5</option>
              <option value="M M">M M</option>
              <option value="P L">P L</option>
              <option value="S P L">S P L</option>
            </select>
       
          </div>

        </div>

        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-sm-2" style="padding-right: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OE
              DE</label>
            <input type="text" class="form-control input-sm text-right" name="are_oe_de" x-model="REFRACAO.formData.are_oe_de" x-mask:dynamic="$money($input, ',')">
        
          </div>
          <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
            <input type="text" class="form-control input-sm text-right" name="are_oe_dc" x-model="REFRACAO.formData.are_oe_dc" x-mask:dynamic="$money($input, ',')">
         
          </div>
          <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
            <input type="text" class="form-control input-sm text-right" name="are_oe_eixo" x-model="REFRACAO.formData.are_oe_eixo" x-mask:dynamic="$money($input, ',')">
      
          </div>
          <div class="col-sm-2" style=" padding-left: 5px;">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
            <select class="form-control " name="are_oe_av"  id="are_oe_av">
              <option value=""></option>
              <option value="20/15">20/15</option>
              <option value="20/20">20/20</option>
              <option value="20/25">20/25</option>
              <option value="20/30">20/30</option>
              <option value="20/40">20/40</option>
              <option value="20/50">20/50</option>
              <option value="20/60">20/60</option>
              <option value="20/80">20/80</option>
              <option value="20/100">20/100</option>
              <option value="20/150">20/150</option>
              <option value="20/300">20/300</option>
              <option value="20/300">20/400</option>
              <option value="CD a 5M">CD a 5M</option>
              <option value="CD a 4M">CD a 4M</option>
              <option value="CD a 3M">CD a 3M</option>
              <option value="CD a 2M">CD a 2M</option>
              <option value="CD a 1M">CD a 1M</option>
              <option value="CD a 0,5">CD a 0,5</option>
              <option value="M M">M M</option>
              <option value="P L">P L</option>
              <option value="S P L">S P L</option>
            </select>
         
          </div>

        </div>

        <div class="form-group" style="margin-bottom: 5px;">
          <div class="col-md-12">
            <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Comentário:</label>
            <textarea rows="3" class="form-control" name="obs" x-model="REFRACAO.formData.obs"></textarea>
          </div>
        </div>

        <div class="panel-footer col-md-12">

          <div class="row">
            <div class="col-md-8">
              <button type="submit" class="btn btn-success" x-html="buttonSalvar" x-bind:disabled="buttonDisabled"> </button> 

              <button type="button" class="btn btn-default" @click="showHistory = !showHistory">
                <i class="fa fa-history"></i> Histórico
              </button>
            </div>
           

          </div>

        </div>

      </form>

      <form class="form-horizontal" x-show="showHistory">
        <fieldset disabled>
          <div class="form-group " style="margin-bottom: 5px;">
            <div class="col-sm-12">
              <label for="input-help-block" class="control-label">Profissional:</label> 
              <input type="text" class="form-control input-sm text-left" name="dt_exame" value="{{ $agendamento->profissional->nm_profissional }}">
            </div>
          </div>

          <div class="form-group  " style="margin-bottom: 5px;">
            <div class="col-sm-4" style="padding-right: 5px;">
              <label for="input-Default " class="  control-label   ">Data do Exame:</label>
              <input type="datetime-local" class="form-control input-sm text-center" name="dt_exame" x-model="REFRACAO.history[index].dt_exame">
          
            </div>

            <div class="col-sm-4" style="padding-right: 5px; padding-left: 5px;">
              <label for="input-Default" class="  control-label ">Data da Liberação:</label>
              <input type="datetime-local" class="form-control input-sm text-center" name="dt_liberacao" x-model="REFRACAO.history[index].dt_liberacao">
 
            </div>

            <div class="col-sm-4" style="  padding-left: 5px;">
              <label for="input-Default" class="  control-label bold ">DP:</label>
              <input type="text" class="form-control input-sm text-right" name="dp" x-model="REFRACAO.history[index].dp">
          
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-sm-6" style="">
              <h5>Auto Refração Dinâmica:</h5>
            </div>
            <div class="col-sm-6">
              
              <template x-if="REFRACAO.history[index].rd_receita <> '1'"> 
                <label style="margin-top: 5px; display: flex">
                  <div class="" style="margin-right: 5px;"><span>
                      <input type="checkbox" name="re_receita" >
                    </span></div> Receita
                </label>
              </template>

              <template x-if="REFRACAO.history[index].rd_receita == '1'"> 
                <label style="margin-top: 5px; display: flex">
                  <div class="" style="margin-right: 5px;"><span>
                      <input type="checkbox" name="re_receita" checked >
                    </span></div> Receita
                </label>
              </template>
         
            
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-right: 5px;">
              <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OD
                DE</label>
              <input type="text" class="form-control input-sm text-right" name="ard_od_de" x-model="REFRACAO.history[index].ard_od_de">
     
            </div>
            <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
              <input type="text" class="form-control input-sm text-right" name="ard_od_dc" x-model="REFRACAO.history[index].ard_od_dc">
          
            </div>
            <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
              <input type="text" class="form-control input-sm text-right" name="ard_od_eixo" x-model="REFRACAO.history[index].ard_od_eixo">
          
            </div>
            <div class="col-sm-2" style=" padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
              <input type="text" class="form-control input-sm  " name="ard_od_eixo" x-model="REFRACAO.history[index].ard_od_av">
             
            </div>
            <div class="col-sm-2" style=" padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Adição:</label>
              <input type="text" class="form-control input-sm text-right" name="ard_od_add" x-model="REFRACAO.history[index].ard_od_add">
         
            </div>
            <div class="col-sm-2" style=" padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
              
              <input type="text" class="form-control input-sm " name="ard_od_eixo" x-model="REFRACAO.history[index].ard_od_add_av">
          
               
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-right: 5px;">
              <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OE
                DE</label>
              <input type="text" class="form-control input-sm text-right" name="ard_oe_de" x-model="REFRACAO.history[index].ard_oe_de">
  
            </div>
            <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
              <input type="text" class="form-control input-sm text-right" name="ard_oe_dc" x-model="REFRACAO.history[index].ard_oe_dc">
         
            </div>
            <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
              <input type="text" class="form-control input-sm text-right" name="ard_oe_eixo" x-model="REFRACAO.history[index].ard_oe_eixo">
             
            </div>
            <div class="col-sm-2" style=" padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label> 
              <input type="text" class="form-control input-sm " name="ard_od_eixo" x-model="REFRACAO.history[index].ard_oe_av">
          
     
            </div>
            <div class="col-sm-2" style=" padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Adição:</label>
              <input type="text" class="form-control input-sm " name="ard_oe_add" x-model="REFRACAO.history[index].ard_oe_add">
          
            </div>
            <div class="col-sm-2" style=" padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
              <input type="text" class="form-control input-sm " name="ard_oe_add" x-model="REFRACAO.history[index].ard_oe_add_av">
            
            </div>
          </div>

          <div class="form-group">
            <div class="col-sm-6">
              <h5>Auto Refração Estática:</h5>
            </div>
            <div class="col-sm-6">
              
              <template x-if="REFRACAO.history[index].re_receita <> '1'"> 
                <label style="margin-top: 5px; display: flex">
                  <div class="" style="margin-right: 5px;"><span>
                      <input type="checkbox" name="re_receita" >
                    </span></div> Receita
                </label>
              </template>

              <template x-if="REFRACAO.history[index].re_receita == '1'"> 
                <label style="margin-top: 5px; display: flex">
                  <div class="" style="margin-right: 5px;"><span>
                      <input type="checkbox" name="re_receita" checked >
                    </span></div> Receita
                </label>
              </template>
               
              @if($errors->has('receita_dinamica'))
              <div class="error">{{ $errors->first('receita_dinamica') }}</div>
              @endif
            </div>
          </div>

          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-right: 5px;">
              <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OD
                DE</label>
              <input type="text" class="form-control input-sm text-right" name="are_od_de" x-model="REFRACAO.history[index].are_od_de">
              @if($errors->has('od_de_dinamica'))
              <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
              @endif
            </div>
            <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
              <input type="text" class="form-control input-sm text-right" name="are_od_dc" x-model="REFRACAO.history[index].are_od_dc">
              @if($errors->has('od_dc_dinamica'))
              <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
              @endif
            </div>
            <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
              <input type="text" class="form-control input-sm text-right" name="are_od_eixo" x-model="REFRACAO.history[index].are_od_eixo">
              @if($errors->has('od_eixo_dinamica'))
              <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
              @endif
            </div>
            <div class="col-sm-2" style=" padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
              <input type="text" class="form-control input-sm " name="are_od_eixo" x-model="REFRACAO.history[index].are_od_av">
               
            </div>

          </div>

          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-sm-2" style="padding-right: 5px;">
              <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">OE
                DE</label>
              <input type="text" class="form-control input-sm text-right" name="are_oe_de" x-model="REFRACAO.history[index].are_oe_de">
              @if($errors->has('od_de_dinamica'))
              <div class="error">{{ $errors->first('od_de_dinamica') }}</div>
              @endif
            </div>
            <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="font-size: 0.9em; padding-top: 0px;">DC</label>
              <input type="text" class="form-control input-sm text-right" name="are_oe_dc" x-model="REFRACAO.history[index].are_oe_dc">
              @if($errors->has('od_dc_dinamica'))
              <div class="error">{{ $errors->first('od_dc_dinamica') }}</div>
              @endif
            </div>
            <div class="col-sm-2" style="padding-right: 5px; padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Eixo</label>
              <input type="text" class="form-control input-sm text-right" name="are_oe_eixo" x-model="REFRACAO.history[index].are_oe_eixo">
              @if($errors->has('od_eixo_dinamica'))
              <div class="error">{{ $errors->first('od_eixo_dinamica') }}</div>
              @endif
            </div>
            <div class="col-sm-2" style=" padding-left: 5px;">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">AV:</label>
              <input type="text" class="form-control input-sm " name="are_oe_eixo" x-model="REFRACAO.history[index].are_oe_av">
          
            </div>

          </div>

          <div class="form-group" style="margin-bottom: 5px;">
            <div class="col-md-12">
              <label for="input-placeholder" class=" control-label" style="  padding-top: 0px;">Comentário:</label>
              <textarea rows="3" class="form-control" name="obs" x-model="REFRACAO.history[index].obs"></textarea>
            </div>
          </div>
        </fieldset>

        <div class="panel-footer col-md-12">
          <div class="row">
            <div class="col-md-6" style="text-align: left">
              <div class="btn-group" role="group" aria-label="First group">

                <button type="button" class="btn btn-default" @click="index = 0" :disabled="index === 0"><i class="fa fa-fast-backward"></i></button>

                <button type="button" class="btn btn-default" @click="index = Math.max(0, index - 1)" :disabled="index === 0">
                  <i class="fa fa-backward"></i>
                </button>
                <button type="button" class="btn btn-default" @click="index = Math.min(history.length - 1, index + 1)" :disabled="index === history.length - 1">
                  <i class="fa fa-forward"></i>
                </button>
                <button type="button" class="btn btn-default" @click="index = history.length - 1" :disabled="index === history.length - 1">
                  <i class="fa fa-fast-forward"></i>
                </button>
                <button type="button" class="btn btn-default" @click="showHistory = !showHistory">
                  <i class="fa fa-file-text"></i> Formulário
                </button>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="col-md-2 col-sm-2 col-xs-12 col-lg-3">
  <div class="panel panel-white ui-sortable-handle">
    <div class="panel-heading">
      <div class="row">
        <div class="col-md-12">
          <h3 class="panel-title">Histórico</h3>
          <div class="panel-control">
            <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top"
            title="" data-original-title="Histórico Completo"><i class="icon-docs"
                x-on:click="modalRefracao({{$agendamento->cd_paciente}},null)"></i></a>

           
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="panel-group" role="tablist" aria-multiselectable="true">
 
    <template x-if="REFRACAO.history.length > 0" >
      <div class="panel panel-default" style="border-radius: 0px;">
        
        <template x-for="item in REFRACAO.history" >
         
          <div class="panel-heading" role="tab" id="headHist" style="margin: 1px; padding: 10px; ">
            <h4 class="panel-title" style=" font-size: 12px; cursor: pointer; padding-top:1px; padding-bottom:1px; " 
                x-html=" iconHistry + ' ' + FormatData(item.dt_exame)+ ' -  { ' + item.cd_agendamento + ' } ' "
                x-on:click="modalRefracao({{$agendamento->cd_paciente}},item.cd_refracao)">
            </h4>
          </div>

        </template> 

      </div> 
    </template>


 
  </div>
</div>
