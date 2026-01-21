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
        <h6 class="mb-0 text-slate-700 font-bold uppercase tracking-tight" style="font-size: 11px; margin-top: -5px;">Perfil</h6>
    </div>
@endsection




@section('content')


       <!--start to page content-->
       <div class="page-content p-0" x-data="appPerfil" style="margin-top: -30px !important;">
        <div class="border-0 bg-transparent">
          <div class="">
             <form x-on:submit.prevent="saveProfile" class="row g-3 needs-validation"
              id="formProfile">
               @csrf
                <div class="col-12">
                   <div class="mb-3">
                     <label class="form-label text-slate-700 font-bold text-sm">Nome do Profissional</label>
                     <input type="text" class="form-control rounded-3 p-3" placeholder="Nome do Profissional"
                       value="{{ auth()->guard('rpclinica')->user()->nm_header_doc }}" name="nm_header_doc">
                   </div>
               </div>
               <div class="col-12">
                   <div class="mb-3">
                     <label class="form-label text-slate-700 font-bold text-sm">Especialidade(s) do Profissional</label>
                     <input type="text" class="form-control rounded-3 p-3" placeholder="Especialidade(s) do Profissional"
                       value="{{ auth()->guard('rpclinica')->user()->espec_header_doc }}" name="espec_header_doc">
                   </div>
               </div>
               <div class="col-12">
                   <div class="mb-3">
                     <label class="form-label text-slate-700 font-bold text-sm">Conselho do Profissional No</label>
                     <input type="text" class="form-control rounded-3 p-3" placeholder="Conselho do Profissional No"
                       value="{{ auth()->guard('rpclinica')->user()->conselho_header_doc }}" name="conselho_header_doc">
                   </div>
               </div>
               <div class="col-12">
                 <div class="mb-3">
                   <label class="form-label text-slate-700 font-bold text-sm">Email</label>
                   <input type="text" class="form-control rounded-3 p-3" placeholder="Email"
                    value="{{ auth()->guard('rpclinica')->user()->email_contato }}" name="email">
                 </div>
               </div>
               <div class="col-12">
                 <div class="mb-3">
                   <label class="form-label text-slate-700 font-bold text-sm">Celular</label>
                   <input type="text" class="form-control rounded-3 p-3" placeholder="Celular"
                    value="{{ auth()->guard('rpclinica')->user()->nm_celular }}" name="celular">
                 </div>
               </div>
               {{-- <a href="addresses.html#AddNewAddress" data-bs-toggle="offcanvas" class="btn btn-ecomm rounded-3 btn-dark flex-fill" style="  height: 60px; font-weight: 600;
               padding: 1.2rem 1.5rem;">Salvar</a> --}}
               <button type="submit"
                class="btn btn-ecomm rounded-3 flex-fill text-white"
                style="height: 60px; font-weight: 600; padding: 1.2rem 1.5rem; background-color: #0d9488; border-color: #0d9488;"
                x-bind:disabled="loading">
                  <template x-if="loading">
                    <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
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
        const routePerfilUpdate = @js(route('app.api.perfil-update'));
    </script>
    <script src="{{ asset('js/app_rpclinica/perfil.js') }}"></script>
@endpush
