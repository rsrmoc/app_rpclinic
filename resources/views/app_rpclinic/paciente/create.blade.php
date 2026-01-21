@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex flex-column align-items-center justify-content-center pt-1">
        <div class="brand-logo mb-0">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 40px; width: auto;" 
                     class="">
            </a>
        </div>
        <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none">Novo Paciente</h6>
    </div>
@endsection

@section('content')


       <!--start to page content-->
       <div class="px-2 pt-0 pb-20 min-h-screen" x-data="appPacienteAdd" style="overflow-x: hidden;">
             <form x-on:submit.prevent="createPaciente" class="row g-3 needs-validation">
                <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control rounded-3 border-0 shadow-sm" id="nome" placeholder="Nome" required
                        x-model="pacienteData.nm_paciente" />
                      <label for="nome">Nome</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                      <input type="date" class="form-control rounded-3 border-0 shadow-sm" id="nasc" placeholder="nasc"
                        x-model="pacienteData.dt_nasc" />
                      <label for="nasc">Data Nascimento</label>
                    </div>
                </div>
               <div class="col-12">
                 <div class="form-floating">
                   <select class="form-select rounded-3 border-0 shadow-sm" placeholder="Sexo"
                    x-model="pacienteData.sexo">
                     <option value=""></option>
                     <option value="H" >Masculino</option>
                     <option value="M" >Feminino</option>
                   </select>
                   <label>Sexo</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input type="text" class="form-control rounded-3 border-0 shadow-sm" id="mae" placeholder="Nome da Mãe"
                    x-model="pacienteData.nm_mae">
                   <label for="mae">Nome da Mãe</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input type="text" class="form-control rounded-3 border-0 shadow-sm" id="pai" placeholder="Nome do Pai"
                    x-model="pacienteData.nm_pai">
                   <label for="pai">Nome do Pai</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input class="form-control rounded-3 border-0 shadow-sm" id="cpf" placeholder="CPF"
                    x-model="pacienteData.cpf"
                    x-mask="999.999.999-99" />
                   <label for="cpf">CPF</label>
                 </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control rounded-3 border-0 shadow-sm" id="rg" placeholder="RG"
                        x-model="pacienteData.rg">
                      <label for="rg">RG</label>
                    </div>
                   </div>

                   <button type="submit" class="btn btn-primary w-100 rounded-3 text-white shadow-sm mt-4"
                    style="height: 55px; font-weight: 600; font-size: 16px; background-color: #0d9488; border-color: #0d9488;"
                    x-bind:disabled="loading">
                    <template x-if="loading">
                      <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                    </template>
                    <span>Salvar</span>
                  </button>
             </form><!--end form-->
    </div>
  <!--end to page content-->
  <!--end to page content-->


@endsection


@push('scripts')
    <script>
        const routePacienteAdd = @js(url('app_rpclinic/api/paciente-add'));
    </script>
    <script src="{{ asset('/js/app_rpclinica/paciente-add.js') }}"></script>
@endpush
