@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Setor</h3>
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
                    <form role="form" action="{{ route('setor.store') }}" method="post" role="form">
                        @csrf

                        <div class="row">
                       

                            <div class="col-md-5 col-md-offset-2">
                                <div class="form-group">
                                    <label for="fname">Nome: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control  " required id="NM_SETOR" value="{{ old('nome') }}"
                                        name="nome" maxlength="100" aria-required="true">
                                </div>
                            </div>

                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fname">
                                        Grupo: <span class="red normal">*</span>
                                    </label>

                                    <select name="grupo"
                                        id="empresas"
                                        class="js-states form-control select2-hidden-accessible"
                                        tabindex="-1"
                                        style="display: none; width: 100%"
                                        aria-hidden="true"
                                        required>
                                        <option value="">Selecione</option>
                                        <option value="A">Administrativo</option>
                                        <option value="P">Apoio</option>
                                        <option value="R">Produtivo</option>
                                        <option value="N">Não Operacional</option>
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
