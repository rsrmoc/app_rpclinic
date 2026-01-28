@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 m-0">
        <div class="brand-logo mb-0">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 40px; width: auto;">
            </a>
        </div>
        <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none">Acesso Negado</h6>
    </div>
@endsection

@section('content')
<div class="container-fluid px-3 ">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <!-- Card de Permissão Negada -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center">
                    <!-- Ícone de Alerta -->
                    <div class="mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center" 
                            style="width: 100px; height: 100px; background: rgba(239, 68, 68, 0.1); border-radius: 50%;">
                            <i class="bi bi-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                        </div>
                    </div>

                    <!-- Título -->
                    <h2 class="fw-bold text-slate-900 mb-3">Acesso Negado</h2>

                    <!-- Mensagem -->
                    <p class="text-slate-600 mb-4 fs-5">
                        Desculpe, você <strong>não tem permissão</strong> para acessar esta página.
                    </p>

                    <!-- Descrição adicional -->
                    <div class="alert alert-warning border-0 rounded-3 mb-4" role="alert">
                        <i class="bi bi-shield-exclamation me-2"></i>
                        <span>Seu perfil de usuário não tem acesso a esta funcionalidade.</span>
                    </div>

                    <!-- URL Tentada -->
                    @if(isset($attemptedUrl))
                    <div class="alert alert-light border-1 border-slate-300 rounded-3 mb-4 text-start" role="alert">
                        <small class="text-slate-600 d-block mb-2">
                            <i class="bi bi-link me-2"></i><strong>URL que tentou acessar:</strong>
                        </small>
                        <code class="d-block bg-slate-50 p-2 rounded text-break" style="word-break: break-all;">
                            {{ $attemptedUrl }}
                        </code>
                    </div>
                    @endif

                    <!-- Botões de Ação -->
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('app.inicial') }}" class="btn btn-lg btn-outline-slate-400 rounded-3 px-3">
                            <i class="bi bi-house-door me-2"></i>
                            Voltar ao Início
                        </a>
                      
                    </div>

                    <!-- Código de erro -->
                    <hr class="my-5">
                    <small class="text-slate-400">
                        <i class="bi bi-info-circle me-2"></i>
                        Código de erro: 403 - Proibido
                    </small>
                </div>
            </div>

            <!-- Informação adicional -->
            <div class="alert alert-info border-0 rounded-3 mt-4 text-center" role="alert">
                <i class="bi bi-question-circle me-2"></i>
                <span>Se você acha que isso é um erro, <a href="{{ route('app.perfil') }}" class="alert-link">entre em contato com o administrador</a></span>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-outline-slate-400 {
        border-color: #cbd5e1;
        color: #64748b;
    }

    .btn-outline-slate-400:hover {
        border-color: #94a3b8;
        color: #475569;
        background-color: #f1f5f9;
    }

    code {
        background-color: #f1f5f9;
        padding: 8px 12px;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #1e293b;
        font-family: 'Courier New', monospace;
    }
</style>
@endsection
