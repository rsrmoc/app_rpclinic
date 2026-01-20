@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Edição de Tipo de Ajuste </h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('tipoaj.ajuste.listar') }}">Relação de Tipo de Ajuste de Estoque</a></li>
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

                    <form role="form" action="{{ route('tipoaj.ajuste.update', ['tipo' => $tipo->cd_tipo_ajuste]) }}" method="post" role="form">
                        @csrf
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Descrição: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required"
                                        value="{{ $tipo->nm_tipo_ajuste }}"
                                        name="descricao"
                                        maxlength="100" aria-required="true" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">

                                <div class="form-group">
                                    <label for="fname" class="mat-label">Tipo <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="tipo">
                                        <option value="">Selecione</option>
                                        <option value="+" @if($tipo->tp_ajuste == '+') selected @endif>+ Soma</option>
                                        <option value="-" @if($tipo->tp_ajuste == '-') selected @endif>- Subtrai</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Ativo <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="ativo">
                                        <option value="">Selecione</option>
                                        <option value="S" @if($tipo->sn_ativo == 'S') selected @endif>Sim</option>
                                        <option value="N" @if($tipo->sn_ativo == 'N') selected @endif>Não</option>
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

    </div><!-- Main Wrapper -->
@endsection
