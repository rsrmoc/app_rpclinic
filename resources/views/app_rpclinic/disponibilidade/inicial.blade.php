@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex flex-column align-items-center pt-0 m-0">
        <div class="brand-logo">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" alt="Logo" style="height: 45px; width: auto;">
            </a>
        </div>
        <h6 class="mb-0 text-slate-700 font-bold uppercase tracking-tight" style="font-size: 11px; margin-top: -5px;">
            Disponibilidade</h6>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content pt-0 px-2" x-data="appAgendamento"
        style="padding-top: 0px !important; margin-top: -30px !important;">
        <form x-on:submit.prevent="saveDisponibilidade" class="row g-3 needs-validation" id="formDisponibilidade">
            @csrf
            <div class="pt-2">
                <div id="dataAgendamento"></div>
            </div>

            <div class="py-2"></div>

            <!-- Loading -->
            <div x-show="loading" class="mb-3 text-center p-4 ">
                <div class="spinner-border text-teal-600" role="status"></div>
                <div class="text-teal-600 font-bold mt-2">Salvando Disponibilidade...</div>
            </div>
  
            <div class="px-2">
                <button type="submit" class="btn btn-teal w-100 rounded-3 text-white shadow-sm mt-2 d-flex align-items-center justify-content-center gap-2"
                    style="height: 55px; font-weight: 600; font-size: 16px;" x-bind:disabled="loading">
                    <template x-if="loading">
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    </template>
                    <template x-if="!loading">
                        <i class="bi bi-check-circle"></i>
                    </template>
                    <span>Salvar</span>
                </button>
            </div>

            <!-- Ícone para limpar datas -->
            <div class="flex justify-end mt-2">
                <button type="button" @click="showClearModal = true"
                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                    title="Limpar datas selecionadas">
                    <i class="bi bi-trash text-base"></i>
                </button>
            </div>

        </form>

        <!-- Modal de Confirmação para Limpar -->
        <div x-show="showClearModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="bi bi-trash text-red-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Limpar Datas</h3>
                </div>
                <p class="text-slate-600 mb-6">Tem certeza que deseja limpar todas as datas selecionadas deste mês?</p>
                <div class="flex gap-3 justify-end">
                    <button @click="showClearModal = false"
                        class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors font-semibold flex items-center gap-2">
                        <i class="bi bi-x-circle text-[16px]"></i>
                        Cancelar
                    </button>
                    <button @click="clearDates()"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors font-semibold flex items-center gap-2">
                        <i class="bi bi-trash text-[16px]"></i>
                        Limpar
                    </button>
                </div>
            </div>
        </div>

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
            margin-bottom: 5px !important;
            /* Reduced margin */
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
            grid-template-columns: repeat(7, 1fr);
            grid-gap: 5px !important;
            row-gap: 8px !important;
            padding: 5px;
        }

        .air-datepicker-cell {
            color: #64748b !important;
            font-size: 0.9rem !important;
            height: 40px !important;
            width: 40px !important;
            margin: 2px auto !important;
            border-radius: 50% !important;
            font-weight: 600 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: relative;
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
            border-radius: 50% !important;
        }

        /* Prevent any other color from overriding selected state */
        .air-datepicker-cell.-selected-:hover,
        .air-datepicker-cell.-selected-:active,
        .air-datepicker-cell.-selected-.-focus- {
            background: #0d9488 !important;
            color: #ffffff !important;
        }

        /* Clear backgrounds for non-selected cells */
        .air-datepicker-cell:not(.-selected-):hover,
        .air-datepicker-cell:not(.-selected-):active,
        .air-datepicker-cell:not(.-selected-).-focus- {
            background: transparent !important;
            color: #0d9488 !important;
        }


        .air-datepicker-cell.-other-month- {
            color: #cbd5e1 !important;
        }

        /* Ponto indicador de evento */
        .has-event-dot::after {
            content: '' !important;
            position: absolute !important;
            bottom: 6px !important;
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
        const routeDisponibilidadeSave = @js(route('app.api.disponibilidade-save'));
        const routeDisponibilidadeGet = @js(route('app.api.disponibilidade-get'));
        const routeDisponibilidadeDelete = @js(route('app.api.disponibilidade-delete'));
    </script>
    <script src="{{ asset('/js/app_rpclinica/disponibilidade.js') }}"></script>
@endpush
