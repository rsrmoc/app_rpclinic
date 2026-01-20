@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Texto Padrão</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('exame.listar') }}">{{ $exame->nm_exame }}</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper" x-data="appFormulario">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <form role="form" action="{{ route('exame.formulario.store', ['exame' => $exame]) }}" method="post">
                    @csrf
                    <div class="panel-body">
                        <input type="hidden" name="codigo" value="{{ $formulario['codigo'] }}" >
                        <div class="row">
                            <div class="col-md-8 ">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="fname">Nome: <span class="red normal">*</span></label>
                                            <input type="text" class="form-control " value="{{ $formulario['nome'] }}"
                                                name="descricao" maxlength="100">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea id="conteudo" style="height: 500px;" class="form-control" name="conteudo_laudo">{{ $formulario['conteudo'] }}</textarea>
                                        </div>
                                    </div> 
                                </div>

                                <div class="panel-footer">
                                    <input type="submit" class="btn btn-success" value="Salvar" />
                                </div>

                                <div class="panel-body" style="margin-top: 20px;">
                                    <div class="table-responsive">
                                        <table class="display table dataTable table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#ID</th>
                                                    <th>Nome</th>
                                                    <th>Data</th>
                                                    <th>Usuario</th>  
                                                    <th class="text-center">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($exame->formularios as $ex)
                                                <tr id="est-{{ $ex->cd_exame_formulario }}">
                                                    <td>{{ $ex->cd_exame_formulario }}</td> 
                                                    <td>{{ $ex->nm_formulario }}</td>
                                                    <td>{{ $ex->data }}</td>
                                                    <td>{{ $ex->usuario?->nm_usuario }}</td>
                                                    <td class="text-center">
                                                        <div class="btn-group"> 

                                                            <a href="{{ route('exame.formulario', ['exame' => $exame->cd_exame,'codigo'=>$ex->cd_exame_formulario]) }}" class="btn btn-success">
                                                                <i class="fa fa-edit"></i>
                                                            </a>

                                                            <button onclick="delete_cadastro('{{ route('exame.formulario.delete', ['formulario' => $ex->cd_exame_formulario]) }}', '#est-{{ $ex->cd_exame_formulario }}')"
                                                                class="btn btn-danger" type="button">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="box-footer clearfix">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4 ">
                                
                                <table class="table  ">
                                    <thead>
                                        <tr>
                                            <th width="100%" colspan="2"   class="text-center ">PACIENTE
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Codigo</th>
                                            <td>@CodPaciente <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@CodPaciente')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Nome</th>
                                            <td>@PacienteNome <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteNome')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Idade</th>
                                            <td>@PacienteIdade <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteIdade')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Data de Nascimento</th>
                                            <td>@PacienteNascimento <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteNascimento')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Nome da Mãe</th>
                                            <td>@PacienteMae <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteMae')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Nome do Pai</th>
                                            <td>@PacientePai <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacientePai')">
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row">CPF</th>
                                            <td>@PacienteCpf <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteCpf')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">RG</th>
                                            <td>@PacienteRG <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteRG')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Estado Civil</th>
                                            <td>@PacienteCivil <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteCivil')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Telefone</th>
                                            <td>@PacienteTelefone <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteTelefone')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Celular</th>
                                            <td>@PacienteCelular <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteCelular')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">E-mail</th>
                                            <td>@PacienteEmail <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteEmail')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Endereço</th>
                                            <td>@PacienteEnd <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteEnd')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Endereço Numero</th>
                                            <td>@PacienteEndNum <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteEndNum')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Bairro</th>
                                            <td>@PacienteBairro <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteBairro')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Cidade</th>
                                            <td>@PacienteCidade <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteCidade')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">UF</th>
                                            <td>@PacienteUF <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteUF')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">CEP</th>
                                            <td>@PacienteCep <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@PacienteCep')">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th width="100%" colspan="2" class="text-center">
                                                PROFISSIONAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Nome</th>
                                            <td>@Profissional_nome <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@Profissional_nome')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Conselho</th>
                                            <td>@Profissional_conselho <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@Profissional_conselho')">
                                            </td>
                                        </tr>
                                    </tbody>
                                    <thead>
                                        <tr>
                                            <th width="100%" colspan="2" class="text-center">
                                                ATENDIMENTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Nr.Atendimento</th>
                                            <td>@Atendimento <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@Atendimento')">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Data</th>
                                            <td>@AtendimentoData <img height="18" style="margin-left: 15px; cursor: pointer;" src="{{ asset('assets/images/copiar-texto.png') }}" x-on:click="copyText('@AtendimentoData')">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        



                </form>



            </div>
        </div>

    </div>


    <script>
        CKEDITOR.replace('conteudo', {
            toolbar: [{
                    name: 'basicstyles',
                    items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat']
                },
                {
                    name: 'paragraph',
                    items: ['NumberedList', 'BulletedList', '-', 'Blockquote']
                },
                {
                    name: 'styles',
                    items: ['Format']
                }
            ]

        });
    </script>



    </div><!-- Main Wrapper -->
@endsection

@section('scripts')
  
    <script src="{{ asset('js/rpclinica/exame.js') }}"></script>
@endsection
