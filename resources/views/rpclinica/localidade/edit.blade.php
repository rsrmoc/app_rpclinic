@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Editar Localidade</h3>

        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('localidade.listar') }}">Relação de Localidade</a></li>
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
                        action="{{ route('localidade.update', ['localidade' => $localidade->cd_escala_localidade]) }}" >
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="fname">Nome da Localidade: <span
                                            class="red normal">*</span></label> 
                                    <input type="text" class="form-control required" value="{{ old('nome', $localidade->nm_localidade ) }}"
                                        name="nome" maxlength="100" aria-required="true" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname">Cep: <span
                                            class="red normal">*</span></label> 
                                    <input type="text" class="form-control required" value="{{ old('cep', $localidade->cep ) }}"
                                        name="cep" maxlength="100" aria-required="true" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fname">Cidade: <span
                                            class="red normal">*</span></label> 
                                    <input type="text" class="form-control required" value="{{ old('cidade', $localidade->ds_cidade ) }}"
                                        name="cidade" maxlength="100" aria-required="true" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>UF <span class="red normal">*</span></label>

                                <select class="form-control" name="uf" required>
                                    <option value="">SELECIONE</option>
                                    @foreach ($ufs as $uf)
                                        <option value="{{ $uf->cd_uf }}"  @if($localidade->cd_uf==$uf->cd_uf) selected @endif >{{ $uf->nm_uf . ' ( '.$uf->cd_uf.' )' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Ativo <span class="red normal">*</span></label>

                                    <select class="form-control" required="" name="ativo">
                                        <option value="S" @if($localidade->sn_ativo=='S') selected @endif >SIM</option>
                                        <option value="N" @if($localidade->sn_ativo=='N') selected @endif >NÃO</option>
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
