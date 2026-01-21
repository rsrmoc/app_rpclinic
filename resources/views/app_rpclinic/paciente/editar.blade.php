@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex align-items-center">
        <a href="{{ url('app_rpclinic/paciente') }}" class="text-slate-500 hover:text-teal-600 transition-colors p-1 me-3">
            <i class="bi bi-arrow-left text-2xl"></i>
        </a>
        <div class="d-flex flex-column align-items-center justify-content-center pt-1">
            <div class="brand-logo mb-0">
                <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                         alt="Logo" 
                         style="height: 45px; width: auto;" 
                         class="">
                </a>
            </div>
            <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none text-center">{{ Str::limit($paciente->nm_paciente, 25) }}</h6>
        </div>
    </div>
@endsection

@section('content')


       <!--start to page content-->
       <div class="px-3 pt-0 pb-20 min-h-screen" x-data="appPacienteEdit">
             <form x-on:submit.prevent="savePaciente" class="row g-3 needs-validation" id="formPaciente">
                <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control rounded-3 border-0 shadow-sm" id="nome" placeholder="Nome"
                        value="{{ $paciente->nm_paciente }}" name="nm_paciente" required />
                      <label for="nome">Nome</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                      <input type="date" class="form-control rounded-3 border-0 shadow-sm" id="nasc" placeholder="nasc"
                        value="{{ $paciente->dt_nasc }}" name="dt_nasc" />
                      <label for="nasc">Data Nascimento</label>
                    </div>
                </div>
               <div class="col-12">
                 <div class="form-floating">
                   <select class="form-control rounded-3 border-0 shadow-sm" placeholder="Sexo" name="sexo">
                     <option value=""></option>
                     <option value="H" @if($paciente->sexo == 'H') selected @endif>Masculino</option>
                     <option value="M" @if($paciente->sexo == 'M') selected @endif>Feminino</option>
                   </select>
                   <label>Sexo</label>
                 </div>
               </div>

               <div class="col-12">
                 <div class="form-floating">
                   <input type="text" class="form-control rounded-3 border-0 shadow-sm" id="mae" placeholder="Nome da Mãe"
                    value="{{ $paciente->nm_mae }}" name="nm_mae" />
                   <label for="mae">Nome da Mãe</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input type="text" class="form-control rounded-3 border-0 shadow-sm" id="pai" placeholder="Nome do Pai"
                    value="{{ $paciente->nm_pai }}" name="nm_pai" />
                   <label for="pai">Nome do Pai</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input class="form-control rounded-3 border-0 shadow-sm" id="cpf" placeholder="CPF"
                    value="{{ $paciente->cpf }}" name="cpf"
                    x-mask="999.999.999-99" />
                   <label for="cpf">CPF</label>
                 </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                      <input type="text" class="form-control rounded-3 border-0 shadow-sm" id="rg" placeholder="RG"
                        value="{{ $paciente->rg }}" name="rg" />
                      <label for="rg">RG</label>
                    </div>
                   </div>

                   <button type="submit"
                    class="btn btn-primary w-100 rounded-3 text-white shadow-sm mt-4"
                    style="height: 55px; font-weight: 600; font-size: 16px; background-color: #0d9488; border-color: #0d9488;">
                      <span>Salvar</span>
                    </button>
             </form><!--end form-->
    </div>
  <!--end to page content-->


@endsection

@push('scripts')
    <script>
      const cdPaciente = '{{ $paciente->cd_paciente }}';
      const routePacienteEditBase = @js(url('app_rpclinic/api/paciente-edit'));
    </script>
    <script src="{{ asset('js/app_rpclinica/paciente-edit.js') }}"></script>
@endpush
