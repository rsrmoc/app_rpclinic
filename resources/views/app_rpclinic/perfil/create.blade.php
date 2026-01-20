@extends('app_rpclinic.layout.layout')

@section('button_left')

<div class="nav-button" onclick="history.back()"><a href="javascript:;"><i class="bi bi-arrow-left"></i></a></div>
<div class="account-my-addresses">
  <h6 class="mb-0 fw-bold text-dark">Cadastro do perfil</h6>
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
                class="btn btn-ecomm rounded-3 btn-dark flex-fill"
                style="height: 60px; font-weight: 600; padding: 1.2rem 1.5rem;"
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
    <script src="{{ asset('js/app_rpclinica/perfil.js') }}"></script>
@endpush