@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Local de Atendimento</h3>
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
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form role="form" action="{{ route('local.atend.store') }}" method="post" role="form">
                        @csrf

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Local: <span class="red normal">*</span></label>
                                    <input type="text"
                                        class="form-control required"
                                        required
                                        value="{{ old('nome') }}"
                                        name="nome"
                                        maxlength="100"
                                        aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Setor: <span class="red normal">*</span></label>
                                    <select name="setor"
                                        required
                                        class="form-control"
                                        tabindex="-1"
                                        style="width: 100%"
                                        aria-hidden="true">
                                        <option value="">SELECIONE O SETOR</option>
                                        @foreach ($setores as $setor)
                                            <option value="{{ $setor->cd_setor }}">{{ $setor->nm_setor }}</option>
                                        @endforeach
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
    <script>
        $(document).ready(function() {
            $('select').select2();
        });
    </script>
@endsection
