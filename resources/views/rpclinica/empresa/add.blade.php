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
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @error('error')
                        <div class="alert alert-danger" role="alert">{{ $message }}</div>
                    @enderror

                    <form role="form" id="addUser" action="{{ route('empresa.store') }}" method="post" role="form" enctype="multipart/form-data">
                        @csrf
                        <input type="file"  name="logo" accept="image/png,image/jpeg" style="opacity:0; height: 0;" class="form-control"  id="logo"  >
                        <input name="fotoCopy" type="text" style="opacity:0; height: 0;" id="fotoCopy">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="logo" style="cursor: pointer; text-align: center;">
                                    <img src="/assets/images/logo_clinic.jpg" width="100%" id="fotopaciente"  style="  max-height: 200px; text-align: center;" >
                                </label> 

                            </div>

                            <div class="col-md-10">

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nome Fantasia: <span class="red normal">*</span></label>
                                            <input type="text" class="form-control required" value="{{ old('nome') }}" name="nome"
                                                maxlength="100" aria-required="true" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Razão Social: <span class="red normal">*</span></label>
                                            <input type="text" class="form-control required" value="{{ old('razao') }}" name="razao"
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
                                                <option value="SN">Simples Nacional</option>
                                                <option value="ME">MEI</option>
                                                <option value="LR">Lucro Real</option>
                                                <option value="LP">Lucro Presumido</option>
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
                                            <input   class="form-control required" value="{{ old('cnpj') }}" name="cnpj"
                                                maxlength="100" aria-required="true" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>
                                                Inscrição Estadual: <span class="red normal"></span>
                                            </label>
                                            <input type="text" class="form-control required" value="{{ old('inscricao_est') }}" name="inscricao_est"
                                                maxlength="100" aria-required="true"  >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>
                                                Inscrição Municipal: <span class="red normal"></span>
                                            </label>
                                            <input type="text" class="form-control required" value="{{ old('inscricao_mun') }}" name="inscricao_mun"
                                                maxlength="100" aria-required="true"  >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Cnes: <span class="red normal"></span>
                                            </label>
                                            <input type="text" class="form-control required" value="{{ old('cnes') }}" name="cnes"
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
                                            <input type="text" class="form-control required" value="{{ old('cep') }}" name="cep"
                                                maxlength="100" aria-required="true"  >
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>
                                                Endereço: <span class="red normal"> </span>
                                            </label>
                                            <input type="text" class="form-control required" value="{{ old('end') }}" name="end"
                                                maxlength="100" aria-required="true"  >
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>
                                                Numero: <span class="red normal"> </span>
                                            </label>
                                            <input type="text" class="form-control required" value="{{ old('numero') }}" name="numero"
                                                maxlength="100" aria-required="true" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>
                                                Bairro: <span class="red normal"> </span>
                                            </label>
                                            <input type="text" class="form-control required" value="{{ old('bairro') }}" name="bairro"
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
                                    <input type="text" class="form-control required" value="{{ old('cidade') }}" name="cidade"
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
                                        <option value="AC" >Acre</option>
                                        <option value="AL" >Alagoas</option>
                                        <option value="AP" >Amapá</option>
                                        <option value="AM" >Amazonas</option>
                                        <option value="BA" >Bahia</option>
                                        <option value="CE" >Ceará</option>
                                        <option value="DF" >Distrito Federal</option>
                                        <option value="GO" >Goiás</option>
                                        <option value="ES" >Espírito Santo</option>
                                        <option value="MA" >Maranhão</option>
                                        <option value="MT" >Mato Grosso</option>
                                        <option value="MS" >Mato Grosso do Sul</option>
                                        <option value="MG" selected="selected" >Minas Gerais</option>
                                        <option value="PA" >Pará</option>
                                        <option value="PB" >Paraiba</option>
                                        <option value="PR" >Paraná</option>
                                        <option value="PE" >Pernambuco</option>
                                        <option value="PI" >Piauí­</option>
                                        <option value="RJ" >Rio de Janeiro</option>
                                        <option value="RN" >Rio Grande do Norte</option>
                                        <option value="RS" >Rio Grande do Sul</option>
                                        <option value="RO" >Rondônia</option>
                                        <option value="RR" >Roraima</option>
                                        <option value="SP" >São Paulo</option>
                                        <option value="SC" >Santa Catarina</option>
                                        <option value="SE" >Sergipe</option>
                                        <option value="TO" >Tocantins</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>
                                        Contato: <span class="red normal"> </span>
                                    </label>
                                    <input type="text" class="form-control required" value="{{ old('end') }}" name="end"
                                        maxlength="100" aria-required="true" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        Email: <span class="red normal"> </span>
                                    </label>
                                    <input type="email" class="form-control required" value="{{ old('end') }}" name="end"
                                        maxlength="100" aria-required="true" required>
                                </div>
                            </div>
                            <div class="col-md-2">

                                <div class="form-group">
                                    <label for="fname" class="mat-label">
                                        Ativo <span class="red normal">*</span>
                                    </label>
                                    <select class="form-control" required="" name="ativo">
                                        <option value="S">Sim</option>
                                        <option value="N">Não</option>
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
                                        value="{{ old('hora_inicial') }}" name="hora_inicial" maxlength="100"
                                        aria-required="true" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Horário Final: <span class="red normal">*</span></label>
                                    <input type="time" class="form-control required" value="{{ old('hora_final') }}"
                                        name="hora_final" maxlength="100" aria-required="true" required>
                                </div>
                            </div>
                            <div class="col-md-1 col-md-offset-1 col-sm-12 col-xs-12 ">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="segunda" @if(old('segunda')) checked @endif value="segunda" class="flat-red"  >
                                        Seg.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="terca" @if(old('terca')) checked @endif value="terca" class="flat-red" >
                                        Ter.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="quarta" @if(old('quarta')) checked @endif value="quarta" class="flat-red"   >
                                        Qua.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="quinta" @if(old('quinta')) checked @endif value="quinta" class="flat-red">
                                        Qui.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="sexta" @if(old('sexta')) checked @endif  value="sexta" class="flat-red">
                                        Sex.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="sabado" @if(old('sabado')) checked @endif   value="sabado" class="flat-red">
                                        Sab.
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="domingo" @if(old('domingo')) checked @endif value="domingo" class="flat-red">
                                        Dom.
                                    </label>
                                </div>
                            </div>

                        </div>
                        
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Salvar" />
                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
        
        </script>

    </div><!-- Main Wrapper -->
@endsection

@section('script')
  


@endsection
