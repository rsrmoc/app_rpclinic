@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Editar de Usuario</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('usuario.listar') }}">Relação de Usuarios</a></li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper" x-data="app">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h5>Houve alguns erros</h5>

                            <ul>
                                @foreach($errors->all() as $erro)
                                    <li>{{ $erro }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form   method="post" action="{{ route('usuario.update', ['usuario' => $usuario->cd_usuario]) }}" id="formUsuario">
                        <input type="submit" style="display: none" />
                        @csrf
                        <div class="row">
                            <div class="col-md-{{ ($codEmpresa->sn_login_email=='sim') ? '4' : '2' }}">
                                <div class="form-group">
                                    <label>{{ ($codEmpresa->sn_login_email=='sim') ? 'Email (Usuário de Acesso)' : 'Usuário de Acesso' }} :  <span class="red normal">*</span></label>
                                    <input type="email" class="form-control required" name="email" maxlength="100"
                                        aria-required="true" required readonly value="{{ old('email',$usuario->email) }}">
                                </div>
                            </div>

                            <div class="col-md-{{ ($codEmpresa->sn_login_email=='sim') ? '5' : '4' }}">
                                <div class="form-group">
                                    <label>Nome: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required"  name="nome"
                                        maxlength="100" aria-required="true" required value="{{ old('nome',$usuario->nm_usuario) }}">
                                </div>
                            </div>

                            @if($codEmpresa->sn_login_email=='nao')
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Email Contato : <span class="red normal"></span></label>
                                        <input type="email" class="form-control required"   name="email_contato"
                                            maxlength="100" aria-required="true" value="{{ old('email_contato',$usuario->email_contato) }}"  >
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Informar Senha: <span class="red normal"></span></label>
                                    <input type="password" placeholder="Senha" name="senha"
                                    class="form-control" x-model="inputsUsuario.senha" aria-label="..." value="">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Perfil: <span class="red normal">*</span></label>
                                    <select name="perfil" class="form-control" required id="perfil-usuario">
                                        <option value="">SELECIONE</option>
                                        @foreach ($perfis as $perfil)
                                            <option  value="{{ $perfil->cd_perfil }}" @if($usuario->cd_perfil==$perfil->cd_perfil) selected @endif >{{ $perfil->nm_perfil }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Celular: <span class="red normal">*</span></label>

                                    <input x-mask="(99) 99999-9999" type="tel" placeholder="Celular"
                                        class="form-control" name="celular" required value="{{ old('celular',$usuario->nm_celular) }}"/>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Profissional: <span class="red normal"></span></label>
                                    <select name="profissional" class="form-control"   id="prof-usuario">
                                        <option value="">SELECIONE</option> 
                                        @foreach ($profissionais as $prof)
                                            <option value="{{ $prof->cd_profissional }}" @if($usuario->cd_profissional==$prof->cd_profissional) selected @endif>{{ $prof->nm_profissional }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Empresa: <span class="red normal">*</span></label>
                                    <select name="empresa" class="form-control" required id="empresa-usuario">
                                        <option value="S">SELECIONE</option>
                                        @foreach ($empresas as $empresa)
                                            <option @if($usuario->cd_empresa==$empresa->cd_empresa) selected @endif value="{{ $empresa->cd_empresa }}">{{ $empresa->nm_empresa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>

 

                        <div class="line">
                            <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                <label style="padding: 0">
                                    <div class="checker">
                                        <span>
                                            <input type="checkbox" name="resetar_senha"
                                                x-model="inputsUsuario.resetar_senha" />
                                        </span>
                                    </div> Resetar senha
                                </label>
                            </div>
 
                            <div class="checkbox   m-r-md" style="margin-top: 0; margin-bottom: 0">
                                <label style="padding: 0">
                                    <div class="checker" id="box_todos_agendamentos">
                                        <span>
                                            <input type="checkbox" name="admin" @if($usuario->admin=='S') checked  @endif value="S"  />
                                        </span>
                                    </div> Usuário Administrador
                                </label>
                            </div>

                            <div class="checkbox   m-r-md" style="margin-top: 0; margin-bottom: 0">
                                <label style="padding: 0">
                                    <div class="checker" id="box_todos_agendamentos">
                                        <span>
                                            <input type="checkbox" name="ativo-usuario" id="ativo_usuario" @if($usuario->sn_ativo=='S') checked  @endif value="S"  />
                                        </span>
                                    </div> Usuário Ativo
                                </label>
                            </div>


                            <div class="checkbox" style="margin-top: 0; margin-bottom: 0">
                                <label style="padding: 0">
                                    <div class="checker" id="box_todos_agendamentos">
                                        <span>
                                            <input type="checkbox" name="todos_agendamentos" value="S"  @if($usuario->sn_todos_agendamentos=='S') checked  @endif />
                                        </span>
                                    </div> Visualizar todos os agendamentos
                                </label>
                            </div> 
                        </div>

                        <br>
                        <div class="row box-footer" style="border: 1px solid #eee;   padding: 15px; margin: 10px;">
                            
                            <label ><b>Central de Laudos:</b>  </label>
                            <div class="line">
                                <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                    <label style="padding: 0">
                                        <div class="checker">
                                            <span>
                                                <input type="checkbox" name="visualizar_exame"
                                                @if($usuario->visualizar_exame=='S') checked  @endif value="S"
                                                  />
                                            </span>
                                        </div> Visualizar Todos Exames
                                    </label>
                                </div>

                                <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                    <label style="padding: 0">
                                        <div class="checker">
                                            <span>
                                                <input type="checkbox" name="laudar_exame" value="S"
                                                @if($usuario->laudar_exame=='S') checked  @endif
                                                   />
                                            </span>
                                        </div> Laudar Todos Exames
                                    </label>
                                </div>
                            </div>
                            
                             

                        </div>
                    </form>
 
                </div>

                <div class="panel-footer line">
                    <button class="btn btn-success" x-on:click="submitSave"
                        x-bind:disabled="loadingUsuario">Salvar</button>
                    <input type="reset" class="btn btn-default" value="Limpar" />

                    <template x-if="loadingUsuario">
                        <x-loading message="Salvando usuário..." />
                    </template>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')

    <script>
        const procedimentos = @js($procedimentos);
        const convenios = @js($convenios);
        const especialidades = @js($especialidades);
        const usuario = @js($usuario);
    </script>
    <script src="{{ asset('js/rpclinica/usuarios.js') }}"></script>
@endsection
