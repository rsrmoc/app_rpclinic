@extends('app_rpclinic.layout.layout')

@section('button_left')

<div class="nav-button" onclick="history.back()"><a href="javascript:;"><i class="bi bi-arrow-left"></i></a></div>
<div class="account-my-addresses">
  <h6 class="mb-0 fw-bold text-dark">Cadastrar  Paciente</h6>
</div>

@endsection




@section('content')


       <!--start to page content-->
       <div class="page-content p-0" x-data="appPacienteAdd">
        <div class="card rounded-0 border-0">
          <div class="card-body">
             <form x-on:submit.prevent="createPaciente" class="row g-3 needs-validation">
                <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control rounded-3" id="nome" placeholder="Nome" required
                        x-model="pacienteData.nm_paciente" />
                      <label for="floatingEmail">Nome</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                      <input type="date" class="form-control rounded-3" id="nasc" placeholder="nasc"
                        x-model="pacienteData.dt_nasc" />
                      <label for="floatingEmail">Data Nascimento</label>
                    </div>
                </div>
               <div class="col-12">
                 <div class="form-floating">
                   <select class="form-control rounded-3" placeholder="CPF"
                    x-model="pacienteData.sexo">
                     <option value=""></option>
                     <option value="H" >Masculino</option>
                     <option value="M" >Feminino</option>
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
                    x-model="pacienteData.nm_mae">
                   <label for="floatingEmail">Nome da Mãe</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input type="text" class="form-control rounded-3" id="floatingMobileNo" placeholder="Mobile No"
                    x-model="pacienteData.nm_pai">
                   <label for="floatingMobileNo">Nome do Pai</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input class="form-control rounded-3" id="cpf" placeholder="CPF"
                    x-model="pacienteData.cpf"
                    x-mask="999.999.999-99" />
                   <label for="floatingStreetAddress">CPF</label>
                 </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control rounded-3"
                        x-model="pacienteData.rg">
                      <label for="floatingStreetAddress">RG</label>
                    </div>
                   </div>

                   <button type="submit" class="btn btn-ecomm rounded-3 btn-dark flex-fill"
                    style="height: 60px; font-weight: 600; padding: 1.2rem 1.5rem;"
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
    <script src="{{ asset('/js/app_rpclinica/paciente-add.js') }}"></script>
@endpush