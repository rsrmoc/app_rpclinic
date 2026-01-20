@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="brand-logo">
        <a href="javascript:;"><img src="{{ asset('app/assets/images/logo_menu.svg') }}" width="190" alt=""></a>
    </div>
@endsection




@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appConsulta">


        @isset($profissionais)
        <div class="mb-4 p-4 rounded-xl border border-white/10" style="background: rgba(255, 255, 255, 0.05);">
            <label class="block text-slate-300 mb-2 font-bold">Visualizar Consultório de:</label>
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
                <div class="text-amber-400 text-sm mt-2">⚠ Por favor, selecione um profissional para visualizar o consultório.</div>
            @endif
        </div>
        @endisset

        <div id="dataAgendamento"></div>

        <div class="py-2"></div>

        
        <div>
            <template x-if="agendamentos.length==0">
                <div class="mb-3">
                    <div style="text-align: center; margin-top: 10px;" >
                        <img src="{{ asset('app/assets/images/historico-medico.png') }}" style="max-width: 65%;" class="img-fluid" alt="">
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
            <template x-for="agendamento, index in agendamentos" x-bind:key="index">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-lg border border-white/10 mb-4 p-4 transition-all duration-300 hover:bg-white/15">
                     <div class="flex flex-row gap-4">
                         <div class="flex-grow text-slate-200">
                                  <span class="font-bold text-lg text-white block mb-1" x-text="agendamento.paciente.nm_paciente"></span>
                                  <div class="text-sm space-y-1">
                                      <div><strong class="text-teal-400">Data:</strong> <span x-text="agendamento.data_agenda+' as '+agendamento.hr_agenda"></span></div>
                                      <div><strong class="text-teal-400">Profissional:</strong> <span x-text="agendamento.profissional.nm_profissional"></span></div>
                                      <div><strong class="text-teal-400">Especialidade:</strong> <span x-text="agendamento.especialidade.nm_especialidade"></span></div>
                                      <div><strong class="text-teal-400">Número:</strong> <span x-text="agendamento.celular"></span></div>
                                      
                                      <div class="mt-2 flex gap-2">
                                           <span class="px-2 py-1 rounded text-xs font-bold" x-bind:class="agendamento.tipo_atend.cor" x-text="capitalizeFirstLetter(agendamento.tipo_atend.nm_tipo_atendimento)"></span>
                                           <span class="px-2 py-1 rounded text-xs font-bold bg-slate-700 text-slate-200 border border-slate-600" x-bind:class="classLabelSituacao[agendamento.situacao.toLocaleLowerCase()]" x-text="capitalizeFirstLetter(agendamento.situacao)"></span>
                                      </div>
                                  </div>
                         </div>
                         
                         <div class="flex flex-col gap-2 justify-center">
                             <a x-bind:href="`/app_rpclinic/consultorio-consulta/${agendamento.cd_agendamento}`" class="w-12 h-12 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center hover:bg-teal-500/30 transition-colors border border-teal-500/30">
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
        const cdProfissional = {{ $cd_profissional ?? auth()->guard('rpclinica')->user()->cd_profissional ?? 'null' }};
    </script>
    <script src="{{ asset('js/app_rpclinica/consulta-list.js') }}"></script>
@endpush
