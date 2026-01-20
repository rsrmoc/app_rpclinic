@extends('rpclinica/layout.layout')

@section('content')
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <form method="post" action="{{ route('rpclinica.usuario.alterar-acao') }}" class="panel panel-white">
                @csrf   
                <div class="panel-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5>Houve alguns erros:</h5>  
                            <ul>
                                {!! implode('', $errors->all('<li>:message</li>')) !!}
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputPassword1">Senha Antiga</label>
                                <input type="password" class="form-control" id="SENHA_ANTIGA" placeholder="Senha Antiga"
                                    name="atual" minlength="3" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputPassword1">Nova Senha</label>
                                <input type="password" class="form-control required" id="SENHA_NOVA" placeholder="Senha"
                                    name="nova_senha" minlength="3" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom: 0">
                                <label for="inputPassword2">Confirme a nova senha</label>
                                <input type="password" class="form-control required equalTo" id="SENHA_CONFIRME"
                                    placeholder="Confirme" name="nova_senha_confirmation" minlength="3" maxlength="20" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-footer">
                    <input type="submit" class="btn btn-success" value="Salvar" />
                    <input type="reset" class="btn btn-default" value="Limpar" />
                </div>
            </form>
        </div>
    </div>
@endsection
