@extends('app_rpclinic.layout.layout')



@section('button_left')
    <div class="nav-button" onclick="history.back()"><a href="javascript:;"><i class="bi bi-arrow-left"></i></a></div>
    <div class="account-my-addresses">
        <h6 class="mb-0 fw-bold text-dark">Documento do Paciente</h6>
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

                    <button type="submit" class="btn btn-ecomm rounded-3 btn-dark flex-fill"
                        style="  height: 60px; font-weight: 600; padding: 1.2rem 1.5rem;"
                        x-bind:disabled="loading">
                        <template x-if="loading">
                          <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        </template>
                        <span>Salvar</span>
                    </button>
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
    </script>
    <script src="{{ asset('js/app_rpclinica/paciente-add-doc.js') }}"></script>
@endpush
