@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex flex-column align-items-center justify-content-center pt-1">
        <div class="brand-logo mb-0">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 40px; width: auto;">
            </a>
        </div>
        <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none">Consultório</h6>
    </div>
@endsection




@section('content')
    <!--start to page content-->
    <div class="page-content px-2 pt-0" x-data="appConsulta" style="padding-top: 0px !important;">




        <div id="dataAgendamento"></div>

        <div class="py-2"></div>

        
        <div>
            <template x-if="agendamentos.length==0">
                <div class="mb-3">
                    <div style="text-align: center; margin-top: 10px;" >
                        <img src="{{ asset('app/assets/images/historico-medico.png') }}" style="max-width: 65%; opacity: 0.5; filter: grayscale(1);" class="img-fluid" alt="">
                    </div>
                </div>
            </template>
        </div>

        <template x-if="loading">
            <div class="mb-3 text-slate-600 flex items-center justify-center">
                <span class="spinner-border spinner-border-sm text-primary" aria-hidden="true"></span>&ensp;
                <span>Carregando...</span>
            </div>
        </template>



        <div>
            <template x-for="agendamento, index in agendamentos" x-bind:key="index">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-4 p-4 transition-all duration-300 hover:shadow-md">
                     <div class="flex flex-row gap-4">
                         <div class="flex-grow text-slate-600">
                                  <span class="font-bold text-lg text-slate-900 block mb-1" x-text="agendamento.paciente.nm_paciente"></span>
                                  <div class="text-sm space-y-1">
                                      <div><strong class="text-teal-600">Data:</strong> <span x-text="agendamento.data_agenda+' as '+agendamento.hr_agenda"></span></div>
                                      <div><strong class="text-teal-600">Profissional:</strong> <span x-text="agendamento.profissional.nm_profissional"></span></div>
                                      <div><strong class="text-teal-600">Especialidade:</strong> <span x-text="agendamento.especialidade.nm_especialidade"></span></div>
                                      <div><strong class="text-teal-600">Número:</strong> <span x-text="agendamento.celular"></span></div>
                                      
                                      <div class="mt-2 flex gap-2">
                                           <span class="px-2 py-1 rounded text-xs font-bold" x-bind:class="agendamento.tipo_atend.cor" x-text="capitalizeFirstLetter(agendamento.tipo_atend.nm_tipo_atendimento)"></span>
                                           <span class="px-2 py-1 rounded text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200" x-bind:class="classLabelSituacao[agendamento.situacao.toLocaleLowerCase()]" x-text="capitalizeFirstLetter(agendamento.situacao)"></span>
                                      </div>
                                  </div>
                         </div>
                         
                         <div class="flex flex-col gap-2 justify-center">
                             <a x-bind:href="`/app_rpclinic/consultorio-consulta/${agendamento.cd_agendamento}`" class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center hover:bg-teal-100 transition-colors border border-teal-100">
                                 <i class="fa fa-stethoscope text-xl"></i>
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
        /* Ajuste do DatePicker para Fundo Branco e Texto Escuro */
        .datePickerAgendamento {
            width: 100% !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important; /* slate-200 */
            border-radius: 20px !important;
            padding: 5px 15px 15px 15px !important;
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

        /* Ajuste para colar no topo */
        #dataAgendamento {
            margin-top: -10px !important;
        }

        /* Ponto indicador de evento - Ajustado para ser maior e verde */
        .has-event-dot {
            position: relative;
        }
        .has-event-dot::after {
            content: '' !important;
            position: absolute !important;
            bottom: 4px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 6px !important;
            height: 6px !important;
            background-color: #10b981 !important; /* Verde Emerald */
            border-radius: 50% !important;
            display: block !important;
        }
        
        .air-datepicker-cell.-selected-.has-event-dot::after {
            background-color: #ffffff !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const cdProfissional = {{ $cd_profissional ?? auth()->guard('rpclinica')->user()->cd_profissional ?? 'null' }};
        const routeAgendamentos = @js(url('app_rpclinic/api/agendamentos'));
        const routeAgendamentosDatas = @js(url('app_rpclinic/api/agendamentos-datas'));
    </script>
    <script src="{{ asset('js/app_rpclinica/consulta-list.js') }}"></script>
@endpush
