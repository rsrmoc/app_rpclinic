@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Cartão de Credito</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Cadastrar</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
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

                                <form role="form" action="{{ route('cartao.credito.store') }}" method="post">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Descrição do cartão <span class="red normal">*</span></label>
                                                <input type="text" class="form-control required" required=""
                                                    value="{{ old('numero') }}" name="numero" maxlength="30" aria-required="true"
                                                    placeholder="Descrição do cartão">
                                                <div id="DivMsg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-3 col-xs-3">
                                            <div class="form-group">
                                                <label class="mat-label">Dia Fechamento <span
                                                        class="red normal">*</span></label>
                                                <input type="number" class="form-control required" required=""
                                                    value="{{ old('dia_fechamento') }}" name="dia_fechamento" maxlength="100" aria-required="true"
                                                    placeholder="Dia do fechamento">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-3 col-xs-3">
                                            <div class="form-group">
                                                <label for="fname" class="mat-label">Dia Vencimento <span
                                                        class="red normal">*</span></label>
                                                <input type="number" class="form-control required" required=""
                                                    value="{{ old('dia_vencimento') }}" name="dia_vencimento" maxlength="100" aria-required="true"
                                                    placeholder="Dia do vencimento">
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Valor Limite <span class="red normal">*</span></label>
                                                <input onKeyPress="return(moeda(this,'.',',',event))" class="form-control" id="documento"
                                                    value="{{ old('valor_limite') }}"
                                                    name="valor_limite" maxlength="100" aria-required="true"
                                                    placeholder="Valor Limite">
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Resumo </label>
                                                    <select name="resumo" class="form-control">
                                                        <option value="S">Sim</option>
                                                        <option value="N">Não</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
 

                         
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
    </div>
 

@endsection
