@extends('rpclinica.layout.layout')

@section('content')

<style>
    /* Dark Theme Overrides for Edit Page */
    .panel, .panel-white, .panel-default, .panel-body, .tab-content {
        background-color: #1e293b !important;
        color: #fff !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    /* Fix Header Overlap */
    .page-title {
        padding-top: 50px !important; 
        margin-bottom: 20px;
    }

    /* Inputs & Selects */
    .form-control {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
    }
    .form-control:focus {
        background-color: rgba(255, 255, 255, 0.1) !important;
        border-color: #22BAA0 !important;
    }
    
    /* Standard Select Options */
    select.form-control option {
        background-color: #1e293b !important;
        color: #fff !important;
    }

    /* Tabs */
    .nav-tabs > li > a {
        color: #ccc !important;
        background-color: transparent !important;
        transition: all 0.3s;
    }
    /* Tab Hover - GREEN */
    .nav-tabs > li > a:hover {
        background-color: #22BAA0 !important; /* Green */
        color: #fff !important;
        border: 1px solid #22BAA0 !important;
    }

    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        background-color: #1e293b !important;
        color: #fff !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-bottom-color: transparent !important;
    }

    /* Select2 Dropdowns */
    .select2-container--default .select2-selection--single {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #fff !important;
    }
    .select2-dropdown {
        background-color: #1e293b !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    .select2-results__option {
        color: #fff !important;
    }
    .select2-results__option[aria-selected="true"] {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }
</style>

<div class="page-title">
    <h3>
        Edição de Pacientes
        <a href="{{ route('paciente.listar') }}" class="btn btn-danger btn-sm pull-right" style="margin-left: 10px;">
            <i class="fa fa-times"></i> Fechar
        </a>
    </h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{ route('paciente.listar') }}">Relação de Pacientes</a></li>
        </ol>
    </div>
</div>

<div id="main-wrapper">
    <div class="col-md-12 ">

        <div role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#tabCadastro" role="tab" data-toggle="tab"
                        id="buttonTabAgendamentos"> Cadastro</a></li>
                <li role="presentation"><a href="#tabProntuario" role="tab" data-toggle="tab"> Prontuário</a>
                </li>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane " id="tabProntuario">

                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">

                                @foreach ($prontuario as $doc)


                                    <div class="panel panel-white">
                                        <div class="panel-heading" style="height: 80px;">
                                            <h3 class="panel-title" >
                                            <span style="  font-size: 1.2em; padding-top: 15px;"><i class="fa fa-file-text-o"></i> {{  ucfirst(strtolower($doc->nm_formulario)) }} </span>
                                            </h3>
                                            <br style="padding: 5px;">    {{  ucfirst(strtolower($doc->nm_profissional)) }}
                                            <span style="font-weight: 400; font-size: 0.8em; margin-left: 10px;">
                                                {{ $doc->data }}  - há {{ $doc->diferenca }} dia(s) </span>
                                            <div class="panel-control">
                                                @if($doc->nm_formulario=='Anamnese')
                                                    <a  href="/rpclinica/json/imprimirAnamneseGeral/{{ $doc->cd_agendamento }}?tipo=anamnese&header=N&logo=N&footer=N&data=N&assinatura=N" 
                                                        data-toggle="tooltip" data-placement="top" title="Imprimir" target="_blank" class="panel-reload"><i class="icon-printer"></i></a>
                                                @else 
                                                    <a href="/rpclinica/json/imprimirDocumentoGeral/{{ $doc->cd_agendamento }}/{{ $doc->codigo }}?tipo=documento&header=N&logo=N&footer=N&data=N&assinatura=N&rec_especial=N&sn_ocultar_titulo=N" 
                                                       data-toggle="tooltip" data-placement="top" title="Imprimir" target="_blank" class="panel-reload"><i class="icon-printer"></i></a>
                                                @endif 
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <p>
                                                {!! nl2br($doc->conteudo) !!}
                                            </p>
                                        </div>
                                    </div>
                                    <br>
                                @endforeach

                                @if(empty($prontuario))
                                <div style="text-align: center;">
                                    <img src="{{ asset('assets/images/prontuario.png') }} ">
                                </div>

                                @endif

                            </div>
                        </div>

                    </div>

                    <div role="tabpanel" class="tab-pane active fade in" id="tabCadastro">
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

                            <form role="form" action="{{ route('paciente.update', ['paciente' => $paciente->cd_paciente]) }}"
                                method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input name="foto" type="file" style="opacity:0; height: 0;" id="foto">
                                <input name="fotoCopy" type="text" style="opacity:0; height: 0;" id="fotoCopy">

                                <div class="row">
                                    <div class="col-md-2 col-xs-12">
                                        <div class="row">
                                            <div class="col-md-12 text-center">
                                                <div style="position: relative">
                                                    <img id="fotodopaciente" src="{{ $paciente->foto_url ? $paciente->foto_url : asset('assets/images/user.png') }}"
                                                        class="img-perfil img-circle img-responsive avatar-rpclinica"
                                                        style="display:block; margin: 0 auto 12px">

                                                    <div class="btn-selecionar-foto">
                                                        <button id="carregarfotodopaciente" type="button" style="width: 100%;"
                                                            class="btn btn-xs btn-success">
                                                            <span class="fa fa-camera"></span> Alterar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><strong>Nome: </strong><span class="red normal">*</span></label>
                                            <input type="text" class="form-control required" required value="{{ old('nome',  $paciente->nm_paciente)  }}"
                                                name="nome" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Data de nascimento: </strong><span class="red normal">*</span></label>
                                            <input type="date" class="form-control" value="{{ old('data_de_nascimento',trim($paciente->dt_nasc))  }}"
                                                name="data_de_nascimento" required maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>RG: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('rg',  $paciente->rg)  }} " name="rg"
                                                maxlength="100" aria-required="true"  >
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>CPF: </strong><span class="red normal"></span></label>
                                            <input type="text"class="form-control" value="{{ old('cpf',  $paciente->cpf)  }}" name="cpf"
                                                maxlength="100" aria-required="true"  >
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><strong>Nome Social: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control  " value="{{ old('nome_social',trim($paciente->nome_social)) }}"
                                                name="nome_social" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Sexo: </strong><span class="red normal"></span></label>
                                            <select name="sexo" class="form-control">
                                                <option value="">SELECIONE</option>
                                                <option value="H" @if( old('sexo',  $paciente->sexo) == 'H') selected @endif>Masculino</option>
                                                <option value="M" @if( old('sexo',  $paciente->sexo)  == 'M') selected @endif>Feminino</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Estado civil: </strong><span class="red normal"></span></label>
                                            <select class="form-control" name="estado_civil" >
                                                <option value="">SELECIONE</option>
                                                <option value="S" @if( old('estado_civil',  $paciente->estado_civil) == 'S') selected @endif>Solteiro</option>
                                                <option value="C" @if( old('estado_civil',  $paciente->estado_civil) == 'C') selected @endif>Casado</option>
                                                <option value="D" @if( old('estado_civil',  $paciente->estado_civil) == 'D') selected @endif>Divorciado</option>
                                                <option value="V" @if( old('estado_civil',  $paciente->estado_civil) == 'V') selected @endif>Viúvo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Cartão SUS: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('cartao_sus',$paciente->cartao_sus) }}" name="cartao_sus"  maxlength="100" aria-required="true" >
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label><strong>Nome da mâe: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('nome_da_mae',  $paciente->nm_mae)  }}" name="nome_da_mae"
                                                maxlength="100" aria-required="true"  >
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Data  de Nascimento Mãe: </strong><span class="red normal"></span></label>
                                            <input type="date" class="form-control" value="{{ old('dt_nasc_mae',$paciente->dt_nasc_mae) }}" name="dt_nasc_mae"  maxlength="100" aria-required="true" >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><strong>Celular Mãe: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('celular_mae',$paciente->celular_mae) }}" name="celular_mae"  maxlength="100" aria-required="true" >
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><strong>Nome do pai: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('nome_do_pai',  $paciente->nm_pai) }}"
                                                name="nome_do_pai" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Data  de Nascimento Pai: </strong><span class="red normal"></span></label>
                                            <input type="date" class="form-control" value="{{ old('dt_nasc_pai',$paciente->dt_nasc_pai) }}" name="dt_nasc_pai"  maxlength="100" aria-required="true" >
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><strong>Celular Pai: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('celular_pai',$paciente->celular_pai) }}" name="celular_pai"  maxlength="100" aria-required="true" >
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><strong>Profissão do Paciente: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('profissao',  $paciente->profissao) }}"
                                                name="profissao" maxlength="100" aria-required="true"> 
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><strong>Nome do Responsável: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('nm_responsavel',$paciente->nm_responsavel) }}"
                                                name="nm_responsavel" maxlength="100" aria-required="true">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>CPF do Responsável: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{ old('cpf_responsavel',$paciente->cpf_responsavel) }}"
                                                name="cpf_responsavel" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="mat-label"><strong>Convênio</strong> <span
                                                    class="red normal"></span></label>
                                            <select class="form-control"   name="convenio">
                                                <option value="">SELECIONE</option>
                                                @foreach($convenios as $convenio)
                                                    <option value="{{ $convenio->cd_convenio }}"
                                                        @if(  old('convenio',  $paciente->cd_categoria) == $convenio->cd_convenio) selected @endif>
                                                        {{ $convenio->nm_convenio }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Cartão: </strong><span class="red normal"></span></label>
                                            <input type="text" class="form-control" value="{{  old('cartao',  $paciente->cartao) }}"
                                                name="cartao" maxlength="100" aria-required="true">
                                        </div>
                                    </div>
                                </div>

                                <hr />

                                <div class="row" x-data="{

                                    cep:{
                                        cep : null,
                                        rua : null,
                                        bairro : null,
                                        cidade : null,
                                        uf : null,
                                    },

                                    buscarCep(){

                                        axios.get('https://viacep.com.br/ws/'+this.cep.cep.replace(/[^0-9]/g,'')+'/json')

                                        .then((res) => {
                                            this.cep.rua = res.data.logradouro;
                                            this.cep.bairro = res.data.bairro;
                                            this.cep.cidade = res.data.localidade;
                                            $('#pac-uf').val(res.data.uf).trigger('change');
                                        })

                                    }
                                }">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Cep: </strong> </label>
                                            <div class="input-group m-b-sm">
                                                <input type="text" x-mask="99999-999" class="form-control" x-model="cep.cep"  value="{{ old('cep',$paciente->cep) }}"
                                                name="cep" maxlength="100"  aria-required="true">
                                                <span class="input-group-addon" style="cursor: pointer;" x-on:click="buscarCep">
                                                   <i class="fa fa-thumb-tack" style="margin-right: 0px;"></i>
                                               </span>
                                           </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><strong>Rua: </strong> </label>
                                            <input type="text" class="form-control" value="{{ old('logradouro',  $paciente->logradouro) }}" x-model="cep.rua"
                                                name="logradouro" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Número: </strong><span class="red normal"></span></label>
                                            <input type="number" class="form-control" value="{{  old('numero',  $paciente->numero) }}"
                                                name="numero" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label><strong>Complemento: </strong> </label>
                                            <input type="text" class="form-control" value="{{old('complemento',  $paciente->complemento) }}"
                                                name="complemento" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><strong>Bairro: </label>
                                            <input type="text" class="form-control" value="{{  old('bairro',  $paciente->nm_bairro)  }}" x-model="cep.bairro"
                                                name="bairro" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><strong>Cidade: </strong><span class="red normal">*</span></label>
                                            <input type="text" class="form-control" value="{{ old('cidade',  $paciente->cidade) }}" x-model="cep.cidade"
                                                name="cidade" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><strong>Estado:  </label>
                                            <select name="uf" id="pac-uf"   class="form-control">
                                                <option value="">SELECIONE</option>
                                                @foreach (ESTADOS as $estado)
                                                    <option value="{{ $estado["sigla"] }}"
                                                        @if( old('uf',  $paciente->uf) == $estado["sigla"]) selected @endif>
                                                        {{ $estado["nome"] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><strong>Telefone: </strong><!--<span class="red normal">*</span>--></label>
                                            <input type="text" class="form-control" value="{{ old('telefone',  $paciente->fone) }}"
                                                name="telefone" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label><strong>Celular: </strong><!--<span class="red normal">*</span>--></label>
                                            <input type="text" class="form-control" value="{{ old('celular',  $paciente->celular) }}"
                                                name="celular" maxlength="100" aria-required="true">
                                        </div>
                                    </div>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <div class="checker">
                                          <span>
                                            <input type="checkbox" name="vip"  @if(old('vip',  $paciente->vip)=='S') checked @endif  value="S">
                                          </span>
                                        </div> Paciente Vip
                                    </label>
                                </div>
                                <br>
                                <hr>
                                <div class="box-footer">
                                    <input type="submit" class="btn btn-success" value="Salvar" />
                                    <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </ul>
        </div>
        <br><br>
    </div>

</div>
<!-- Main Wrapper -->
<!-- Modal -->
<div class="modal fade" id="modalCarregarFoto" tabindex="-1" role="dialog" aria-labelledby="modalCarregarFoto"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid">
                    <div id="ocultaropcoes" class="row" style="margin: auto;width: 50%;padding: 10px;">
                        <div class="col-md-6">
                            <label for="foto">
                                <i style="font-size: 64pt;" class="fa fa-picture-o" aria-hidden="true"></i>
                            </label>
                            <p>Procurar no computador</p>
                        </div>
                        <div class="col-md-6">
                            <label>
                                <i id="webcam" style="font-size: 64pt;" class="fa fa-video-camera"
                                    aria-hidden="true"></i>
                            </label>
                            <p>Carregar na webcam</p>
                        </div>
                    </div>
                    <div class="row">
                        <div id="areadaWebcam" style="display: none;">
                            <div class="col-md-12" id="tirarfotopelawebcam" style="text-align: center">
                                <video autoplay="true" id="ligarWebcam" width="400" height="400">
                                </video>
                                <br>
                                <button class="btn btn-success" type="button" id="tirarfoto">Tirar foto</button>
                                <canvas id="canvaswebcam" width="400" height="400" style="display: none"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="areadaFoto" style="display: none;">
                            <div class="col-md-12" id="fotodopaciente" style="text-align: center">
                                <img id="fotopaciente" src="" width="400" height="400" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                <button id="salvarfoto" type="button" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </div>
</div>
<script>
    const webCam = document.querySelector("#ligarWebcam");
    const canvasWebCam = document.querySelector("#canvaswebcam");
    var foto = null;
    var fotoCopy = null;

    $("#foto").on('change', function() {
        foto = $("#foto")[0].files[0];

        $("#ocultaropcoes").css("display", "none");
        $('#areadaFoto').css("display", "block");

        var ler = new FileReader();

        ler.onload = function(e) {
            $('#fotoCopy').val(e.target.result);
            $('#fotopaciente').attr('src', e.target.result);
        }

        ler.readAsDataURL($("#foto")[0].files[0]);
    });

    $("#webcam").click(function() {
        $("#ocultaropcoes").css("display", "none");
        $("#areadaWebcam").css("display", "block");

        webCam.setAttribute('autoplay', '');
        webCam.setAttribute('muted', '');
        webCam.setAttribute('playsinline', '');

        if (navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                    audio: false,
                    video: {
                        facingMode: 'user'
                    }
                })
                .then(function(stream) {
                    webCam.srcObject = stream;
                })
                .catch(function(error) {
                    $('#modalCarregarFoto').modal('toggle');
                    Swal.fire('Não conseguimos encontrar uma webcam no seu computador!');
                });
        }
    });

    $("#carregarfotodopaciente").click(function() {
        foto = null;
        fotoCopy = null;

        $('#modalCarregarFoto').modal('toggle');

        $("#ocultaropcoes").css("display", "block");
        $("#areadaWebcam").css("display", "none");
        $("#areadaFoto").css("display", "none");

        $('#fotopaciente').attr('src', '');

        $('#fotoCopy').attr('value', '');

        webCam.pause();
        webCam.srcObject = null;
    });

    $("#salvarfoto").click(function() {

        if (fotoCopy) {
            $('#fotoCopy').attr('value', fotoCopy);
        }
        else {
            var ler = new FileReader();

            ler.onload = function(e) {
                $('#fotodopaciente').attr('src', e.target.result);
            }

            ler.readAsDataURL($("#foto")[0].files[0]);
        }

        $('#modalCarregarFoto').modal('toggle');

    });

    $('#tirarfoto').click(() => {
        canvasWebCam.getContext('2d').drawImage(webCam, 0, 0, 400, 400);
        let image_url = canvasWebCam.toDataURL();

        fotoCopy = image_url;
        $('#fotodopaciente').attr('src', image_url);
        $('#fotopaciente').attr('src', image_url);

        $('#areadaWebcam').css('display', 'none');
        $('#areadaFoto').css("display", "block");

        webCam.pause();
        webCam.srcObject = null;
    })

    $(document).ready(function() {
        $('select').select2();
    });
</script>

@endsection

