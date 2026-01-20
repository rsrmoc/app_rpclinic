@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Formas de Pagamento</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Cadastrar</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div  x-data="appLancamentos" class="panel panel-white">
                <div class="panel-body">
                    @error('error')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form role="form" action="{{ route('forma.pag.store') }}" method="post" role="form">
                        
                        @csrf

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="fname">Descrição: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required" required id="formadepagamento"
                                        value="{{ old('descricao') }}" name="descricao" maxlength="100" aria-required="true">
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Tipo <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="tipo">
                                        <option value="">Selecione</option>
                                        <option value="BO" @if(old('tipo')=="BO") selected  @endif >Boleto</option>
                                        <option value="CA" @if(old('tipo')=="CA") selected  @endif >Cartão</option>
                                        <option value="CH" @if(old('tipo')=="CH") selected  @endif >Cheque</option>
                                        <option value="DI" @if(old('tipo')=="DI") selected  @endif >Dinheiro</option>
                                        <option value="PI" @if(old('tipo')=="PI") selected  @endif >Pix</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <!--
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" >Qtde. dias do crédito: <span class="red normal"></span></label>
                                    <select class="form-control"  name="dias_credito">
                                        <option value="">Selecione</option>
                                        @for ($i = 1; $i <= 60; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option> 
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Ativo <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="ativo">
                                        <option value="S" @if(old('ativo')=="S") selected  @endif>Sim</option>
                                        <option value="N" @if(old('ativo')=="N") selected  @endif>Não</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <!--
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-3 col-md-offset-3"> 
                                    <div class="form-group">
                                        <label for="fname">Parcela: <span class="red normal">*</span></label>
                                        <select class="form-control"   name="parcela">
                                            <option value="">Selecione</option>
                                            @for ($i = 1; $i <= 48; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option> 
                                            @endfor
                                        </select>
                                    </div> 
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname">Taxa: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required"   id="taxa"
                                        value="{{ old('taxa') }}" name="taxa" maxlength="100" aria-required="true">
                                </div>
                            </div> 
                            <div class="col-md-1">
                                <input type="button" style="margin-top: 23px;" class="btn btn-default" value="Incluir" x-on:click="addParcela"   />
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6 col-md-offset-3">
                            <div class="table-responsive">
                                <table class="display table dataTable table-striped">
                                    <thead>
                                        <tr>
                                            <th class="sorting_asc">Parcela</th>
                                            <th class="sorting">Taxa</th> 
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="parcela in parcelas">
                                            <tr>
                                                <th>d </th>
                                                <th> dsd</th>
                                                <th> sd</th>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                        -->
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Salvar" />
                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!-- Main Wrapper -->
@endsection

@section('script')
<script src="{{ asset('js/rpclinica/forma-pag-cadastro.js') }}"></script>
@endsection
