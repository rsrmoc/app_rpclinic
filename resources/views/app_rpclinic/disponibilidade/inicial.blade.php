@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex flex-column align-items-center">
        <div class="brand-logo">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 45px; width: auto;">
            </a>
        </div>
        <h6 class="mb-0 text-slate-700 font-bold uppercase tracking-tight" style="font-size: 11px; margin-top: -5px;">Disponibilidade</h6>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content pt-0" x-data="appAgendamento">
        <div class="pt-2">
            <form x-on:submit.prevent="saveProfile" class="row g-3 needs-validation"
             id="formProfile">
              @csrf

              <div id="dataAgendamento"></div>

              <div class="py-2"></div>
      
              <template x-if="loading">
                  <div class="mb-3">
                      <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>&ensp;
                      <span>Carregando...</span>
                  </div>
              </template>
      
              <button type="submit"
              class="btn btn-ecomm rounded-3 flex-fill text-white"
              style="height: 60px; font-weight: 600; padding: 1.2rem 1.5rem; background-color: #0d9488; border-color: #0d9488;"
              x-bind:disabled="loading">
                <template x-if="loading">
                  <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                </template>
                <span>Salvar</span>
              </button>

            </form>

        </div>

 
      
    </div>
    <!--end to page content-->
@endsection

@push('styles')
    <style>
        /* Ajuste do DatePicker para Fundo Branco e Texto Escuro */
        .datePickerAgendamento {
            width: 100% !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important; /* slate-200 */
            border-radius: 20px !important;
            padding: 15px !important;
            color: #1e293b !important; /* slate-800 */
            font-family: inherit !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        
        .air-datepicker-nav {
            border-bottom: 1px solid #f1f5f9 !important;
            background: transparent !important;
            margin-bottom: 5px !important; /* Reduced margin */
        }

        .air-datepicker-nav--title, .air-datepicker-nav--action {
            color: #334155 !important; /* slate-700 */
            font-weight: 800 !important;
        }
        
        .air-datepicker-nav--action:hover {
            background: #f8fafc !important;
        }

        .air-datepicker-body--day-name {
            color: #0d9488 !important; /* teal-600 */
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 0.8rem !important;
        }

        .air-datepicker-cell {
            color: #64748b !important; /* slate-500 */
            font-size: 1.1rem !important;
            height: 45px !important;
            border-radius: 12px !important;
            font-weight: 600 !important;
            position: relative; /* Needed for ::after positioning */
        }

        .air-datepicker-cell.-current- {
            color: #0d9488 !important;
            font-weight: 900 !important;
            background: #f0fdfa !important; /* teal-50 */
        }

        .air-datepicker-cell.-selected-, .air-datepicker-cell.-selected-.-current- {
            background: #0d9488 !important; /* teal-600 */
            color: #ffffff !important;
            font-weight: bold !important;
            box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.3);
        }

        .air-datepicker-cell:hover {
            background: #f1f5f9 !important; /* slate-100 */
            color: #0f172a !important;
        }
        
        .air-datepicker-cell.-other-month- {
            color: #cbd5e1 !important; /* slate-300 */
        }

        /* Ponto indicador de evento */
        .has-event-dot::after {
            content: '' !important;
            position: absolute !important;
            bottom: 4px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 6px !important;
            height: 6px !important;
            border-radius: 50% !important;
            background-color: #0d9488 !important;
            box-shadow: none !important;
        }
        
        .air-datepicker-cell.-selected-.has-event-dot::after {
            background-color: #ffffff !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const cdProfissional = {{ auth()->guard('rpclinica')->user()->cd_profissional ?? 'null' }};
        const routeAgendamentos = @js(url('app_rpclinic/api/agendamentos'));
        const routeAgendamentosDatas = @js(url('app_rpclinic/api/agendamentos-datas'));
    </script>
    <script src="{{ asset('/js/app_rpclinica/disponibilidade.js') }}"></script>
@endpush
