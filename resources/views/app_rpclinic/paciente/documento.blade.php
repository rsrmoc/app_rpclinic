@extends('app_rpclinic.layout.layout')



@section('button_left')
    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('app_rpclinic/paciente') }}" class="text-slate-500 hover:text-teal-600 transition-colors p-1">
            <i class="bi bi-arrow-left text-2xl"></i>
        </a>
        <div class="brand-logo" style="width: auto;">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 60px; width: auto;" 
                     class="">
            </a>
        </div>
    </div>
@endsection



@section('content')
    <!--start to page content-->
    <div class="page-content p-0" x-data="appPacienteDoc">
        <div class="card rounded-0 border-0">
            <div class="card-body">
                <h5 class="mb-0 text-dark mb-3">Tela de Documentos</h5>
                <form x-on:submit.prevent="saveDoc" class="row g-3 needs-validation">
                    <div class="col-12">
                        <div class="form-floating">

                            <select class="form-control rounded-3" required x-model="dataDoc.cd_formulario">
                                <option value="">Selecione</option>
                                @foreach ($formularios as $formulario)
                                    <option value="{{ $formulario->cd_formulario }}">{{ $formulario->nm_formulario }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="floatingFirstName">Formulario</label>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div>
                            <textarea class="form-control rounded-3" style="height: 150px;" required id="editor"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-ecomm rounded-3 flex-fill text-white"
                        style="height: 60px; font-weight: 600; padding: 1.2rem 1.5rem; background-color: #0d9488; border-color: #0d9488;"
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
    </div>
    <!--end to page content-->
@endsection


@push('scripts')
    <script>
      const cdPaciente = {{ $paciente->cd_paciente }};
      const formularios = @js($formularios);
      const routePacienteAddDoc = @js(url('app_rpclinic/api/paciente-add-doc'));
    </script>
    <script src="{{ asset('js/app_rpclinica/paciente-add-doc.js') }}"></script>
@endpush
