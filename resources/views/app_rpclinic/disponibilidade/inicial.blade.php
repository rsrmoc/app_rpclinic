@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex flex-column align-items-center pt-0 m-0">
        <div class="brand-logo">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 45px; width: auto;">
            </a>
        </div>
        <h6 class="mb-0 text-slate-700 font-bold uppercase tracking-tight" style="font-size: 11px; margin-top: -5px;">Disponibilidade</h6>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content pt-0 px-2" x-data="appAgendamento" style="padding-top: 0px !important; margin-top: -30px !important;">
        <div class="pt-2">
            <form x-on:submit.prevent="saveDisponibilidade" class="row g-3 needs-validation"
             id="formDisponibilidade">
              @csrf

              <!-- Toggle para Seleção Múltipla -->
              <div class="mb-3">
                  <div class="form-check form-switch" style="display: flex; align-items: center; justify-content: space-between; padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                      <label class="form-check-label" for="multipleSelectSwitch" style="font-weight: 600; color: #334155;">
                          <i class="fa fa-calendar-check" style="color: #0d9488; margin-right: 8px;"></i>
                          Seleção Múltipla de Datas
                      </label>
                      <input class="form-check-input" type="checkbox" role="switch" id="multipleSelectSwitch" 
                             x-model="multipleSelect" 
                             x-on:change="toggleMultipleSelect()"
                             checked
                             style="width: 50px; height: 26px; cursor: pointer;">
                  </div>
              </div>

              <div id="dataAgendamento"></div>

              <!-- Lista de Datas Selecionadas -->
              <div x-show="selectedDates && selectedDates.length > 0" class="mb-3" style="background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 12px; padding: 15px;">
                  <h6 style="font-weight: 700; color: #0f766e; margin-bottom: 10px; font-size: 14px;">
                      <i class="fa fa-calendar-check" style="margin-right: 5px;"></i>
                      Datas Selecionadas (<span x-text="selectedDates.length"></span>)
                  </h6>
                  <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                      <template x-for="(date, index) in selectedDates" :key="index">
                          <div style="background: #0d9488; color: white; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 5px;">
                              <span x-text="formatDateDisplay(date)"></span>
                              <i class="fa fa-times" 
                                 x-on:click="removeDate(index)" 
                                 style="cursor: pointer; opacity: 0.8; font-size: 10px;"
                                 title="Remover"></i>
                          </div>
                      </template>
                  </div>
              </div>

              </div>

              <!-- Agendamentos da Data (se houver apenas 1 data selecionada) -->
              <div x-show="selectedDates && selectedDates.length === 1 && agendamentos && agendamentos.length > 0" class="mb-3">
                  <h6 style="font-weight: 700; color: #334155; margin-bottom: 12px; font-size: 14px;">
                      <i class="fa fa-calendar-alt" style="color: #0d9488; margin-right: 5px;"></i>
                      Agendamentos (<span x-text="agendamentos.length"></span>)
                  </h6>
                  <template x-for="(item, index) in agendamentos" :key="index">
                      <div style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; margin-bottom: 8px;">
                          <div style="display: flex; justify-content: space-between; align-items: center;">
                              <div style="flex: 1;">
                                  <div style="font-weight: 700; color: #334155; font-size: 14px;" x-text="item.paciente?.nm_paciente || 'Sem paciente'"></div>
                                  <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                                      <i class="fa fa-clock" style="margin-right: 4px;"></i>
                                      <span x-text="item.hr_agenda"></span>
                                  </div>
                              </div>
                              <div>
                                  <span 
                                      x-bind:class="'badge ' + classLabelSituacao[item.situacao?.nm_situacao?.toLowerCase() || 'livre']"
                                      x-text="capitalizeFirstLetter(item.situacao?.nm_situacao || 'Livre')"
                                      style="font-size: 11px; padding: 4px 10px;"></span>
                              </div>
                          </div>
                      </div>
                  </template>
              </div>

              <div class="py-2"></div>
      
              <template x-if="loading">
                  <div class="mb-3">
                      <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>&ensp;
                      <span>Carregando...</span>
                  </div>
              </template>
      
              <button type="submit"
              class="btn btn-teal w-100 rounded-3 text-white shadow-sm mt-2"
              style="height: 55px; font-weight: 600; font-size: 16px;"
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
            margin-bottom: 5px !important; /* Reduced margin */
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
            transition: all 0.2s ease !important;
        }

        .air-datepicker-cell.-current- {
            color: #0d9488 !important;
            font-weight: 900 !important;
            background: #f0fdfa !important; 
            border: 1px solid #0d9488 !important;
        }

        .air-datepicker-cell.-selected-, 
        .air-datepicker-cell.-selected-.-current- {
            background: #0d9488 !important; 
            color: #ffffff !important;
            font-weight: bold !important;
            border: none !important;
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.4) !important;
            border-radius: 50% !important;
        }

        /* Hover e Focus state for non-selected cells */
        .air-datepicker-cell:hover,
        .air-datepicker-cell.-focus- {
            background: transparent !important;
            color: inherit !important;
        }

        .air-datepicker-cell.-selected-:hover,
        .air-datepicker-cell.-selected-.-focus- {
            background: #0d9488 !important;
            color: #ffffff !important;
        }
        
        .air-datepicker-cell.-other-month- {
            color: #cbd5e1 !important; 
        }

        /* Ponto indicador de evento */
        .has-event-dot::after {
            content: '' !important;
            position: absolute !important;
            bottom: 4px !important;
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
        const routeAgendamentos = @js(url('app_rpclinic/api/agendamentos'));
        const routeAgendamentosDatas = @js(url('app_rpclinic/api/agendamentos-datas'));
        const routeDisponibilidadeSave = @js(route('app.api.disponibilidade-save'));
    </script>
    <script src="{{ asset('/js/app_rpclinica/disponibilidade.js') }}"></script>
@endpush
