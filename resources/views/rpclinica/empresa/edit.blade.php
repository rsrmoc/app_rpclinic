@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">

        <h3>Cadastro de Empresa</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Cadastrar</a></li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper"  x-data="appFormulario">

  
        <div class="col-md-12 ">  
            <div class="panel panel-white">

                @error('error')
                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        <li role="presentation" class="active"><a href="#tabCadastrais" role="tab" data-toggle="tab">Dados Cadastrais</a></li>
                        <li role="presentation"><a href="#tabConfig" role="tab" data-toggle="tab">Configuração</a></li> 
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active fade in" id="tabCadastrais">

                            <div class="panel-body">

            
                                <form role="form" id="addUser"
                                    action="{{ route('empresa.update', ['empresa' => $empresa->cd_empresa]) }}"
                                    method="post" role="form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file"  name="logo" accept="image/png,image/jpeg" style="opacity:0; height: 0;" class="form-control"  id="logo"  >
                                    <input type="file"  name="logo-mini" accept="image/png,image/jpeg" style="opacity:0; height: 0;" class="form-control"  id="logo-mini"  >
                                    <input name="fotoCopy" type="text" style="opacity:0; height: 0;" id="fotoCopy">
                                    <div class="row">
                             
            
                                        <div class="col-md-2" style="text-align: center">
                                            <label for="logo" style="cursor: pointer; text-align: center">
            
                                              @if ($empresa->logo)
                                              <img src = "data:{{$empresa->type_logo}};base64,{{$empresa->logo}}" width="100%" id="fotopaciente" style="  max-height: 200px; text-align: center; " >
                                               @else
                                               <img src="/assets/images/logo_clinic.jpg" width="100%" id="fotopaciente" style="  max-height: 130px; text-align: center;" >
                                               @endif  
                                            </label> 
                                            <hr> 
                                            <label for="logo-mini" style="cursor: pointer; text-align: center">
                                              
                                                @if ($empresa->mini_logo)
                                                         
                                                    <img src = "data:{{$empresa->type_mini_logo}};base64,{{$empresa->mini_logo}}" width="100%" id="fotopaciente" style="  max-height: 50px; text-align: center; " >
                                                 @else
                                                     
                                                 <img src="/assets/images/logo_clinic.jpg"     style="  max-height: 50px; text-align: center;" >
                                                 @endif  
                                            </label> 
            
                                        </div>
            
                                        <div class="col-md-10">
            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Nome Fantasia: <span class="red normal">*</span></label>
                                                        <input type="text" class="form-control required"
                                                            value="{{ $empresa->nm_empresa }}"
                                                            name="nome"
                                                            maxlength="100" aria-required="true" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>Razão Social: <span class="red normal">*</span></label>
                                                        <input type="text" class="form-control required"
                                                            value="{{ $empresa->razao_social }}"
                                                            name="razao"
                                                            maxlength="100" aria-required="true" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
            
                                                    <div class="form-group">
                                                        <label for="fname" class="mat-label">
                                                            Regime de Tributação <span class="red normal">*</span>
                                                        </label>
                                                        <select class="form-control" required="" name="regime">
                                                            <option value="">Selecione</option>
                                                            <option value="SN" @if($empresa->regime == 'SN') selected @endif>Simples Nacional</option>
                                                            <option value="ME" @if($empresa->regime == 'ME') selected @endif>MEI</option>
                                                            <option value="LR" @if($empresa->regime == 'LR') selected @endif>Lucro Real</option>
                                                            <option value="LP" @if($empresa->regime == 'LP') selected @endif>Lucro Presumido</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>
                                                            CNPJ: <span class="red normal">*</span>
                                                        </label>
                                                        <input   class="form-control required"
                                                            value="{{ $empresa->cnpj }}"
                                                            name="cnpj"
                                                            maxlength="100" aria-required="true" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>
                                                            Inscrição Estadual: <span class="red normal"></span>
                                                        </label>
                                                        <input type="text" class="form-control required"
                                                            value="{{ $empresa->insc_estadual }}"
                                                            name="inscricao_est"
                                                            maxlength="100" aria-required="true" >
                                                    </div>
                                                </div>
            
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>
                                                            Inscrição Municipal: <span class="red normal"></span>
                                                        </label>
                                                        <input type="text" class="form-control required"
                                                            value="{{  old('cnes',$empresa->insc_municipal) }}"
                                                            name="inscricao_mun"
                                                            maxlength="100" aria-required="true" >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Cnes: <span class="red normal"></span>
                                                        </label>
                                                        <input type="text" class="form-control required" value="{{ old('cnes',$empresa->cnes) }}" name="cnes"
                                                            maxlength="100" aria-required="true" >
                                                    </div>
                                                </div>
                                              
                                            </div>
            
                                            <div class="row" > 
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>
                                                            CEP: <span class="red normal"> </span>
                                                        </label>
                                                        <input type="text" class="form-control required" value="{{ old('cep',$empresa->cep) }}" name="cep"
                                                            maxlength="100" aria-required="true"  >
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>
                                                            Endereço: <span class="red normal"> </span>
                                                        </label>
                                                        <input type="text" class="form-control required" value="{{ old('end',$empresa->end) }}" name="end"
                                                            maxlength="100" aria-required="true"  >
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>
                                                            Numero: <span class="red normal"> </span>
                                                        </label>
                                                        <input type="text" class="form-control required" value="{{ old('numero',$empresa->numero) }}" name="numero"
                                                            maxlength="100" aria-required="true"  >
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>
                                                            Bairro: <span class="red normal"> </span>
                                                        </label>
                                                        <input type="text" class="form-control required" value="{{ old('bairro',$empresa->bairro) }}" name="bairro"
                                                            maxlength="100" aria-required="true"  >
                                                    </div>
                                                </div>
            
            
                                            </div>
                                           
            
            
            
                                        </div>
                                    </div>
            
                                    <div class="row" >
                                                
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>
                                                    Cidade: <span class="red normal"> </span>
                                                </label>
                                                <input type="text" class="form-control required" value="{{ old('cidade',$empresa->cidade) }}" name="cidade"
                                                    maxlength="100" aria-required="true"  >
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
            
                                            <div class="form-group">
                                                <label for="fname" class="mat-label">
                                                    UF <span class="red normal"></span>
                                                </label>
                                                <select class="form-control" required="" name="uf">
                                                    <option value="">Selecione</option>
                                                    <option value="AC" @if($empresa->uf == 'AC') selected @endif >Acre</option>
                                                    <option value="AL" @if($empresa->uf == 'AL') selected @endif >Alagoas</option>
                                                    <option value="AP" @if($empresa->uf == 'AP') selected @endif >Amapá</option>
                                                    <option value="AM" @if($empresa->uf == 'AM') selected @endif >Amazonas</option>
                                                    <option value="BA" @if($empresa->uf == 'BA') selected @endif >Bahia</option>
                                                    <option value="CE" @if($empresa->uf == 'CE') selected @endif >Ceará</option>
                                                    <option value="DF" @if($empresa->uf == 'DF') selected @endif >Distrito Federal</option>
                                                    <option value="GO" @if($empresa->uf == 'GO') selected @endif >Goiás</option>
                                                    <option value="ES" @if($empresa->uf == 'ES') selected @endif >Espírito Santo</option>
                                                    <option value="MA" @if($empresa->uf == 'MA') selected @endif >Maranhão</option>
                                                    <option value="MT" @if($empresa->uf == 'MT') selected @endif >Mato Grosso</option>
                                                    <option value="MS" @if($empresa->uf == 'MS') selected @endif >Mato Grosso do Sul</option>
                                                    <option value="MG" @if($empresa->uf == 'MG') selected @endif >Minas Gerais</option>
                                                    <option value="PA" @if($empresa->uf == 'PA') selected @endif >Pará</option>
                                                    <option value="PB" @if($empresa->uf == 'PB') selected @endif >Paraiba</option>
                                                    <option value="PR" @if($empresa->uf == 'PR') selected @endif >Paraná</option>
                                                    <option value="PE" @if($empresa->uf == 'PE') selected @endif >Pernambuco</option>
                                                    <option value="PI" @if($empresa->uf == 'PI') selected @endif >Piauí­</option>
                                                    <option value="RJ" @if($empresa->uf == 'RJ') selected @endif >Rio de Janeiro</option>
                                                    <option value="RN" @if($empresa->uf == 'RN') selected @endif >Rio Grande do Norte</option>
                                                    <option value="RS" @if($empresa->uf == 'RS') selected @endif >Rio Grande do Sul</option>
                                                    <option value="RO" @if($empresa->uf == 'RO') selected @endif >Rondônia</option>
                                                    <option value="RR" @if($empresa->uf == 'RR') selected @endif >Roraima</option>
                                                    <option value="SP" @if($empresa->uf == 'SP') selected @endif >São Paulo</option>
                                                    <option value="SC" @if($empresa->uf == 'SC') selected @endif >Santa Catarina</option>
                                                    <option value="SE" @if($empresa->uf == 'SE') selected @endif >Sergipe</option>
                                                    <option value="TO" @if($empresa->uf == 'TO') selected @endif >Tocantins</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>
                                                    Contato: <span class="red normal"> </span>
                                                </label>
                                                <input type="text" class="form-control required" value="{{ old('contato',$empresa->contato) }}" name="contato"
                                                    maxlength="100" aria-required="true"  >
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>
                                                    Email: <span class="red normal"> </span>
                                                </label>
                                                <input type="email" class="form-control required" value="{{ old('email',$empresa->email) }}" name="email"
                                                    maxlength="100" aria-required="true"  >
                                            </div>
                                        </div>
                                        <div class="col-md-2">
            
                                            <div class="form-group">
                                                <label for="fname" class="mat-label">
                                                    Ativo <span class="red normal">*</span>
                                                </label>
                                                <select class="form-control" required="" name="ativo">
                                                    <option value="S" @if($empresa->sn_ativo == 'S') selected @endif>Sim</option>
                                                    <option value="N" @if($empresa->sn_ativo == 'N') selected @endif>Não</option>
                                                </select>
                                            </div>
                                        </div>
            
                                    </div>
            
                                    <div class="timeline-options"> 
                                        <div class="panel-title">Dados do Agendamento</div>
                                    </div>
            
                                    <div class="row" style="margin-bottom: 10px; margin-top: 15px;">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Horário Inicial: <span class="red normal">*</span></label>
                                                <input type="time" class="form-control required"
                                                    value="{{ old('hora_inicial',$empresa->hr_inicial) }}" name="hora_inicial" maxlength="100"
                                                    aria-required="true" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Horário Final: <span class="red normal">*</span></label>
                                                <input type="time" class="form-control required" value="{{ old('hora_final',$empresa->hr_final) }}"
                                                    name="hora_final" maxlength="100" aria-required="true" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12 col-xs-12  col-md-offset-1">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="segunda" @if(old('segunda',$empresa->segunda))  checked @endif value="segunda" class="flat-red"  >
                                                    Seg.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="terca" @if(old('terca',$empresa->terca)) checked @endif value="terca" class="flat-red" >
                                                    Ter.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="quarta" @if(old('quarta',$empresa->quarta)) checked @endif value="quarta" class="flat-red"   >
                                                    Qua.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="quinta" @if(old('quinta',$empresa->quinta)) checked @endif value="quinta" class="flat-red">
                                                    Qui.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="sexta" @if(old('sexta',$empresa->sexta)) checked @endif  value="sexta" class="flat-red">
                                                    Sex.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="sabado" @if(old('sabado',$empresa->sabado)) checked @endif   value="sabado" class="flat-red">
                                                    Sab.
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>
                                                    <input type="checkbox" name="domingo" @if(old('domingo',$empresa->domingo)) checked @endif value="domingo" class="flat-red">
                                                    Dom.
                                                </label>
                                            </div>
                                        </div>
            
                                    </div>
            
                                    <div class="box-footer">
                                        <input type="submit" class="btn btn-success" value="Salvar" />
                                        <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                                    </div>
            
                                    <div class="timeline-options"> 
                                        <div class="panel-title">Cadastro de Mensagens</div>
                                    </div>
            
                                </form>
            
                                    <div class="row" > 
            
                                        <form role="form"    style="margin-top: 20px"
                                        action="{{ route('empresa.update.msg', ['empresa' => $empresa->cd_empresa]) }}"
                                        method="post" role="form" >
                                        @csrf
                                            <input type="hidden" name="tipo" value="AG">
                                            <div class="col-md-4" style="margin-top: 20px">
                                                <div class="form-group">
                                                    <label>Confirmação de Agendamento: <span class="red normal"> </span>
                                                    </label>
                                                    <textarea class="form-control required" style="height: 250px" name="texto"  >{{ old('texto',utf8_decode($empresa->msg_agendamento)) }}</textarea>

                                                    <div class="input-group m-b-sm" style="margin-top: 10px;">
                                                        <span class="input-group-addon">
                                                            <div class="checker"><span class="">
                                                                <input type="checkbox" name="sn_agendamento" value="sim" @if($empresa->sn_agendamento == 'sim') checked @endif  ></span>
                                                            </div>
                                                        </span>
                                                        <select class="form-control"   name="situacao_agendamento">
                                                            <option value="" >...</option>
                                                            @foreach($situacao as $key => $val)
                                                                <option value="{{$val->cd_situacao}}" @if($empresa->situacao_agendamento == $val->cd_situacao) selected @endif>{{$val->nm_situacao}}</option>
                                                            @endforeach 
                                                        </select>
                                                    </div>

                                                    <code>@PACIENTE</code> <code>@PROFISSIONAL</code> <code>@DR_DRA</code> <code>@DATA</code> <code>@HR_AGENDAMENTO</code> <code>@DO_DA</code> 

                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-github m-b-xs"><i style="margin-right: 5px;" class="fa fa-save"></i> Salvar </button>
                                                </div>
                                            </div>
            
                                        </form>
 
                                        <form role="form"    style="margin-top: 20px"
                                        action="{{ route('empresa.update.msg', ['empresa' => $empresa->cd_empresa]) }}"
                                        method="post" role="form" >
                                        @csrf
                                            <input type="hidden" name="tipo" value="AC">
                                            <div class="col-md-4" style="margin-top: 20px">
                                                <div class="form-group">
                                                    <label>Agendamento Confirmado: <span class="red normal"> </span>
                                                    </label>
                                                    <textarea class="form-control required" style="height: 250px" name="texto"  >{{ old('texto',utf8_decode($empresa->msg_ag_confirm)) }}</textarea>

                                                    <div class="input-group m-b-sm" style="margin-top: 10px;">
                                                        <span class="input-group-addon">
                                                            <div class="checker"><span class="">
                                                                <input type="checkbox" name="sn_ag_confirm" value="sim" @if($empresa->sn_ag_confirm == 'sim') checked  @endif ></span>
                                                            </div>
                                                        </span>
                                                        <select class="form-control"   name="situacao_ag_confirm">
                                                            <option value="" >...</option>
                                                            @foreach($situacao as $key => $val)
                                                                <option value="{{$val->cd_situacao}}" @if($empresa->situacao_ag_confirm == $val->cd_situacao) selected @endif>{{$val->nm_situacao}}</option>
                                                            @endforeach 
                                                        </select>
                                                    </div>

                                                    <code>@PACIENTE</code> <code>@PROFISSIONAL</code> <code>@DATA</code> <code>@HR_AGENDAMENTO</code>

                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-github m-b-xs"><i style="margin-right: 5px;" class="fa fa-save"></i> Salvar </button>
                                                </div>
                                            </div>
            
                                        </form>

                                        
                                        
                                        <form role="form"    style="margin-top: 20px"
                                        action="{{ route('empresa.update.msg', ['empresa' => $empresa->cd_empresa]) }}"
                                        method="post" role="form" >
                                        @csrf
                                            <input type="hidden" name="tipo" value="AA">
                                            <div class="col-md-4" style="margin-top: 20px">
                                                <div class="form-group">
                                                    <label>Agendamento Cancelado: <span class="red normal"> </span>
                                                    </label>
                                                    <textarea class="form-control required" style="height: 250px" name="texto"  >{{ old('texto',utf8_decode($empresa->msg_ag_cancel)) }}</textarea>

                                                    <div class="input-group m-b-sm" style="margin-top: 10px;">
                                                        <span class="input-group-addon">
                                                            <div class="checker"><span class="">
                                                                <input type="checkbox" name="sn_ag_cancel" value="sim" @if($empresa->sn_ag_cancel == 'sim') checked @endif ></span>
                                                            </div>
                                                        </span>
                                                        <select class="form-control"   name="situacao_ag_cancel">
                                                            <option value="" >...</option> 
                                                            @foreach($situacao as $key => $val)
                                                                <option value="{{$val->cd_situacao}}" @if($empresa->situacao_ag_cancel == $val->cd_situacao) selected @endif>{{$val->nm_situacao}}</option>
                                                            @endforeach 
                                                        </select>
                                                    </div> 
                                                    <code>@PACIENTE</code> <code>@PROFISSIONAL</code> <code>@DATA</code> <code>@HR_AGENDAMENTO</code>

                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-github m-b-xs"><i style="margin-right: 5px;" class="fa fa-save"></i> Salvar </button>
                                                </div>
                                            </div>
            
                                        </form>

                                        <form role="form"    style="margin-top: 20px"
                                        action="{{ route('empresa.update.msg', ['empresa' => $empresa->cd_empresa]) }}"
                                        method="post" role="form" >
                                        @csrf
                                            <input type="hidden" name="tipo" value="FA">
                                            <div class="col-md-4"  style="margin-top: 20px">
                                                <div class="form-group">
                                                    <label>Faltou: <span class="red normal"> </span>
                                                    </label>
                                                    <textarea class="form-control required" style="height: 250px" name="texto"  >{{ old('texto',utf8_decode($empresa->msg_faltou)) }}</textarea>

                                                    <div class="input-group m-b-sm" style="margin-top: 10px;">
                                                        <span class="input-group-addon">
                                                            <div class="checker"><span class="">
                                                                <input type="checkbox" name="sn_faltou" value="sim" @if($empresa->sn_faltou == 'sim') checked @endif ></span>
                                                            </div>
                                                        </span>
                                                        <select class="form-control"   name="situacao_faltou">
                                                            <option value="" >...</option> 
                                                            @foreach($situacao as $key => $val)
                                                                <option value="{{$val->cd_situacao}}" @if($empresa->situacao_faltou == $val->cd_situacao) selected @endif>{{$val->nm_situacao}}</option>
                                                            @endforeach 
                                                        </select>
                                                    </div> 
                                                    <code>@PACIENTE</code> <code>@PROFISSIONAL</code> <code>@DATA</code> <code>@HR_AGENDAMENTO</code>

                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-github m-b-xs"><i style="margin-right: 5px;" class="fa fa-save"></i> Salvar </button>
                                                </div>
                                            </div>
            
                                        </form>

                                                    
                                        <form role="form"    style="margin-top: 20px"
                                        action="{{ route('empresa.update.msg', ['empresa' => $empresa->cd_empresa]) }}"
                                        method="post" role="form" >
                                        @csrf
                                            <input type="hidden" name="tipo" value="LA"> 
                                            <div class="col-md-4" style="margin-top: 20px">
                                                <div class="form-group">
                                                    <label>Laudo: <span class="red normal"> </span>
                                                    </label>
                                                    <textarea class="form-control required" style="height: 250px" name="texto"  >{{ old('texto',utf8_decode($empresa->msg_laudo)) }}</textarea>

                                                    <div class="input-group m-b-sm" style="margin-top: 10px;">
                                                        <span class="input-group-addon">
                                                            <div class="checker"><span class="">
                                                                <input type="checkbox" name="sn_laudo" value="sim" @if($empresa->sn_laudo == 'sim') checked @endif ></span>
                                                            </div>
                                                        </span>
                                                        <select class="form-control"   name="situacao_laudo">
                                                            <option value="" >...</option> 
                                                            @foreach($situacaoItens as $key => $val)
                                                                <option value="{{$val->cd_situacao_itens}}" @if($empresa->situacao_laudo == $val->cd_situacao_itens) selected @endif>{{$val->nm_situacao_itens}}</option>
                                                            @endforeach 
                                                        </select>
                                                    </div>

                                                    <code>@PACIENTE</code> <code>@PROFISSIONAL</code> <code>@EXAME</code> <code>@NOME_FANTASIA</code>
                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-github m-b-xs"><i style="margin-right: 5px;" class="fa fa-save"></i> Salvar </button>
                                                </div>
                                            </div>
                                        </form>
            
                                        <form role="form"  style="margin-top: 20px" enctype="multipart/form-data"
                                        action="{{ route('empresa.update.msg', ['empresa' => $empresa->cd_empresa]) }}"
                                        method="post" role="form" >
                                        @csrf
                                            <input type="hidden" name="tipo" value="PE">
                                            <div class="col-md-4" style="margin-top: 20px">
                                                <div class="form-group">
                                                    <label>Pesquisa de Satisfação: <span class="red normal"> </span>
                                                    </label>

                                                    <textarea class="form-control required" style="height: 250px" name="texto"  >{{ old('texto',utf8_decode($empresa->pesquisa_satisfacao)) }}</textarea>
 
                                                    <div  style="margin-top: 10px;">
                                                    <input type="file"  name="img_pesquisa" accept="image/png,image/jpeg"  class="form-control"  >
                                                    </div>
                                                    <div class="input-group m-b-sm" style="margin-top: 10px;">
                                                        <span class="input-group-addon">
                                                            <div class="checker"><span class="">
                                                                <input type="checkbox" value="sim" name="sn_pesquisa" @if($empresa->sn_pesquisa == 'sim') checked @endif ></span></div>
                                                        </span>
                                                        <select class="form-control"  name="situacao_pesquisa">
                                                            <option value="" >...</option> 
                                                            @foreach($situacao as $key => $val)
                                                                <option value="{{$val->cd_situacao}}" @if($empresa->situacao_pesquisa == $val->cd_situacao) selected @endif>{{$val->nm_situacao}}</option>
                                                            @endforeach 
                                                        </select>
                                                    </div>
                                                    <code>@PACIENTE</code> <code>@PROFISSIONAL</code>
                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-github m-b-xs"><i style="margin-right: 5px;" class="fa fa-save"></i> Salvar </button>
                                                </div>
                                            </div>
                                        </form>

                                        <form role="form"    style="margin-top: 20px"
                                        action="{{ route('empresa.update.msg', ['empresa' => $empresa->cd_empresa]) }}"
                                        method="post" role="form" >
                                        @csrf
                                            <input type="hidden" name="tipo" value="NI">
                                            <div class="col-md-4" style="margin-top: 20px">
                                                <div class="form-group">
                                                    <label>Aniversário: <span class="red normal"> </span>
                                                    </label>
                                                    <textarea class="form-control required" style="height: 250px" name="texto"  >{{ old('texto',  utf8_decode($empresa->msg_niver) ) }}</textarea>

                                                    <div class="checkbox">
                                                        <label>
                                                            <div class="checker"><span><input type="checkbox" value="sim" name="sn_niver" @if($empresa->sn_niver == 'sim') checked @endif name="sn_niver"></span></div>Habilitar Rotina
                                                        </label>
                                                    </div>
                                                    <code>@NOME</code>  
                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-github m-b-xs"><i style="margin-right: 5px;" class="fa fa-save"></i> Salvar </button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="col-md-4" style="margin-top: 20px">
                                        </div>
                                        <div class="col-md-4" style="margin-top: 20px; text-align: center; ">
                                            @if($empresa->logo_pesq_satisf)
                                                <div class="modal-header">
                                                    <button type="button" class="close" x-on:click="deleteImgPesquisa()"><span aria-hidden="true">×</span></button>
                                                    <h4 class="modal-title" id="myModalLabel">Imagem Pesquisa de Satisfação</h4>
                                                </div>
                                                <img src = "data:{{$empresa->type_logo_pesq_satisf}};base64,{{$empresa->logo_pesq_satisf}}"    style="  max-height: 350px; text-align: center; " >
                                            @endif
                                        </div>
                                        
            
                                    </div>
            
            
                            </div>

                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tabConfig">

                            <form role="form"  
                            action="{{ route('empresa.update.config', ['empresa' => $empresa->cd_empresa]) }}"
                            method="post" role="form" >
                                @csrf 
                                <div class="row" style="margin-top: 40px;"> 
         
                                    <div class="col-md-12">
        
                                        <div class="row"> 
                                            <div class="col-md-2"> 
                                                <div class="form-group">
                                                    <label for="fname" class="mat-label">
                                                       <br> Atendimento Externo <span class="red normal">*</span>
                                                    </label>
                                                    <select class="form-control" required="" style="width: 100%" name="atend_externo"> 
                                                        <option value="N" @if($empresa->atend_externo == 'N') selected @endif>Não</option>
                                                        <option value="S" @if($empresa->atend_externo == 'S') selected @endif>Sim</option> 
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2"> 
                                                <div class="form-group">
                                                    <label for="fname" class="mat-label">
                                                        Obrigar informar item no Agendamento <span class="red normal">*</span>
                                                    </label>
                                                    <select class="form-control" required="" style="width: 100%" name="sn_item_agendamento"> 
                                                        <option value="N" @if($empresa->sn_item_agendamento == 'N') selected @endif>Não</option>
                                                        <option value="S" @if($empresa->sn_item_agendamento == 'S') selected @endif>Sim</option> 
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2"> 
                                                <div class="form-group">
                                                    <label for="fname" class="mat-label">
                                                        <br> Pre Exame <span class="red normal">*</span>
                                                    </label>
                                                    <select class="form-control" required="" style="width: 100%" name="sn_pre_exame"> 
                                                        <option value="NAO" @if($empresa->sn_pre_exame == 'NAO') selected @endif>Não</option>
                                                        <option value="SIM" @if($empresa->sn_pre_exame == 'SIM') selected @endif>Sim</option> 
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2"> 
                                                <div class="form-group">
                                                    <label for="fname" class="mat-label">
                                                        <br> Recibo Atendimento <span class="red normal">*</span>
                                                    </label>
                                                    <select class="form-control" required="" style="width: 100%" name="recibo_atendimento"> 
                                                        <option value="geral" @if($empresa->recibo_atendimento == 'geral') selected @endif>Geral</option>
                                                        <option value="oftalmo" @if($empresa->recibo_atendimento == 'oftalmo') selected @endif>Oftalmo</option> 
                                                    </select>
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><br> Link Notícias: <span class="red normal"> </span></label>
                                                    <input type="text" class="form-control required"
                                                        value="{{ $empresa->link_noticias }}"
                                                        name="link_noticias"
                                                        maxlength="100" aria-required="true" >
                                                </div>
                                            </div>
                                            
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="timeline-options"> 
                                                    <div class="panel-title">Cadastro de Paciente</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="fname" class="mat-label">
                                                                <br> Obriga CPF <span class="red normal">*</span>
                                                            </label>
                                                            <select class="form-control" required="" style="width: 100%" name="obriga_cpf"> 
                                                                <option value="sim" @if($empresa->obriga_cpf == 'sim') selected @endif>Sim</option>
                                                                <option value="nao" @if($empresa->obriga_cpf == 'nao') selected @endif>Não</option> 
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="fname" class="mat-label">
                                                                <br> Valida CPF <span class="red normal">*</span>
                                                            </label>
                                                            <select class="form-control" required="" style="width: 100%" name="valida_cpf"> 
                                                                <option value="sim" @if($empresa->valida_cpf == 'sim') selected @endif>Sim</option>
                                                                <option value="nao" @if($empresa->valida_cpf == 'nao') selected @endif>Não</option> 
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="timeline-options"> 
                                                    <div class="panel-title">Configuração do Prontuario Eletrônico</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>
                                                               Bloqueio do Prontuário (dias): <span class="red normal">*</span>
                                                            </label>
                                                            <input   class="form-control required"
                                                                value="{{ $empresa->tempo_prontuario_dia }}"
                                                                name="tempo_prontuario_dia"
                                                                maxlength="100" aria-required="true" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4"> 
                                                        <div class="form-group">
                                                            <label for="fname" class="mat-label">
                                                                <br>  Editor HTML<span class="red normal">*</span>
                                                            </label>
                                                            <select class="form-control" required="" style="width: 100%" name="tp_editor_html"> 
                                                                <option value="sim" @if($empresa->tp_editor_html == 'sim') selected @endif>Sim</option>
                                                                <option value="nao" @if($empresa->tp_editor_html == 'nao') selected @endif>Não</option> 
                                                            </select>
                                                        </div>
                                                    </div>
                
                                                    <div class="col-md-4"> 
                                                        <div class="form-group">
                                                            <label for="fname" class="mat-label">
                                                                <br> Tipo de Prontuario<span class="red normal">*</span>
                                                            </label>
                                                            <select class="form-control" required="" style="width: 100%" name="tp_prontuario_eletronico"> 
                                                                <option value="consultorio" @if($empresa->tp_prontuario_eletronico == 'consultorio') selected @endif>Geral</option>
                                                                <option value="oftalmo" @if($empresa->tp_prontuario_eletronico == 'oftalmo') selected @endif>Oftalmo</option> 
                                                            </select>
                                                        </div>
                                                    </div> 
                                                
                                                </div>
                                            </div>
                                        </div>

 
                                        <div class="timeline-options"> 
                                            <div class="panel-title">Api do WhatsApp</div>
                                        </div>
        
                                        <div class="row" >  
                                            <div class="col-md-2"> 
                                                <div class="form-group">
                                                    <label for="fname" class="mat-label">
                                                        Api WhatsApp<span class="red normal"></span>
                                                    </label>
                                                    <select class="form-control"   style="width: 100%" name="api_whast"> 
                                                        <option value="" >...</option> 
                                                        <option value="api-wa.me" @if($empresa->api_whast == 'api-wa.me') selected @endif>RPCLINIC</option> 
                                                        <option value="kentro" @if($empresa->api_whast == 'kentro') selected @endif>KENTRO</option> 
                                                    </select>
                                                </div>
                                            </div>
        
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>
                                                        Key: <span class="red normal"> </span>
                                                    </label>
                                                    <input type="text" class="form-control required" value="{{ old('cep',$empresa->key_whast) }}" name="key_whast"
                                                        maxlength="100" aria-required="true"  >
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>
                                                        Fila (Kentro): <span class="red normal"> </span>
                                                    </label>
                                                    <input type="number" class="form-control required" value="{{ old('fila_whast',$empresa->fila_whast) }}" name="fila_whast"
                                                        maxlength="100" aria-required="true"  >
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>
                                                        Url (Kentro): <span class="red normal"> </span>
                                                    </label>
                                                    <input type="text" class="form-control required" value="{{ old('url_whast',$empresa->url_whast) }}" name="url_whast"
                                                        maxlength="100" aria-required="true"  >
                                                </div>
                                            </div>
        
                                        </div>
                                        <div class="box-footer">
                                            <input type="submit" class="btn btn-success" value="Salvar">
                                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()">
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                         
                    </div>
                </div>










            </div>
        </div>


    </div>
@endsection

@section('scripts')

<script>
 
    var foto = null;
    var fotoCopy = null;
 
    $("#logo").on('change', function() {
        foto = $("#logo")[0].files[0];

        var ler = new FileReader();

        ler.onload = function(e) {
            $('#fotoCopy').val(e.target.result);
            $('#fotopaciente').attr('src', e.target.result);
            
        }

        ler.readAsDataURL($("#logo")[0].files[0]);
    });
    const empresa = @js($empresa->cd_empresa);

</script>

<script src="{{ asset('js/rpclinica/empresa.js') }}"></script>

@endsection
