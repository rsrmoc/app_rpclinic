@extends('app_rpclinic.layout.layout')

@section('content')
<div class="container-fluid px-3 py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <!-- Card de Página Não Encontrada -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-5 text-center">
                    <!-- Ícone Grande 404 -->
                    <div class="mb-4">
                        <div style="font-size: 6rem; font-weight: bold; background: linear-gradient(135deg, #0d9488, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            404
                        </div>
                    </div>

                    <!-- Título -->
                    <h2 class="fw-bold text-slate-900 mb-3">Página Não Encontrada</h2>

                    <!-- Mensagem -->
                    <p class="text-slate-600 mb-4 fs-5">
                        Desculpe, a página que você está procurando <strong>não existe</strong> ou foi removida.
                    </p>

                    <!-- Descrição adicional -->
                    <div class="alert alert-warning border-0 rounded-3 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span>O caminho solicitado não corresponde a nenhuma página disponível no sistema.</span>
                    </div>

                    <!-- Sugestões -->
                    <div class="mb-4 p-3 bg-slate-50 rounded-3">
                        <p class="text-slate-600 mb-2 fw-bold">Verifique:</p>
                        <ul class="list-unstyled text-start text-slate-600 small">
                            <li><i class="bi bi-check2 text-teal-500 me-2"></i> Se a URL está correta</li>
                            <li><i class="bi bi-check2 text-teal-500 me-2"></i> Se a página foi movida ou renomeada</li>
                            <li><i class="bi bi-check2 text-teal-500 me-2"></i> Se você tem permissão para acessar este recurso</li>
                        </ul>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('app.inicial') }}" class="btn btn-lg btn-teal rounded-3 px-5">
                            <i class="bi bi-house-door me-2"></i>
                            Voltar ao Início
                        </a>
                        <button onclick="history.back()" class="btn btn-lg btn-outline-slate-400 rounded-3 px-5">
                            <i class="bi bi-arrow-left me-2"></i>
                            Voltar Atrás
                        </button>
                    </div>

                    <!-- Código de erro -->
                    <hr class="my-5">
                    <small class="text-slate-400">
                        <i class="bi bi-info-circle me-2"></i>
                        Código de erro: 404 - Not Found | URL: <code>{{ request()->path() }}</code>
                    </small>
                </div>
            </div>

            <!-- Informação adicional -->
            <div class="alert alert-info border-0 rounded-3 mt-4 text-center" role="alert">
                <i class="bi bi-question-circle me-2"></i>
                <span>Precisa de ajuda? <a href="{{ route('app.perfil') }}" class="alert-link">Entre em contato com o suporte</a></span>
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
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #e11d48;
    }
</style>
@endsection
