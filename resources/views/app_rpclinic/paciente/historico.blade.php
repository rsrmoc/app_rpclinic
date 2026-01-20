@extends('app_rpclinic.layout.layout')



@section('button_left')
        <div class="nav-button" onclick="history.back()"><a href="javascript:;"><i class="bi bi-arrow-left"></i></a></div>
        <div class="account-my-addresses">
        <h6 class="mb-0 fw-bold text-dark">Historico do Paciente</h6>
        </div>
@endsection

@section('button_add')
<li class="nav-item">
    <a class="nav-link" href="{{ route('app.paciente.doc', ['idPaciente'=> $paciente->cd_paciente]) }}"><i class="bi bi-folder-plus"></i></a>
</li>
@endsection

@section('content')

       <!--start to page content-->
       <div class="page-content">
            @foreach ($historicos as $historico)    
                <div class="review-item p-3 border rounded-3 bg-light">
                    <h6 class="client-name fw-bold">{{ $historico->nm_formulario }}</h6>

                    <div class="review-text">
                        <p>{!! $historico->conteudo !!}</p>
                        <p class="text-end mb-0 reviw-date">{{ $historico->nm_usuario }}</p>
                        <p class="text-end mb-0 reviw-date">{{ $historico->data }}</p>
                        <!--
                        <p class="text-end mb-0 reviw-date" style="font-size: 1.5em;">
                            <i class="bi bi-pencil-square me-2"></i>
                            <i class="bi bi-file-earmark-medical me-2"></i>
                        </p>
                        -->
                    </div>
                </div>

                <div class="py-2"></div>
            @endforeach
    </div>
    <!--end to page content-->


@endsection
