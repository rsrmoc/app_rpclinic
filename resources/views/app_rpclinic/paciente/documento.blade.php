@extends('app_rpclinic.layout.layout')


@section('button_back')
    <a href="{{ url('app_rpclinic/paciente') }}" class="text-slate-500 hover:text-teal-600 transition-colors p-2 ms-2">
        <i class="bi bi-arrow-left text-2xl"></i>
    </a>
@endsection

@section('button_left')
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 m-0">
        <div class="brand-logo mb-0">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 40px; width: auto;">
            </a>
        </div>
        <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none">Documentos</h6>
    </div>
@endsection



@section('content')
    <!--start to page content-->
    <div class="page-content px-2 pt-0" x-data="appPacienteDoc" style="padding-top: 0px !important; overflow-x: hidden; margin-top: -30px !important;">
        <div class="card border-0 shadow-none bg-transparent mb-0">
            <div class="card-body p-2">
                <h5 class="mb-0 text-dark mb-3">Tela de Documentos</h5>
                <form x-on:submit.prevent="saveDoc" class="row g-3 needs-validation">
                    <div class="col-12">
                        <div class="form-floating">
                            <input
                                type="text"
                                class="form-control rounded-3 border-0 shadow-sm modelo-documento-picker"
                                placeholder=" "
                                readonly
                                x-bind:value="selectedModeloDocumentoLabel"
                                x-on:click="openModeloDocumentoPicker()"
                            >
                            <label>Escolher Modelo de Documento</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div>
                            <textarea class="form-control rounded-3 border-0 shadow-sm" style="height: 150px;" required id="editor"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3 text-white shadow-sm mt-2"
                        style="height: 55px; font-weight: 600; font-size: 16px; background-color: #0d9488; border-color: #0d9488;"
                        x-bind:disabled="loading">
                        <template x-if="loading">
                          <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        </template>
                        <span>Salvar Documento</span>
                    </button>
                    
                    <div class="mt-3 text-center">
                         <a href="{{ url('app_rpclinic/paciente-hist/'.$paciente->cd_paciente) }}" class="text-teal-600 font-bold text-sm hover:underline">
                            <i class="bi bi-clock-history me-1"></i> Ver documentos anteriores
                         </a>
                    </div>
                </form><!--end form-->
            </div>
        </div>
        <div class="modal fade" id="modalModeloDocumento" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-sm-down modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Escolher Modelo de Documento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body p-2">
                        <div class="mb-2">
                            <input
                                type="search"
                                class="form-control rounded-3 border-0 shadow-sm"
                                placeholder="Buscar..."
                                x-model="modeloDocumentoQuery"
                                x-ref="modeloDocumentoSearch"
                            >
                        </div>

                        <div class="list-group">
                            <template x-for="formulario in modeloDocumentoFiltrado" :key="formulario.cd_formulario">
                                <button
                                    type="button"
                                    class="list-group-item list-group-item-action py-3 text-wrap"
                                    x-on:click="selectModeloDocumento(formulario.cd_formulario)"
                                >
                                    <div class="fw-semibold" x-text="formulario.nm_formulario"></div>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end to page content-->
@endsection


@push('scripts')
    <!-- Dados para o JS -->
    <script id="data-formularios" type="application/json">
        @json($formularios)
    </script>
    <input type="hidden" id="data-cd-paciente" value="{{ $paciente->cd_paciente }}">
    <input type="hidden" id="data-route-add-doc" value="{{ url('app_rpclinic/api/paciente-add-doc') }}">

    <script src="{{ asset('js/app_rpclinica/paciente-add-doc.js') }}"></script>
@endpush



@push('styles')
  <style>
    .modelo-documento-picker {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 1rem center;
      background-size: 12px;
      padding-right: 2.5rem;
      cursor: pointer;
    }
  </style>

  </style>
@endpush
