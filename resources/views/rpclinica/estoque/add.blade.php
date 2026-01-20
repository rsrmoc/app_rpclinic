@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Estoque</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('tab-estoque.listar') }}">Relação de Laudos</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    <form role="form" id="addUser" action="{{ route('tab-estoque.store') }}" method="post" role="form">
                        @csrf
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="fname">Descrição: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required" value="{{ old('descricao') }}" name="descricao"
                                        maxlength="100" aria-required="true" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Ativo <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="ativo">
                                        <option value="S">SIM</option>
                                        <option value="N">NÃO</option>
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

@section('script')
@endsection
