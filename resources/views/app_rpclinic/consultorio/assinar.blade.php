@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex align-items-center gap-3">
        <div class="brand-logo" style="width: auto;">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 60px; width: auto;" 
                     class="">
            </a>
        </div>
        <div class="border-start border-slate-300 h-6 mx-1"></div>
        <h6 class="mb-0 text-slate-700 font-bold uppercase tracking-tight">Documentos</h6>
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
                        <img src="{{ asset('app/assets/images/arquivo-medico.png') }}" class="img-fluid w-32 opacity-80" alt="">
                        <p class="text-slate-400 font-medium mt-3">Nenhum documento para assinar nesta data.</p>
                    </div>
                </div>
            </template>
        </div>

        <template x-if="loading">
            <div class="mb-3 text-center p-4">
                <div class="spinner-border text-teal-600" role="status"></div>
                <div class="text-teal-600 font-bold mt-2">Carregando documentos...</div>
            </div>
        </template>

        <div>
            <template x-for="documento, index in documentos" x-bind:key="index">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-4 p-4 transition-all duration-300 hover:shadow-md hover:border-teal-200">
                     <div class="flex flex-row gap-4">
                         <div class="flex-grow">
                                  <div class="flex justify-between items-start mb-2">
                                      <span class="font-extrabold text-lg text-slate-800 leading-tight" x-text="documento.agendamento.paciente.nm_paciente"></span>
                                      <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded text-xs font-bold" x-text="formatDate(documento.created_at)"></span>
                                  </div>
                                  
                                  <div class="text-sm space-y-2">
                                      <div class="flex items-center gap-2">
                                          <i class="bi bi-file-earmark-text text-teal-500 text-lg"></i>
                                          <div class="flex flex-col">
                                              <span class="text-xs text-slate-400 font-bold uppercase">Documento</span>
                                              <span class="text-slate-700 font-bold" x-text="documento.nm_formulario"></span>
                                          </div>
                                      </div>

                                      <div class="grid grid-cols-2 gap-2 mt-2">
                                          <div class="flex flex-col">
                                              <strong class="text-teal-600 text-xs uppercase">Profissional</strong>
                                              <span class="text-slate-600 font-semibold" x-text="documento.agendamento.profissional.nm_profissional"></span>
                                          </div>
                                          <div class="flex flex-col">
                                              <strong class="text-teal-600 text-xs uppercase">Especialidade</strong>
                                              <span class="text-slate-600 font-semibold" x-text="documento.agendamento.especialidade.nm_especialidade"></span>
                                          </div>
                                      </div>
                                  </div>
                         </div>
                         
                         <div class="flex flex-col justify-center border-l border-slate-100 pl-3 gap-2">
                             <a target="_blank" x-bind:href="`/rpclinica/json/imprimirDocumentoGeral/`+documento.agendamento.cd_agendamento+`/`+documento.cd_documento" 
                                class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center hover:bg-teal-100 transition-colors border border-teal-100 shadow-sm"
                                title="Imprimir/Visualizar">
                                 <i class="fa fa-print text-lg"></i>
                             </a>
                             <button type="button" @click="compartilharDoc(documento)"
                                class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-100 transition-colors border border-indigo-100 shadow-sm"
                                title="Compartilhar">
                                 <i class="bi bi-share text-lg"></i>
                             </button>
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
            margin-bottom: 15px !important;
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
            background-color: #0d9488 !important;
            box-shadow: none !important;
            bottom: 4px !important;
        }
        
        .air-datepicker-cell.-selected-.has-event-dot::after {
            background-color: #ffffff !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const cdProfissional = {{ auth()->guard('rpclinica')->user()->cd_profissional ?? 'null' }};
        const routeDocumentos = @js(url('app_rpclinic/api/documentos'));
        const routeAgendamentosDatas = @js(url('app_rpclinic/api/agendamentos-datas'));
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="{{ asset('js/app_rpclinica/documentos.js') }}"></script>
@endpush




