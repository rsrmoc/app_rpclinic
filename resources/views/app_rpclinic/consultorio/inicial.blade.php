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
        <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none">Consultório</h6>
    </div>
@endsection




@section('content')
    <!--start to page content-->
    <div class="page-content px-2 pt-0" x-data="appConsulta" style="padding-top: 0px !important; margin-top: -30px !important;">




        <div id="dataAgendamento"></div>



        
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



        <div class="px-2">
            <template x-for="(agendamento, index) in agendamentos" :key="index">
                <div class="col-md-4 col-sm-6" style="padding: 0 10px; margin-bottom: 20px;">
                    <div class="card-patient" style="padding: 20px; border-radius: 20px; background: #fff; border: 1px solid #eef2f6; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                        
                        <!-- Header: Initial + Name + Status -->
                        <div class="cp-header" style="display: flex; gap: 12px; align-items: flex-start; margin-bottom: 15px;">
                            <div style="min-width: 45px; width: 45px; height: 45px; background-color: #f2fcf9; color: #1e293b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 600; border: 1px solid #f0f0f0;">
                                <span x-text="agendamento.paciente?.nm_paciente ? agendamento.paciente?.nm_paciente.charAt(0) : '?'"></span>
                            </div>
                            <div style="flex: 1; overflow: hidden;">
                                <h3 style="margin: 0; font-size: 15px; font-weight: 700; color: #334155; text-transform: uppercase; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="agendamento.paciente?.nm_paciente"></h3>
                                <div style="display: flex; align-items: center; gap: 5px; margin-top: 4px; font-size: 13px; color: #64748b; font-weight: 500;">
                                    <i class="fa fa-check-circle" style="color: #2AB09C; font-size: 14px;"></i>
                                    <span x-text="capitalizeFirstLetter(agendamento.situacao)"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Time Separator -->
                        <div style="border-top: 1px solid #eef2f6; padding-top: 12px; margin-bottom: 15px;">
                            <span style="color: #0d9488; font-size: 14px; font-weight: 600;" 
                                  x-text="(agendamento.data_agenda ? formatDateLong(agendamento.data_agenda) : 'Data indefinida') + ' • ' + agendamento.hr_agenda"></span>
                        </div>

                        <!-- Doctor Section -->
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
                            <div style="display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0;">
                                <!-- Doctor Avatar with Badge -->
                                <div style="position: relative; width: 48px; height: 48px; flex-shrink: 0;">
                                    <div style="width: 100%; height: 100%; border-radius: 50%; background-color: #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        <i class="fa fa-user-md" style="font-size: 20px; color: #94a3b8;"></i>
                                    </div>
                                    <!-- Restored Badge -->
                                    <div style="position: absolute; bottom: -2px; right: -2px; width: 22px; height: 22px; background-color: #2AB09C; border: 2px solid #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-phone" style="font-size: 10px; color: #ffffff;"></i>
                                    </div>
                                </div>
                                
                                <!-- Doctor Text -->
                                <div style="flex: 1; min-width: 0;">
                                    <h5 style="margin: 0; font-size: 14px; font-weight: 700; color: #334155; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" x-text="agendamento.profissional?.nm_profissional"></h5>
                                    <span style="display: block; font-size: 10px; font-weight: 700; color: #0d9488; text-transform: uppercase; margin-top: 2px; letter-spacing: 0.5px;" 
                                          x-text="agendamento.especialidade?.nm_especialidade || agendamento.tipo_atend?.nm_tipo_atendimento || 'CLÍNICO GERAL'"></span>
                                    <span style="display: block; font-size: 12px; color: #0d9488; margin-top: 2px;" 
                                          x-text="agendamento.paciente?.celular || '(38) 99999-9999'"></span>
                                </div>
                            </div>

                            <!-- Side Phone Button -->
                            <a x-bind:href="'tel:' + agendamento.paciente?.celular" title="Ligar" 
                               style="width: 48px; height: 48px; background-color: #f0fdf9; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0d9488; font-size: 18px; text-decoration: none; border: 1px solid #e2e8f0; flex-shrink: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.03);">
                                <i class="fa fa-phone" style="transform: rotate(90deg);"></i>
                            </a>
                        </div>

                        <!-- Footer Buttons -->
                        <div style="display: flex; gap: 8px; height: 38px;">
                            <!-- Tipo Atendimento Button -->
                            <div style="flex: 1; background-color: #d1fae5; color: #115e59; border-radius: 9999px; display:flex; align-items:center; justify-content:center; padding: 0 10px;">
                                <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 100%;" x-text="agendamento.tipo_atend?.nm_tipo_atendimento || 'Atendimento'"></span>
                            </div>
                            
                            <!-- Atendimento Action Button -->
                            <a x-bind:href="routeConsultaBase.replace('/0', '/' + agendamento.cd_agendamento)" 
                               style="flex: 1; background-color: #fff; color: #334155; border: 1px solid #e2e8f0; border-radius: 9999px; text-align: center; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s; text-decoration: none;">
                                Atendimento
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
    <!--end to page content-->
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
             // Make function available to Alpine scope if needed, or just keep global
        });
        
        function formatDateLong(dateStr) {
            if (!dateStr) return '';
            // Handle YYYY-MM-DD
            let parts = dateStr.split('-');
            if(parts.length === 3) {
                 const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                 const year = parts[0];
                 const monthIndex = parseInt(parts[1]) - 1;
                 const day = parts[2];
                 return `${day} de ${months[monthIndex]} de ${year}`;
            }
            
            // If coming as DD/MM/YYYY
            parts = dateStr.split('/');
            if(parts.length === 3) {
                 const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
                 const day = parts[0];
                 const monthIndex = parseInt(parts[1]) - 1;
                 const year = parts[2];
                 return `${day} de ${months[monthIndex]} de ${year}`;
            }
            
            return dateStr;
        }

        function capitalizeFirstLetter(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
        }
    </script>
@endpush

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
        const routeAgendamentos = @js(route('app.api.agendamentos'));
        const routeAgendamentosDatas = @js(route('app.api.agendamentos-datas'));
        const routeConsultaBase = @js(route('app.consultorio.consulta', ['idAgendamento' => 0]));
    </script>
    <script src="{{ asset('js/app_rpclinica/consulta-list.js') }}"></script>
@endpush
