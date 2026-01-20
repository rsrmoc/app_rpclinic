@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Edição de Formas de Pagamento</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Editar</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @error('error')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form role="form" action="{{ route('forma.pag.update', ['forma' => $forma->cd_forma_pag]) }}" method="post" role="form">
                        @csrf

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="fname">Descrição: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required" required id="formadepagamento"
                                        value="{{ $forma->nm_forma_pag }}"
                                        name="descricao"
                                        maxlength="100"
                                        aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Tipo <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="tipo">
                                        <option value="">Selecione</option>
                                        <option value="BO" @if(old('tipo',$forma->tipo)=="BO") selected  @endif >Boleto</option>
                                        <option value="CA" @if(old('tipo',$forma->tipo)=="CA") selected  @endif >Cartão</option>
                                        <option value="CH" @if(old('tipo',$forma->tipo)=="CH") selected  @endif >Cheque</option>
                                        <option value="DI" @if(old('tipo',$forma->tipo)=="DI") selected  @endif >Dinheiro</option>
                                        <option value="PI" @if(old('tipo',$forma->tipo)=="PI") selected  @endif >Pix</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Ativo <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="ativo">
                                        <option value="S" @if($forma->sn_ativo == 'S') selected @endif>Sim</option>
                                        <option value="N" @if($forma->sn_ativo == 'N') selected @endif>Não</option>
                                    </select>
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
    </div>
@endsection
