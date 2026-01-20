@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Especialidade</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('especialidade.listar') }}">Relação de especialidade</a></li>
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
                    <form role="form" id="addUser" method="post" role="form"
                        action="{{ route('especialidade.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="fname">Descrição da Especialidade: <span
                                            class="red normal">*</span></label>

                                    <input type="text" class="form-control required" value="{{ old('nome') }}"
                                        name="nome" maxlength="100" aria-required="true" required>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label>CBO <span class="red normal">*</span></label>

                                <select class="form-control" name="cbo" required>
                                    <option value="">SELECIONE</option>
                                    @foreach ($cbos as $cbo)
                                        <option value="{{ $cbo->cd_cbos }}">{{ $cbo->nm_cbos }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Ativo <span class="red normal">*</span></label>

                                    <select class="form-control" required="" name="ativo">
                                        <option value="S">SIM</option>
                                        <option value="N">NÃO</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr />

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
