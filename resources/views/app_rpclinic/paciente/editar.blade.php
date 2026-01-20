@extends('app_rpclinic.layout.layout')

@section('button_left')

<div class="nav-button" onclick="history.back()"><a href="javascript:;"><i class="bi bi-arrow-left"></i></a></div>
<div class="account-my-addresses">
  <h6 class="mb-0 fw-bold text-dark">Editar  Paciente [ {{ $paciente->cd_paciente }} ]</h6>
</div>

@endsection




@section('content')


       <!--start to page content-->
       <div class="page-content p-0" x-data="appPacienteEdit">
        <div class="card rounded-0 border-0">
          <div class="card-body">
             <form x-on:submit.prevent="savePaciente" class="row g-3 needs-validation" id="formPaciente">
                <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control rounded-3" id="nome" placeholder="Nome"
                        value="{{ $paciente->nm_paciente }}" name="nm_paciente" required />
                      <label for="floatingEmail">Nome</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                      <input type="date" class="form-control rounded-3" id="nasc" placeholder="nasc"
                        value="{{ $paciente->dt_nasc }}" name="dt_nasc" />
                      <label for="floatingEmail">Data Nascimento</label>
                    </div>
                </div>
               <div class="col-12">
                 <div class="form-floating">
                   <select class="form-control rounded-3" placeholder="CPF" name="sexo">
                     <option value=""></option>
                     <option value="H" @if($paciente->sexo == 'H') selected @endif>Masculino</option>
                     <option value="M" @if($paciente->sexo == 'M') selected @endif>Feminino</option>
                   </select>
                   <label for="floatingFirstName">Sexo</label>
                   <div class="invalid-feedback">
                     Please provide a valid city.
                   </div>
                 </div>
               </div>

               <div class="col-12">
                 <div class="form-floating">
                   <input type="text" class="form-control rounded-3" id="floatingEmail" placeholder="Email"
                    value="{{ $paciente->nm_mae }}" name="nm_mae" />
                   <label for="floatingEmail">Nome da Mãe</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input type="text" class="form-control rounded-3" id="floatingMobileNo" placeholder="Mobile No"
                    value="{{ $paciente->nm_pai }}" name="nm_pai" />
                   <label for="floatingMobileNo">Nome do Pai</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input class="form-control rounded-3" id="cpf" placeholder="CPF"
                    value="{{ $paciente->cpf }}" name="cpf"
                    x-mask="999.999.999-99" />
                   <label for="floatingStreetAddress">CPF</label>
                 </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control rounded-3" id="cpf" placeholder="CPF"
                        value="{{ $paciente->rg }}" name="rg" />
                      <label for="floatingStreetAddress">RG</label>
                    </div>
                   </div>

                   <button type="submit"
                    class="btn btn-ecomm rounded-3 btn-dark flex-fill"
                    style="  height: 60px; font-weight: 600; padding: 1.2rem 1.5rem;">
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
      const cdPaciente = '{{ $paciente->cd_paciente }}';
    </script>
    <script src="{{ asset('js/app_rpclinica/paciente-edit.js') }}"></script>
@endpush