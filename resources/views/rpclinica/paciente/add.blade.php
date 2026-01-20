@extends('rpclinica.layout.layout')

@section('content')

<div class="page-title">
    <h3>Cadastro de Pacientes</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="{{ route('paciente.listar') }}">Relação de Pacientes</a></li>
        </ol>
    </div>
</div>

<div id="main-wrapper">
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

                <form role="form" action="{{ route('paciente.store') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <input name="foto" type="file" style="opacity:0; height: 0;" id="foto">
                    <input name="fotoCopy" type="text" style="opacity:0; height: 0;" id="fotoCopy">

                    <div class="row">
                        <div class="col-md-2 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <div style="position: relative">
                                        <img id="fotodopaciente" src="{{ asset('assets/images/user.png') }}"
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
                                <input type="text" class="form-control required" required value="{{ old('nome') }}"
                                    name="nome" maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Data de nascimento: </strong><span class="red normal">*</span></label>
                                <input type="date" class="form-control" value="{{ old('data_de_nascimento') }}"
                                    name="data_de_nascimento" required maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>RG: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('rg') }}" name="rg"
                                    maxlength="100" aria-required="true" >
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>CPF: </strong><span class="red normal"></span></label>
                                <input x-mask="999.999.999-99" class="form-control" value="{{ old('cpf') }}" name="cpf"
                                    maxlength="100" aria-required="true" type="text"  >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Nome Social: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control  " value="{{ old('nome_social') }}"
                                    name="nome_social" maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Sexo: </strong><span class="red normal"></span></label>
                                <select name="sexo" class="form-control">
                                    <option value="">SELECIONE</option>
                                    <option value="H" @if(old('data_de_nascimento')=='H') selected @endif>Masculino</option>
                                    <option value="M" @if(old('data_de_nascimento')=='M') selected @endif>Feminino</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Estado civil: </strong><span class="red normal"></span></label>
                                <select class="form-control" name="estado_civil"  >
                                    <option value="">SELECIONE</option>
                                    <option value="S" @if(old('estado_civil')=='S') selected @endif>Solteiro</option>
                                    <option value="C" @if(old('estado_civil')=='C') selected @endif>Casado</option>
                                    <option value="D" @if(old('estado_civil')=='D') selected @endif>Divorciado</option>
                                    <option value="V" @if(old('estado_civil')=='V') selected @endif>Viúvo</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Cartão SUS: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('cartao_sus') }}" name="cartao_sus"  maxlength="100" aria-required="true" >
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label><strong>Nome da mâe: </strong><span class="red normal"> </span></label>
                                <input type="text" class="form-control" value="{{ old('nome_da_mae') }}" name="nome_da_mae"
                                    maxlength="100" aria-required="true"   >
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Data  de Nascimento Mãe: </strong><span class="red normal"></span></label>
                                <input type="date" class="form-control" value="{{ old('dt_nasc_mae') }}" name="dt_nasc_mae"  maxlength="100" aria-required="true" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Celular Mãe: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('celular_mae') }}" name="celular_mae"  maxlength="100" aria-required="true" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Nome do pai: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('nome_do_pai') }}"
                                    name="nome_do_pai" maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Data  de Nascimento Pai: </strong><span class="red normal"></span></label>
                                <input type="date" class="form-control" value="{{ old('dt_nasc_pai') }}" name="dt_nasc_pai"  maxlength="100" aria-required="true" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Celular Pai: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('celular_pai') }}" name="celular_pai"  maxlength="100" aria-required="true" >
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Profissão do Paciente: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('profissao') }}"
                                    name="profissao" maxlength="100" aria-required="true"> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><strong>Nome do Responsável: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('nm_responsavel') }}"
                                    name="nm_responsavel" maxlength="100" aria-required="true">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>CPF do Responsável: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('cpf_responsavel') }}"
                                    name="cpf_responsavel" maxlength="100" aria-required="true">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="mat-label"><strong>Convênio</strong> <span
                                        class="red normal">*</span></label>
                                <select class="form-control"   name="convenio">
                                    <option value="">SELECIONE</option>
                                    @foreach($convenios as $convenio)
                                        <option value="{{ $convenio->cd_convenio }}" @if(old('convenio')==$convenio->cd_convenio) selected @endif >{{ $convenio->nm_convenio }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Cartão: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('cartao') }}"
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
                                <label><strong>Cep: </strong><span class="red normal"></span></label>

                                    <div class="input-group m-b-sm">
                                        <input type="text" x-mask="99999-999" class="form-control" x-model="cep.cep"  value="{{ old('cep') }}"
                                        name="cep" maxlength="100"  aria-required="true">
                                        <span class="input-group-addon" style="cursor: pointer;" x-on:click="buscarCep">
                                           <i class="fa fa-thumb-tack" style="margin-right: 0px;"></i>
                                       </span>
                                   </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Rua: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" x-model="cep.rua"  value="{{ old('logradouro') }}"
                                    name="logradouro" maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Número: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control"  value="{{ old('numero') }}"
                                    name="numero" maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label><strong>Complemento: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" value="{{ old('complemento') }}"
                                    name="complemento" maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Bairro: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" x-model="cep.bairro" value="{{ old('bairro') }}"
                                    name="bairro" maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Cidade: </strong><span class="red normal"></span></label>
                                <input type="text" class="form-control" x-model="cep.cidade" value="{{ old('cidade') }}"
                                    name="cidade" maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Estado: </strong><span class="red normal"></span></label>
                                <select name="uf" id="pac-uf"  class="form-control">
                                    <option value="">SELECIONE</option>
                                    @foreach (ESTADOS as $estado)
                                        <option value="{{ $estado["sigla"] }}" @if(old('uf')==$estado["sigla"]) selected @endif>{{ $estado["nome"] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Telefone: </strong><!--<span class="red normal">*</span>--></label>
                                <input type="text" class="form-control" value="{{ old('telefone') }}"
                                    name="telefone" maxlength="100" aria-required="true">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label><strong>Celular: </strong><!--<span class="red normal">*</span>--></label>
                                <input  type="text" class="form-control" value="{{ old('celular') }}"
                                    name="celular" maxlength="100" aria-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="checkbox">
                        <label>
                            <div class="checker"><span><input type="checkbox" name="vip" value="S"></span></div> Paciente Vip
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

