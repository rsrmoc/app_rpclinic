@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="brand-logo">
        <a href="javascript:;"><img src="{{ asset('app/assets/images/logo_menu.svg') }}" width="190" alt=""></a>
    </div>
@endsection




@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appDocumentos">

        <div id="documentosDatePicker"></div>

        <div class="py-2"></div>

        <div>
            <template x-if="documentos.length==0">
                <div class="mb-3">
                    <div style="text-align: center; margin-top: 30px;" >
                        <img src="{{ asset('app/assets/images/arquivo-medico.png') }}" class="img-fluid" alt="">
                    </div>
                </div>
            </template>
        </div>

        <template x-if="loading">
            <div class="mb-3">
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>&ensp;
                <span>Carregando...</span>
            </div>
        </template>

        <div>
            <template x-for="documento, index in documentos" x-bind:key="index">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-lg border border-white/10 mb-4 p-4 transition-all duration-300 hover:bg-white/15">
                     <div class="flex flex-row gap-4">
                         <div class="flex-grow text-slate-200">
                                  <span class="font-bold text-lg text-white block mb-1" x-text="documento.agendamento.paciente.nm_paciente"></span>
                                  <div class="text-sm space-y-1">
                                      <div><strong class="text-teal-400">Doc.:</strong> <span x-text="documento.nm_formulario"></span></div>
                                      <div><strong class="text-teal-400">Data:</strong> <span x-text="formatDate(documento.created_at)"></span></div>
                                      <div><strong class="text-teal-400">Profissional:</strong> <span x-text="documento.agendamento.profissional.nm_profissional"></span></div>
                                      <div><strong class="text-teal-400">Especialidade:</strong> <span x-text="documento.agendamento.especialidade.nm_especialidade"></span></div>
                                  </div>
                         </div>
                         
                         <div class="flex flex-col justify-center">
                             <a target="_blank" x-bind:href="`/rpclinica/json/imprimirDocumentoGeral/`+documento.agendamento.cd_agendamento+`/`+documento.cd_documento" class="w-12 h-12 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center hover:bg-teal-500/30 transition-colors border border-teal-500/30">
                                 <i class="fa fa-print text-xl"></i>
                             </a>
                         </div>
                     </div>
                </div>
            </template>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('js/app_rpclinica/documentos.js') }}"></script>
@endpush
