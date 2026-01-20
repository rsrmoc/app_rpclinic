@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Configuração Geral</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Financeiro</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @error('record')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form role="form" id="addUser" action="{{ route('config.financeiro.store') }}" method="post"  role="form">
                        @csrf
                        <div class="row"> 
                            <div class="col-md-5 col-md-offset-1"> 
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Categoria Transferência <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="cd_cateogria_cartao">
                                        <option value="">Selecione</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->cd_categoria }}" 
                                            @if ( old('cd_cateogria_cartao',(isset($Config->cd_cateogria_cartao)) ? $Config->cd_cateogria_cartao : null) ==$categoria->cd_categoria ) selected @endif    >{{ $categoria->nm_categoria }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5"> 
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Categoria Cartão <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="cd_categoria_transf">
                                        <option value="">Selecione</option>
                                        @foreach ($categorias as $categoria)
                                            <option value="{{ $categoria->cd_categoria }}"
                                            @if ( old('cd_categoria_transf',(isset($Config->cd_categoria_transf)) ? $Config->cd_categoria_transf : null) ==$categoria->cd_categoria ) selected @endif  >{{ $categoria->nm_categoria }}
                                            </option>
                                        @endforeach
                                    </select>
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
