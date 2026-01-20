@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Editar Especialidade</h3>

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
                    <form role="form"
                        id="addUser"
                        method="post"
                        role="form"
                        action="{{ route('especialidade.update', ['especialidade' => $especialidade->cd_especialidade]) }}" >
                        @csrf

                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="fname">Nome: <span class="red normal">*</span></label>

                                    <input type="text" class="form-control required" value="{{ $especialidade->nm_especialidade }}" name="nome"
                                        maxlength="100" aria-required="true" required>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <label>CBO <span class="red normal">*</span></label>

                                <select class="form-control" name="cbo" required>
                                    <option value="">SELECIONE</option>
                                    @foreach ($cbos as $cbo)
                                        <option value="{{ $cbo->cd_cbos }}"
                                            @if ($especialidade->cd_cbos == $cbo->cd_cbos) selected  @endif>
                                            {{ $cbo->nm_cbos }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Ativo <span class="red normal">*</span></label>

                                    <select class="form-control" required="" name="ativo">
                                        <option value="S" @if ($especialidade->sn_ativo == 'S') selected  @endif>SIM</option>
                                        <option value="N" @if ($especialidade->sn_ativo == 'N') selected  @endif>NÃO</option>
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
