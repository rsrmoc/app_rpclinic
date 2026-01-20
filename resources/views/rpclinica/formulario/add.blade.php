@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Formularios</h3>
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

                    <form role="form" id="addUser" action="{{ route('formulario.store') }}" method="post"
                        role="form">
                        @csrf

                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="fname">Nome: <span class="red normal">*</span></label>

                                    <input type="text" class="form-control required" value="{{ old('nome') }}" name="nome"
                                        maxlength="100" aria-required="true" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <textarea class="summernote" name="conteudo">
                                        {{ old('conteudo') }}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Salvar" />
                            <input type="reset" class="btn btn-default" value="Limpar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
