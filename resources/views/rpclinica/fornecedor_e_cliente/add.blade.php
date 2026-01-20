@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Fornecedores e Clientes</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('fornecedor.listar') }}">Relação de Fornecedores e Clientes</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div role="tabpanel">
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tabCadastro">

                        <div class="panel-body">
                            @error('error')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <form role="form" action="{{ route('fornecedor.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Fornecedor/Cliente <span class="red normal">*</span></label>
                                            <input type="text" class="form-control required" required=""
                                                id="fornecedor" value="{{ old('fornecedor') }}" name="fornecedor" maxlength="30"
                                                aria-required="true" placeholder="Fornecedor">
                                            <div id="DivMsg"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="mat-label">Razão Social <span class="red normal"></span></label>
                                            <input type="text" class="form-control required"  
                                                id="razao" value="{{ old('razao') }}" name="razao" maxlength="100"
                                                aria-required="true" placeholder="Razão Social">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="mat-label">Tipo Pessoa <span class="red normal">*</span></label>
                                            <select class="form-control" required="" name="tipo">
                                                <option value="">...</option>
                                                <option value="PF" @if(old('tipo') == 'PF') selected @endif>PESSOA FISICA</option>
                                                <option value="PJ" @if(old('tipo') == 'PJ') selected @endif>PESSOA JURIDICA</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="mat-label">Tipo <span class="red normal">*</span></label>
                                            <select class="form-control" required="" name="tipo_cadastro">
                                                <option value="">...</option>
                                                <option value="F" @if(old('tipo_cadastro') == 'F') selected @endif>FORNECEDOR</option>
                                                <option value="C" @if(old('tipo_cadastro') == 'C') selected @endif>CLIENTE</option>
                                                <option value="A" @if(old('tipo_cadastro') == 'A') selected @endif>AMBOS</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>CPF/CNPJ <span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('documento') }}" name="documento"
                                                maxlength="100" aria-required="true" placeholder="CNPJ/CPF">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>CBO </label>
                                            <input type="text" class="form-control" value="{{ old('cbo') }}" name="cbo"
                                                maxlength="100" aria-required="true" placeholder="CBO">
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Email </label>
                                                <input type="email" class="form-control" value="{{ old('email') }}" name="email"
                                                    maxlength="100" aria-required="true" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Contato de Referência </label>
                                            <input type="text" class="form-control" value="{{ old('contato') }}" name="contato"
                                                maxlength="100" aria-required="true" placeholder="Contato">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label for="fname">Banco </label>
                                                <input type="text" class="form-control" id="banco"
                                                    value="{{ old('banco') }}" name="banco" maxlength="100"
                                                    aria-required="true" placeholder="Banco">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label for="fname">Conta Bancária </label>
                                                <input type="text" class="form-control" id="conta_bancaria"
                                                    value="{{ old('conta_bancaria') }}" name="contabancaria" maxlength="100"
                                                    aria-required="true" placeholder="Conta Bancária">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label for="fname">Agência</label>
                                                <input type="text" class="form-control" id="conta_bancaria"
                                                    value="{{ old('agencia') }}" name="agencia" maxlength="100"
                                                    aria-required="true" placeholder="Agencia">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label for="fname">Tipo de Pix</label>
                                                <select name="tp_pix" id="tp_pix" class="form-control ">
                                                    <option  value="">Nenhuma</option>
                                                    <option @if( old('tp_pix') =='telefone') selected @endif value="telefone" >Telefone</option>
                                                    <option @if( old('tp_pix') =='email') selected @endif value="email" >Email</option>
                                                    <option @if( old('tp_pix') =='chave') selected @endif value="chave" >Chave aleatória</option>
                                                    <option @if( old('tp_pix') =='cpf') selected @endif value="cpf" >cpf/cnpj</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label for="fname">Pix</label>
                                                <input type="text" class="form-control" id="pix"
                                                    value="{{ old('pix') }}" name="pix" maxlength="100"
                                                    aria-required="true" placeholder="Conta Bancária">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row"> 
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Telefone 01 </label>
                                            <input type="text" class="form-control" value="{{ old('telefone') }}" name="telefone"
                                                maxlength="100" aria-required="true" placeholder="Telefone">
                                        </div>
                                    </div> 
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>Celular </label>
                                            <input type="text" class="form-control" value="{{ old('celular') }}" name="celular"
                                                maxlength="100" aria-required="true" placeholder="Celular">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label>WhastsApp </label>
                                            <input type="text" class="form-control" value="{{ old('whast') }}" name="whast"
                                                maxlength="100" aria-required="true" placeholder="WhastsApp">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Cep <span class="red normal"></span></label>
                                                <input type="text" class="form-control" value="{{ old('cep') }}" name="cep"
                                                    maxlength="100" aria-required="true" placeholder="CEP"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Endereço <span class="red normal"></span></label>
                                                <input type="text" class="form-control" value="{{ old('end') }}" name="end"
                                                    maxlength="100" aria-required="true" placeholder="Endereço"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">

                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Numero <span class="red normal"></span></label>
                                                <input type="text" class="form-control" value="{{ old('numero') }}" name="numero"
                                                    maxlength="100" aria-required="true" placeholder="Numero"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Bairro <span class="red normal"></span></label>
                                                <input type="text" class="form-control" value="{{ old('bairro') }}" name="bairro"
                                                    maxlength="100" aria-required="true" placeholder="Bairro"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Cidade <span class="red normal"></span></label>
                                                <input type="text" class="form-control" value="{{ old('cidade') }}" name="cidade"
                                                    maxlength="100" aria-required="true" placeholder="Cidade"
                                                    >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">

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
                                </div>
                                <div class="row" >
                                    <div class="col-md-12">
                                        <label>Observação Adicionais: <span class="red normal"></span></label>
                                        <textarea   class="form-control required" name="obs" rows="4" cols="50">{{ old('obs') }}</textarea>
                                    </div>
                                </div>
                                <br>
                                <div class="box-footer">
                                    <input type="submit" class="btn btn-success" value="Salvar">
                                    <input type="reset" class="btn btn-default" value="Limpar">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Main Wrapper -->
@endsection

@section('script')
@endsection
