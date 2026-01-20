<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1"> 
    <title>App - RPclinic</title>

    <!--CSS Files-->
    <link href="{{ asset('app/assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link href="{{ asset('app/assets/css/style.css') }}" rel="stylesheet"/>

    	<!-- add isso no header.html -->
      <link rel="manifest" href=" {{ asset('manifest.json') }}"> 

      <script>
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
        navigator.serviceWorker.register('./service-worker.js');
        });
      }
      </script>
      <!-- add isso no header.html -->
  </head>
  <body>

    <!--page loader-->
    <div class="loader-wrapper">
      <div class="d-flex justify-content-center align-items-center position-absolute top-50 start-50 translate-middle">
        <div class="spinner-border text-white" role="status">
          <span class="visually-hidden">Carregando...</span>
        </div>
      </div>
    </div>
   <!--end loader-->

   <!--start wrapper-->
    <div class="wrapper" style="background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
       <!--start to page content-->
       <div class="page-content bg-transparent w-100">
          <div class="login-body p-4" style="max-width: 450px; margin: 0 auto;">
             <form action="{{ route('app.login.action') }}" method="POST" class="mt-4 bg-white/5 backdrop-blur-md p-5 rounded-3xl border border-white/10 shadow-2xl">
               @csrf

               <div style="text-align: center" class="mb-5">
                   <img src="{{ asset('app/assets/images/logo_completa.svg') }}" class="img-fluid" style="width: 80%; filter: drop-shadow(0 0 10px rgba(45,212,191,0.2));" alt="RP Clinic">
               </div>

               @error('error')
                 <div class="alert alert-danger bg-red-500/20 border-red-500/30 text-red-200 rounded-xl mb-4" role="alert">
                   <i class="bi bi-exclamation-triangle-fill me-2"></i>
                   {{ $message }}
                 </div>
               @enderror

               {{-- <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="floatingInputName" placeholder="nameBusiness" value="{{ old('businessName') }}"
                  name="businessName" required>
                <label for="floatingInputName">Nome da empresa</label>
              </div> --}}

              {{--
              <div class="form-floating mb-3">
                <select class="form-select" id="floatingSelect" aria-label="Floating label select example"    
                  name="business">
                  <option value="" selected>Selecione</option>
                  @foreach ($business as $item)  
                    <option value="{{ $item->cd_empresa_app }}" @if(old('business') == $item->cd_empresa_app) selected @endif>{{ $item->nm_empresa_app }}</option>
                  @endforeach
                </select>
                <label for="floatingSelect">Empresa</label>
              </div>
              --}}
               <div class="form-floating mb-3">
                 <input type="email" class="form-control rounded-2xl bg-white/5 border-white/10 text-white" id="floatingInputEmail" placeholder="name@example.com" value="{{ old('email') }}"
                   name="email" required style="height: 65px;">
                 <label for="floatingInputEmail" class="text-slate-400">Email de Acesso</label>
               </div>

               <div class="input-group mb-4" id="show_hide_password">
                 <div class="form-floating flex-grow-1">
                   <input type="password" class="form-control rounded-start-2xl bg-white/5 border-white/10 text-white border-end-0" id="floatingInputPassword" placeholder="Senha" 
                     name="password" required minlength="2" style="height: 65px;">
                   <label for="floatingInputPassword" class="text-slate-400">Senha</label>
                 </div>
                 <span class="input-group-text bg-white/5 border-white/10 border-start-0 rounded-end-2xl text-slate-400"><i class="bi bi-eye-slash"></i></span>
               </div>

               <div class="d-flex align-items-center justify-content-between mb-5">
                 <div class="form-check">
                   <input type="checkbox" class="form-check-input bg-white/10 border-white/20" id="flexCheckDefault"
                     name="remember" value="1">
                   <label class="form-check-label text-slate-400 text-sm" for="flexCheckDefault">Lembrar de mim</label>
                 </div>
                 <div class=""><a href="#" class="forgot-link text-teal-400 text-sm font-bold no-underline">Esqueceu?</a></div>
               </div>

               <div class="mb-0 d-grid">
                 <button type="submit" class="btn btn2 py-3 rounded-2xl font-bold shadow-lg shadow-teal-500/20">ENTRAR NO SISTEMA</button>
               </div>
             </form>
          </div>
       </div>
     <!--end to page content-->

       <style>

            .btn2 {
                display: block;
                width: 100%;
                height: 50px;
                border-radius: 25px;
                outline: none;
                border: none;
                background-image: linear-gradient(to right, #135ea2, #69bb64, #135ea2);
                background-size: 200%;
                color: #fff;
                font-family: 'Poppins', sans-serif;
                text-transform: uppercase;
                --bs-btn-padding-y: 0.575rem;
                --bs-btn-font-size: 1.3rem;
                cursor: pointer;
                transition: .5s;
            }
        </style>




    </div>
   <!--end wrapper-->


    <!--JS Files-->
    <script src="{{ asset('app/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('app/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('app/assets/js/show-hide-password.js') }}"></script>
    <script src="{{ asset('app/assets/js/loader.js') }}"></script>



  </body>
</html>
