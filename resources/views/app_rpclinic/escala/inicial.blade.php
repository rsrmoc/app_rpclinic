@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 m-0">
        <div class="brand-logo mb-0">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" alt="Logo" style="height: 40px; width: auto;">
            </a>
        </div>
        <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none">Escalas</h6>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content px-2 pt-0" x-data="appAgendamento"
        style="padding-top: 0px !important; margin-top: -30px !important;">


        <div id="dataAgendamento"></div>

        <div class="py-2"></div>

      
        <!-- Loading -->
        <div x-show="loading" class="mb-3 text-center p-4">
            <div class="spinner-border text-teal-600" role="status"></div>
            <div class="text-teal-600 font-bold mt-2">Carregando Escalas...</div>
        </div>

        <div>
            <template x-if="agendamentos.length==0">
                <div class="mb-3">
                    <div class="d-flex justify-content-center align-items-center mt-3">
                        <img src="{{ asset('app/assets/images/agendar.png') }}"
                            style="max-width: 65%; opacity: 0.5; filter: grayscale(1);" class="img-fluid" alt="">
                    </div>
                </div>
            </template>
        </div>

        <div>
            <template x-for="agendamento, index in agendamentos" x-bind:key="index">
                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-3 p-2 transition-all duration-300 hover:shadow-md hover:border-teal-200">
                    <div class="flex flex-row gap-3">
                        <div class="flex-grow">
                            <!-- Nome do Paciente -->
                            <div class="flex items-center gap-3 mb-1 border-b border-slate-50 pb-2">
                                <div
                                    class="w-9 h-9 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-sm border border-teal-100">
                                    <i class="bi bi-calendar2-event"></i>
                                </div>
                                <span class="font-bold text-sm text-slate-800 leading-tight uppercase"
                                    x-text="' Escala #' + agendamento.cd_escala_medica"></span>
                            </div>

                            <div class="space-y-2">
                                <!-- Data & Hora -->
                                <div class="flex flex-col">
                                    <span class="text-teal-600 font-bold text-[10px] uppercase tracking-widest mb-0.5">Data
                                        & Hora</span>
                                    <span class="text-slate-700 font-bold text-sm"
                                        x-text="agendamento.data_agenda + ' das [ '+agendamento.hr_inicial + ' às ' + agendamento.hr_final + ' ]'"></span>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-teal-600 font-bold text-[10px]  tracking-widest mb-0.5">Profissional</span>
                                        <span class="text-slate-700 font-semibold text-xs truncate"
                                            x-text=" agendamento.profissional?.nm_profissional ? capitalizeFirstLetter(agendamento.profissional.nm_profissional) : ' -- '   "></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-teal-600 font-bold text-[10px]   tracking-widest mb-0.5">Especialidade</span>
                                        <span class="text-slate-700 font-semibold text-xs truncate"
                                            x-text=" agendamento.especialidade?.nm_especialidade ? capitalizeFirstLetter(agendamento.especialidade.nm_especialidade) : ' -- '   "></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-teal-600 font-bold text-[10px]   tracking-widest mb-0.5">Localidade</span>
                                        <span class="text-slate-700 font-semibold text-xs truncate"
                                            x-text=" agendamento.localidade?.nm_localidade ? capitalizeFirstLetter(agendamento.localidade.nm_localidade) : ' -- '   "></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-teal-600 font-bold text-[10px]   tracking-widest mb-0.5">Cidade</span>
                                        <span class="text-slate-700 font-semibold text-xs truncate"
                                            x-text=" agendamento.localidade?.ds_cidade ? capitalizeFirstLetter(agendamento.localidade.ds_cidade) : ' -- '   "></span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-teal-600 font-bold text-[10px]   tracking-widest mb-0.5">Tipo
                                            de Escala</span>
                                        <span class="text-slate-700 font-semibold text-xs truncate"
                                            x-text="agendamento.tipo_escala?.nm_tipo_escala ? capitalizeFirstLetter(agendamento.tipo_escala.nm_tipo_escala) : ' -- '"></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span
                                            class="text-teal-600 font-bold text-[10px] uppercase  tracking-widest mb-0.5 flex items-center gap-1">
                                            <i class="bi bi-circle-fill text-teal-600 text-[6px]"></i>
                                            Situação
                                        </span>
                                        <span class="font-bold text-xs truncate flex items-center gap-1" :style="capitalizeFirstLetter(agendamento.situacao) === 'Agendado' ? 'color: #F19958;' : capitalizeFirstLetter(agendamento.situacao) === 'Confirmado' ? 'color: #7a6fbe;' : capitalizeFirstLetter(agendamento.situacao) === 'Finalizado' ? 'color: #12AFCB;' : capitalizeFirstLetter(agendamento.situacao) === 'Pago' ? 'color: #2ecc71;' : capitalizeFirstLetter(agendamento.situacao) === 'Cancelado' ? 'color: #f25656;' : 'color: #0d9488;'">
                                            <template x-if="capitalizeFirstLetter(agendamento.situacao) === 'Agendado'">
                                                <i class="bi bi-calendar-check text-[12px]" style="color: #F19958;"></i>
                                            </template>
                                            <template x-if="capitalizeFirstLetter(agendamento.situacao) === 'Confirmado'">
                                                <i class="bi bi-check-circle text-[12px]" style="color: #7a6fbe;"></i>
                                            </template>
                                            <template x-if="capitalizeFirstLetter(agendamento.situacao) === 'Finalizado'">
                                                <i class="bi bi-check-circle-fill text-[12px]" style="color: #12AFCB;"></i>
                                            </template>
                                            <template x-if="capitalizeFirstLetter(agendamento.situacao) === 'Pago'">
                                                <i class="bi bi-coin text-[12px]" style="color: #2ecc71;"></i>
                                            </template>
                                            <template x-if="capitalizeFirstLetter(agendamento.situacao) === 'Cancelado'">
                                                <i class="bi bi-x-circle text-[12px]" style="color: #f25656;"></i>
                                            </template>
                                            <span x-text="capitalizeFirstLetter(agendamento.situacao)"></span>
                                        </span>
                                    </div>
                                </div>



                                <div class="mt-0 flex flex-wrap gap-2 pt-3 border-t border-slate-50 justify-center">
                                    <!-- Ações -->
                                    <template x-if="capitalizeFirstLetter(agendamento.situacao) === 'Agendado'">
                                        <div @click="showConfirmModal = true; escalaToConfirm = agendamento.cd_escala_medica"
                                            class="px-3 py-2 rounded-xl flex items-center justify-center bg-green-50 border border-green-200 hover:bg-green-100 transition-colors cursor-pointer">
                                            <i class="bi bi-check-circle text-green-600 text-[14px]"></i>
                                            <span class="text-[11px] font-bold text-green-600 m-1">Confirmar Escala</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
            </template>
        </div>

        <!-- Modal de Confirmação -->
        <div x-show="showConfirmModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                        <i class="bi bi-question-circle text-yellow-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Confirmar Escala</h3>
                </div>
                <p class="text-slate-600 mb-6">Deseja realmente confirmar essa escala?</p>
                <div class="flex gap-3 justify-end">
                    <button @click="showConfirmModal = false"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors font-semibold flex items-center gap-2">
                        <i class="bi bi-x-circle text-[16px]"></i>
                        Cancelar
                    </button>
                    <button @click="confirmarEscala(escalaToConfirm)"
                        class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors font-semibold !opacity-100 flex items-center gap-2">
                        <i class="bi bi-check-circle text-[16px]"></i>
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end to page content-->
@endsection

@push('styles')
    <style>
        /* Estilo do Modal de Confirmação */
        button[class*="bg-green-600"] {
            background-color: #0d9488 !important;
            color: #ffffff !important;
            opacity: 1 !important;
        }

        button[class*="bg-green-600"]:hover {
            background-color: #0f766e !important;
        }

        /* Ajuste do DatePicker para Fundo Branco e Texto Escuro */
        .datePickerAgendamento {
            width: 100% !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            /* slate-200 */
            border-radius: 20px !important;
            padding: 15px !important;
            color: #1e293b !important;
            /* slate-800 */
            font-family: inherit !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .air-datepicker-nav {
            border-bottom: 1px solid #f1f5f9 !important;
            background: transparent !important;
            margin-bottom: 15px !important;
        }

        .air-datepicker-nav--title,
        .air-datepicker-nav--action {
            color: #334155 !important;
            /* slate-700 */
            font-weight: 800 !important;
        }

        .air-datepicker-nav--action:hover {
            background: #f8fafc !important;
        }

        .air-datepicker-body--day-name {
            color: #0d9488 !important;
            /* teal-600 */
            font-weight: 800 !important;
            text-transform: uppercase !important;
            font-size: 0.8rem !important;
        }

        .air-datepicker-body--cells.-days- {
            grid-template-columns: repeat(7, 1fr) !important;
            row-gap: 8px !important;
            /* Increase vertical spacing */
        }

        .air-datepicker-cell {
            color: #64748b !important;
            /* slate-500 */
            font-size: 0.9rem !important;
            /* Slightly smaller for mobile */
            height: 40px !important;
            width: 40px !important;
            margin: 2px auto !important;
            border-radius: 50% !important;
            font-weight: 600 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: none !important;
            /* Remove delay for instant feel */
        }

        .air-datepicker-cell.-current- {
            color: #0d9488 !important;
            font-weight: 900 !important;
            background: #f0fdfa !important;
            border: 1px solid #0d9488 !important;
        }

        .air-datepicker-cell.-selected-,
        .air-datepicker-cell.-selected-.-current-,
        .air-datepicker-cell.-selected-.-focus-,
        .air-datepicker-cell.-selected-.-active- {
            background: #0d9488 !important;
            /* Force teal-600 */
            color: #ffffff !important;
            font-weight: bold !important;
            border: none !important;
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.4) !important;
        }

        /* Prevent any other color from overriding selected state */
        .air-datepicker-cell.-selected-:hover,
        .air-datepicker-cell.-selected-:active,
        .air-datepicker-cell.-selected-.-focus- {
            background: #0d9488 !important;
            color: #ffffff !important;
        }

        /* Focus state for non-selected cells - REMOVED BACKGROUND */
        .air-datepicker-cell.-focus-:not(.-selected-) {
            background: transparent !important;
            color: #0d9488 !important;
        }

        /* Clear backgrounds for non-selected cells */
        .air-datepicker-cell:not(.-selected-):hover,
        .air-datepicker-cell:not(.-selected-):active,
        .air-datepicker-cell:not(.-selected-).-focus- {
            background: transparent !important;
            color: #0d9488 !important;
        }

        /* Ensure selected cell remains teal instantly */
        .air-datepicker-cell.-selected-,
        .air-datepicker-cell.-selected-:hover,
        .air-datepicker-cell.-selected-:active,
        .air-datepicker-cell.-selected-.-focus- {
            background: #0d9488 !important;
            color: #ffffff !important;
        }

        .air-datepicker-cell.-other-month- {
            color: #cbd5e1 !important;
            /* slate-300 */
        }

        /* Ponto indicador de evento - Ajustado para ser maior e verde */
        .has-event-dot {
            position: relative;
        }

        .has-event-dot::after {
            content: '' !important;
            position: absolute !important;
            bottom: 6px !important;
            /* Moved slightly up */
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 6px !important;
            height: 6px !important;
            background-color: #10b981 !important;
            /* Verde Emerald */
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
        const cdProfissional = {{ $cd_profissional ?? (auth()->guard('rpclinica')->user()->cd_profissional ?? 'null') }};
        const routeAgendamentos = @js(route('app.api.escalas'));
        const routeAgendamentosDatas = @js(route('app.api.escalas-datas'));
    </script>
    <script src="{{ asset('/js/app_rpclinica/escala.js') }}"></script>
@endpush
