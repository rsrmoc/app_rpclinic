@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Convênio</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('convenio.listar') }}">Relação de Convênios</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper" x-data="app">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5>Houve alguns erros:</h5>

                            <ul>
                                {!! implode('', $errors->all('<li>:message</li>')) !!}
                            </ul>
                        </div>
                    @endif

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified m-b-lg" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tabCadastro" role="tab" data-toggle="tab">
                                <i class="fa fa-user m-r-xs"></i> Cadastro
                            </a>
                        </li>

                        

 

                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="tabCadastro">
                            <form role="form" id="formConvenio" action="{{ route('convenio.store') }}" method="post"
                                role="form">
                                @csrf
                                <button type="submit" style="display: none"></button>
                      
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nome: <span class="red normal">*</span></label>
                                            <input type="text" class="form-control required" value="{{ old('nome') }}"
                                                name="nome" maxlength="100" aria-required="true" required>
                                                @if($errors->has('nome'))
                                                <div class="error">{{ $errors->first('nome') }}</div>
                                                @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tipo: <span class="red normal">*</span></label>
                                            <select name="convenio" class="form-control" required>
                                                <option value="">SELECIONE</option>
                                                <option @if (old('convenio') == 'CO') selected @endif value="CO">CONVENIO</option>
                                                <option @if (old('convenio') == 'SUS') selected @endif value="SUS">SUS</option>
                                                <option @if (old('convenio') == 'PA') selected @endif value="PA">PARTICULAR</option>
                                            </select>
                                            @if($errors->has('convenio'))
                                            <div class="error">{{ $errors->first('convenio') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>CNPJ: </label>
                                            <input x-mask="99.999.999/9999-99" class="form-control" value="{{ old('cnpj') }}"
                                                name="cnpj" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Prazo de Retorno: </label>
                                            <input type="number" class="form-control" value="{{ old('prazo_retorno') }}"
                                                name="prazo_retorno" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Registro ANS da Operadora: </label>
                                            <input type="text" class="form-control " name="ans"
                                                maxlength="100" aria-required="true" value="{{ old('ans') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Prazo das Guias: </label>
                                            <input type="number" class="form-control " name="prazo_guia"
                                                maxlength="100" aria-required="true" value="{{ old('prazo_guia') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label>Endereço: </label>
                                            <input type="text" class="form-control " value="{{ old('endereco') }}" name="endereco"
                                                maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email: </label>
                                            <input type="email" class="form-control " value="{{ old('email') }}" name="email"
                                                maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Data Contrato: </label>
                                            <input type="date" class="form-control " value="{{ old('data_contrato') }}" name="data_contrato"
                                                maxlength="100" aria-required="true">
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Telefone: </label>
                                            <input type="tel" class="form-control " value="{{ old('telefone') }}" name="telefone"
                                                maxlength="100" aria-required="true" x-mask="(99)9999-9999">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fatura Sancoop: <span class="red normal">*</span></label>
                                            <select name="faturasancoop" class="form-control" required>
                                                <option value="">SELECIONE</option>
                                                <option @if (old('faturasancoop') == 'S') selected @endif value="S">SIM</option>
                                                <option @if (old('faturasancoop') == 'N') selected @endif value="N">NÃO</option>
                                            </select>
                                            @if($errors->has('faturasancoop'))
                                            <div class="error">{{ $errors->first('faturasancoop') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Link da Plataforma: </label>
                                            <input type="tel" class="form-control " value="{{ old('link_autorizacao') }}" name="link_autorizacao"
                                                maxlength="255" aria-required="true"  >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Usuário da Plataforma: </label>
                                            <input type="tel" class="form-control " value="{{ old('user_autorizacao') }}" name="user_autorizacao"
                                                maxlength="255" aria-required="true"  >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Senha da Plataforma: </label>
                                            <input type="tel" class="form-control " value="{{ old('senha_autorizacao') }}" name="senha_autorizacao"
                                                maxlength="255" aria-required="true"  >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Observação</label> 
                                        <textarea rows="4" class="form-control" name="obs" >{{ old('obs') }}</textarea>
                                    </div>
                                    <hr> 
                                </div>
                                <br> 

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Financeiro</h3>
                                    </div>
                                    <div class="panel-body">
                                        <br>
                                        <div class="row">
                                         
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label>Cliente: <span class="red normal"></span></label>
                                                    <select name="cliente" class="form-control"  >
                                                        <option value="">SELECIONE</option>
                                                        @foreach ($fornecedor as $forn)
                                                            <option value="{{ $forn->cd_fornecedor }}"
                                                                @if(old('cliente')==$forn->cd_fornecedor) selected  @endif 
                                                                >
                                                                {{  $forn->nm_fornecedor }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('cliente'))
                                                    <div class="error">{{ $errors->first('cliente') }}</div>
                                                    @endif
                                                </div>
                                            </div> 
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Categoria: <span class="red normal"></span></label>
                                                    <select name="categoria" class="form-control"  >
                                                        <option value="">SELECIONE</option>
                                                        @foreach ($categoria as $linha)
                                                            <option value="{{ $linha->cd_categoria }}"
                                                                @if(old('categoria')==$linha->cd_categoria) selected  @endif 
                                                                >
                                                                {{  $linha->nm_categoria }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('categoria'))
                                                    <div class="error">{{ $errors->first('categoria') }}</div>
                                                    @endif
                                                </div>
                                            </div> 
 
                                        </div>

                                    </div>
                                </div>
 
                                <template x-for="entrada, indice in entradasProcedimentos">
                                    <div>
                                        <input type="hidden" x-bind:name="`procedimentos[${indice}][cd_procedimento]`"
                                            x-bind:value="entrada.cd_procedimento" />
                                        <input type="hidden" x-bind:name="`procedimentos[${indice}][dt_vigencia]`"
                                            x-bind:value="entrada.dt_vigencia" />
                                        <input type="hidden" x-bind:name="`procedimentos[${indice}][valor]`"
                                            x-bind:value="entrada.valor" />
                                    </div>
                                </template>
                            </form>
                        </div>

               
                    </div>
                </div>

                <div class="panel-footer">
                    <input type="submit" class="btn btn-success" value="Salvar" x-on:click="submitConvenio" />
                    <input type="reset" class="btn btn-default" value="Limpar" x-on:click="limpar" />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const procedimentos = @js($procedimentos);
    </script>
    <script src="{{ asset('js/rpclinica/convenio.js') }}"></script>
@endsection
