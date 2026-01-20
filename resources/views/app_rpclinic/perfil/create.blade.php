@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex align-items-center gap-3">
        <div class="brand-logo" style="width: auto;">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('app/assets/images/logo_horizontal.svg') }}" 
                     alt="Logo" 
                     style="height: 60px; width: auto;" 
                     class="">
            </a>
        </div>
        <div class="border-start border-slate-300 h-6 mx-1"></div>
        <h6 class="mb-0 text-slate-700 font-bold uppercase tracking-tight">Perfil</h6>
    </div>
@endsection




@section('content')


       <!--start to page content-->
       <div class="page-content p-0" x-data="appPerfil">
        <div class="card rounded-0 border-0">
          <div class="card-body">
             <form x-on:submit.prevent="saveProfile" class="row g-3 needs-validation"
              id="formProfile">
               @csrf
               <div class="col-12">
                 <div class="form-floating">
                    <input type="text" class="form-control rounded-3" id="floatingMobileNo" placeholder="Mobile No"
                      value="{{ auth()->guard('rpclinica')->user()->nm_header_doc }}" name="nm_header_doc">
                   <label for="floatingLastName">Nome do Profissional</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                    <input type="text" class="form-control rounded-3" id="floatingMobileNo" placeholder="Mobile No"
                      value="{{ auth()->guard('rpclinica')->user()->espec_header_doc }}" name="espec_header_doc">
                   <label for="floatingEmail">Especialidade(s) do Profissional</label>
                 </div>
               </div>
               <div class="col-12">
                 <div class="form-floating">
                   <input type="text" class="form-control rounded-3" id="floatingMobileNo" placeholder="Mobile No"
                    value="{{ auth()->guard('rpclinica')->user()->conselho_header_doc }}" name="conselho_header_doc">
                   <label for="floatingMobileNo">Conselho do Profissional No</label>
                 </div>
               </div>
               <div class="col-12">
                <div class="form-floating">
                  <input type="text" class="form-control rounded-3" id="floatingMobileNo" placeholder="Mobile No"
                   value="{{ auth()->guard('rpclinica')->user()->email_contato }}" name="email">
                  <label for="floatingMobileNo">Email</label>
                </div>
              </div>
              <div class="col-12">
                <div class="form-floating">
                  <input type="text" class="form-control rounded-3" id="floatingMobileNo" placeholder="Mobile No"
                   value="{{ auth()->guard('rpclinica')->user()->nm_celular }}" name="celular">
                  <label for="floatingMobileNo">Celular</label>
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
        const routePerfilUpdate = @js(url('app_rpclinic/api/perfil-update'));
    </script>
    <script src="{{ asset('js/app_rpclinica/perfil.js') }}"></script>
@endpush