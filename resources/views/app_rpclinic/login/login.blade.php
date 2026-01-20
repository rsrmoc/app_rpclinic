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
    <div class="wrapper">



      <!--start to page content-->
       <div class="page-content">

         <div class="login-body">


            <form action="{{ route('app.login.action') }}" method="POST" class="mt-4">
              @csrf

              <div style="text-align: center">
                  <img src="{{ asset('app/assets/images/logo_completa.svg') }}" class="img-fluid" style="width: 80%;" alt="">
              </div>

              @error('error')
                <div class="alert alert-danger" role="alert">
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
                <input type="email" class="form-control rounded-3" id="floatingInputEmail" placeholder="name@example.com" value="{{ old('email') }}"
                  name="email" required>
                <label for="floatingInputEmail">Email</label>
              </div>

              <div class="input-group mb-3" id="show_hide_password">
                <div class="form-floating flex-grow-1">
                  <input type="password" class="form-control rounded-3 rounded-end-0 border-end-0" id="floatingInputPassword" placeholder="Enter Password" value=""
                    name="password" required minlength="2">
                  <label for="floatingInputPassword">Password</label>
                </div>
                <span class="input-group-text bg-transparent rounded-start-0 rounded-3"><i class="bi bi-eye-slash"></i></span>
              </div>

              <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="flexCheckDefault"
                    name="remember" value="1">
                  <label class="form-check-label" for="flexCheckDefault">Lembrar</label>
                </div>
                <div class=""><a href="authentication-otp-varification.html" class="forgot-link">Esqueceu sua senha?</a></div>
              </div>

              <div class="mb-0 d-grid">
                <button type="submit" class="btn btn2 ">Logar</button>
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
