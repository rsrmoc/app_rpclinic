@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="brand-logo">
        <a href="javascript:;"><img src="{{ asset('app/assets/images/logo_menu.svg') }}" width="190" alt=""></a>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appAgendamento">
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
              class="btn btn-ecomm rounded-3 btn-dark flex-fill"
              style="height: 60px; font-weight: 600; padding: 1.2rem 1.5rem;"
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
        .datePickerAgendamento {
            width: 100% !important;
            background: rgba(255, 255, 255, 0.05) !important;
            backdrop-blur: 10px !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 20px !important;
            padding: 15px !important;
            color: white !important;
            font-family: inherit !important;
        }
        
        .air-datepicker-nav {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
            background: transparent !important;
            margin-bottom: 15px !important;
        }

        .air-datepicker-nav--title, .air-datepicker-nav--action {
            color: white !important;
            font-weight: bold !important;
        }
        
        .air-datepicker-nav--action:hover {
            background: rgba(255, 255, 255, 0.1) !important;
        }

        .air-datepicker-body--day-name {
            color: #2dd4bf !important; /* teal-400 */
            font-weight: bold !important;
            text-transform: uppercase !important;
            font-size: 0.8rem !important;
        }

        .air-datepicker-cell {
            color: #cbd5e1 !important; /* slate-300 */
            font-size: 1.1rem !important; /* Larger numbers */
            height: 45px !important;
            border-radius: 12px !important;
        }

        .air-datepicker-cell.-current- {
            color: #2dd4bf !important;
            font-weight: bold !important;
        }

        .air-datepicker-cell.-selected-, .air-datepicker-cell.-selected-.-current- {
            background: #2dd4bf !important;
            color: #0f172a !important;
            font-weight: bold !important;
        }

        .air-datepicker-cell:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
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
    </script>
    <script src="{{ asset('/js/app_rpclinica/disponibilidade.js') }}"></script>
@endpush
