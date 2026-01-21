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
        <h6 class="mb-0 text-slate-700 font-bold uppercase tracking-tight">Agenda</h6>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appAgendamento">


        @isset($profissionais)
        <div class="mb-4 p-4 rounded-xl border border-white/10" style="background: rgba(255, 255, 255, 0.05);">
            <label class="block text-slate-300 mb-2 font-bold">Visualizar Agenda de:</label>
            <select class="w-full bg-slate-800 border-slate-700 text-slate-200 rounded-lg p-2" 
                    onchange="if(this.value) window.location.search = '?cd_profissional='+this.value">
                <option value="">Selecione um Profissional...</option>
                @foreach($profissionais as $p)
                    <option value="{{ $p->cd_profissional }}" {{ ($cd_profissional ?? null) == $p->cd_profissional ? 'selected' : '' }}>
                        {{ $p->nm_profissional }}
                    </option>
                @endforeach
            </select>
            @if(empty($cd_profissional) && empty(auth()->guard('rpclinica')->user()->cd_profissional))
                <div class="text-amber-400 text-sm mt-2">⚠ Por favor, selecione um profissional para visualizar a agenda.</div>
            @endif
        </div>
        @endisset

        <div id="dataAgendamento"></div>

        <div class="py-2"></div>

        <template x-if="loading">
            <div class="mb-3">
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>&ensp;
                <span>Carregando...</span>
            </div>
        </template>

        <div>
            <template x-if="agendamentos.length==0">
                <div class="mb-3">
                    <div style="text-align: center; margin-top: 10px;" >
                        <img src="{{ asset('app/assets/images/agendar.png') }}" style="max-width: 65%;" class="img-fluid" alt="">
                    </div>
                </div>
            </template>
        </div>
 
        <div>
            <template x-for="agendamento, index in agendamentos" x-bind:key="index">
                <div class="bg-white rounded-2xl shadow-md border border-slate-100 mb-4 p-5 transition-all duration-300 hover:shadow-lg hover:border-teal-200">
                     <div class="flex flex-row gap-4">
                         <div class="flex-grow">
                                 <!-- Nome do Paciente -->
                                 <div class="flex items-center gap-2 mb-3 border-b border-slate-100 pb-2">
                                     <div class="w-10 h-10 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-lg">
                                         <span x-text="agendamento.paciente.nm_paciente.charAt(0)"></span>
                                     </div>
                                     <span class="font-extrabold text-lg text-slate-800 leading-tight" x-text="agendamento.paciente.nm_paciente"></span>
                                 </div>

                                 <div class="space-y-2 text-sm">
                                     <div class="flex flex-col">
                                         <span class="text-teal-600 font-bold text-xs uppercase tracking-wide">Data & Hora</span>
                                         <span class="text-slate-800 font-bold text-base" x-text="agendamento.data_agenda+' às '+agendamento.hr_agenda"></span>
                                     </div>

                                     <div class="grid grid-cols-2 gap-2">
                                         <div class="flex flex-col">
                                             <span class="text-teal-600 font-bold text-xs uppercase tracking-wide">Profissional</span>
                                             <span class="text-slate-700 font-bold truncate" x-text="agendamento.profissional.nm_profissional"></span>
                                         </div>
                                         <div class="flex flex-col">
                                             <span class="text-teal-600 font-bold text-xs uppercase tracking-wide">Especialidade</span>
                                             <span class="text-slate-700 font-bold truncate" x-text="agendamento.especialidade.nm_especialidade"></span>
                                         </div>
                                     </div>
                                     
                                     <div class="flex flex-col">
                                         <span class="text-teal-600 font-bold text-xs uppercase tracking-wide">Telefone</span>
                                         <span class="text-slate-900 font-bold" x-text="agendamento.celular"></span>
                                     </div>
                                     
                                     <div class="mt-3 flex flex-wrap gap-2 pt-2">
                                          <span class="px-3 py-1.5 rounded-lg text-xs font-bold border" x-bind:class="agendamento.tipo_atend.cor" style="border-width: 1px;" x-text="capitalizeFirstLetter(agendamento.tipo_atend.nm_tipo_atendimento)"></span>
                                          <span class="px-3 py-1.5 rounded-lg text-xs font-bold border" x-bind:class="classLabelSituacao[agendamento.situacao.toLocaleLowerCase()]" style="background-color: #f1f5f9; color: #334155; border-color: #cbd5e1;" x-text="capitalizeFirstLetter(agendamento.situacao)"></span>
                                     </div>
                                 </div>
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
        const cdProfissional = {{ $cd_profissional ?? auth()->guard('rpclinica')->user()->cd_profissional ?? 'null' }};
        const routeAgendamentos = @js(url('app_rpclinic/api/agendamentos'));
        const routeAgendamentosDatas = @js(url('app_rpclinic/api/agendamentos-datas'));
    </script>
    <script src="{{ asset('/js/app_rpclinica/agendamento.js') }}"></script>
@endpush
