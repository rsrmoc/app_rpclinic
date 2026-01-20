@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Tutoriais</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Lista de Videos</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="col-md-6 ">
                <div class="panel panel-white">
                    <div class="panel-body">

                        <video controls width="100%" height="240px;">
                            <source src="{{ asset('turorial/ConsultorioMedico.webm') }}" type="video/webm">

                            <source src="{{ asset('turorial/ConsultorioMedico.mp4') }}" type="video/mp4">

                            Download the
                            <a href="{{ asset('turorial/ConsultorioMedico.webm') }}">WEBM</a>
                            or
                            <a href="{{ asset('turorial/ConsultorioMedico.mp4') }}">MP4</a>
                            video.
                        </video>
                        <figcaption><h3 style="     font-weight: 300;  font-size: 150%;">Consultório</h3>
                            <p>Tela Prontuário Eletrônico</p></figcaption>
                    </div>
                </div>
            </div>

            <div class="col-md-6 ">
                <div class="panel panel-white">
                    <div class="panel-body">

                        <video controls width="100%"  height="240px;">
                            <source src="{{ asset('turorial/PerfilProfissional.webm') }}" type="video/webm">

                            <source src="{{ asset('turorial/PerfilProfissional.mp4') }}" type="video/mp4">

                            Download the
                            <a href="{{ asset('turorial/PerfilProfissional.webm') }}">WEBM</a>
                            or
                            <a href="{{ asset('turorial/PerfilProfissional.mp4') }}">MP4</a>
                            video.
                        </video>
                        <figcaption><h3 style="     font-weight: 300;  font-size: 150%;">Perfil Profissional</h3>
                            <p>Tela de Perfil Profissional <Br> </p></figcaption>
                    </div>
                </div>
            </div>


        </div>

    </div><!-- Main Wrapper -->
@endsection


<script src=" {{ asset('assets/plugins/gridgallery/js/imagesloaded.pkgd.min.js') }} "></script>
<script src=" {{ asset('assets/plugins/gridgallery/js/masonry.pkgd.min.js') }}"></script>
<script src=" {{ asset('assets/plugins/gridgallery/js/classie.js') }}"></script>
<script src=" {{ asset('assets/plugins/gridgallery/js/cbpgridgallery.js') }}"></script>

@section('script')

@endsection




