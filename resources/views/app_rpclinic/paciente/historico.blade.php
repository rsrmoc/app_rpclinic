@extends('app_rpclinic.layout.layout')



@section('button_left')
    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('app_rpclinic/paciente') }}" class="text-slate-500 hover:text-teal-600 transition-colors p-1">
            <i class="bi bi-arrow-left text-2xl"></i>
        </a>
        <div class="brand-logo" style="width: auto;">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 60px; width: auto;" 
                     class="">
            </a>
        </div>
    </div>
@endsection

@section('button_add')
<li class="nav-item">
    <a class="nav-link" href="{{ route('app.paciente.doc', ['idPaciente'=> $paciente->cd_paciente]) }}"><i class="bi bi-folder-plus"></i></a>
</li>
@endsection

@section('content')

       <!--start to page content-->
        <div class="page-content px-3 py-2">
            @forelse ($historicos as $historico)    
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-3 transition-all">
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-slate-100">
                         <h6 class="text-teal-700 font-bold text-base mb-0 gap-2 flex items-center">
                             <i class="bi bi-file-text"></i> {{ $historico->nm_formulario }}
                         </h6>
                         <span class="badge bg-slate-100 text-slate-500 rounded-pill font-medium border border-slate-200 text-xs">
                             {{ $historico->data }}
                         </span>
                    </div>

                    <div class="review-text text-slate-700 mt-2" style="color: #334155 !important;">
                        <div class="prose prose-sm max-w-none text-slate-800">
                             {!! $historico->conteudo !!}
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-slate-50">
                             <small class="text-slate-400 font-bold text-xs uppercase">{{ $historico->nm_usuario }}</small>
                             <!-- Botão de Ver/Imprimir (Opcional, se quiser adicionar depois) -->
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="bg-slate-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                        <i class="bi bi-folder2-open text-3xl text-slate-300"></i>
                    </div>
                    <h6 class="text-slate-500 font-bold">Nenhum documento encontrado</h6>
                    <p class="text-slate-400 text-xs text-center">Os registros médicos aparecerão aqui.</p>
                </div>
            @endforelse
    </div>
    <!--end to page content-->


@endsection
