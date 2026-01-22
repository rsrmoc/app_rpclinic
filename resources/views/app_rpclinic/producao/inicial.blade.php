@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="brand-logo pt-0 m-0" style="width: auto;">
        <a href="javascript:;" class="d-flex justify-content-center align-items-center">
            <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                 alt="Logo" 
                 style="height: 60px; width: auto;" 
                 class="">
        </a>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appProducao" style="padding-top: 0px !important; margin-top: -30px !important;">
        <div class="card-body">
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
              class="btn btn-primary w-100 rounded-3 text-white shadow-sm mt-2"
              style="height: 55px; font-weight: 600; font-size: 16px; background-color: #0d9488; border-color: #0d9488;"
              x-bind:disabled="loading">
                <template x-if="loading">
                  <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
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
        .datePickerAgendamento {
            width: 100% !important;
            background: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 20px !important;
            padding: 15px !important;
            color: #0f172a !important;
            font-family: inherit !important;
        }
        
        .air-datepicker-nav {
            border-bottom: 1px solid rgba(15, 23, 42, 0.1) !important;
            background: transparent !important;
            margin-bottom: 15px !important;
        }

        .air-datepicker-nav--title, .air-datepicker-nav--action {
            color: #0f172a !important; /* Dark text */
            font-weight: 800 !important;
            font-size: 1.1rem !important;
        }
        
        .air-datepicker-nav--action:hover {
            background: rgba(15, 23, 42, 0.05) !important;
        }

        .air-datepicker-body--day-name {
            color: #0f766e !important; /* teal-700 - Much Darker for contrast */
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 0.9rem !important;
        }

        .air-datepicker-cell {
            color: #0f172a !important; /* Dark Slate - High Contrast */
            font-size: 1.2rem !important; 
            font-weight: 700 !important; /* Bold */
            height: 45px !important;
            border-radius: 12px !important;
        }

        .air-datepicker-cell.-current- {
            color: #2dd4bf !important;
            font-weight: 800 !important;
        }

        .air-datepicker-cell.-selected-, .air-datepicker-cell.-selected-.-current- {
            background: #2dd4bf !important;
            color: #ffffff !important;
            font-weight: 800 !important;
        }

        /* Hover desabilitado - apenas clique tem efeito */
        .air-datepicker-cell:hover {
            background: transparent !important;
        }

        .air-datepicker-cell.-other-month- {
            color: rgba(255, 255, 255, 0.15) !important;
        }

        /* Marcador de evento (Ponto) */
        .has-event-dot {
            position: relative !important;
        }
        
        .has-event-dot::after {
            content: '' !important;
            position: absolute !important;
            bottom: 6px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 5px !important;
            height: 5px !important;
            background-color: #2dd4bf !important; /* teal dot */
            border-radius: 50% !important;
            box-shadow: 0 0 8px #2dd4bf !important;
        }

        .air-datepicker-cell.-selected-.has-event-dot::after {
            background-color: #0f172a !important; /* Dark dot if cell is teal */
            box-shadow: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const cdProfissional = {{ auth()->guard('rpclinica')->user()->cd_profissional ?? 'null' }};
        const routeAgendamentos = @js(url('app_rpclinic/api/agendamentos'));
        const routeAgendamentosDatas = @js(url('app_rpclinic/api/agendamentos-datas'));
    </script>
    <script src="{{ asset('/js/app_rpclinica/producao.js') }}"></script>
@endpush
