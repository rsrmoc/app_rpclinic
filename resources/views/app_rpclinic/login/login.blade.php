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
    <div class="loader-wrapper" style="background-color: #f8fafc;">
      <div class="d-flex justify-content-center align-items-center position-absolute top-50 start-50 translate-middle">
        <div class="spinner-border text-teal-600" role="status">
          <span class="visually-hidden">Carregando...</span>
        </div>
      </div>
    </div>
   <!--end loader-->

   <!--start wrapper-->
    <div class="wrapper" style="background: #cbd5e1; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
       <!--start to page content-->
       <div class="page-content bg-transparent w-100">
          <div class="login-body p-4" style="max-width: 450px; margin: 0 auto;">
             <!-- Background Doctor Image -->
             <div class="bg-watermark"></div>
             <form action="{{ route('app.login.action') }}" method="POST" class="mt-4 bg-transparent p-0 position-relative z-10">
               @csrf

               <div style="text-align: center" class="mb-5">
                   <img src="{{ asset('app/assets/images/logo_completa.svg') }}" class="img-fluid" style="width: 80%;" alt="RP Clinic">
               </div>

               @error('error')
                 <div class="alert alert-danger bg-red-50 text-red-700 border-red-200 rounded-xl mb-4" role="alert">
                   <i class="bi bi-exclamation-triangle-fill me-2"></i>
                   {{ $message }}
                 </div>
               @enderror

               <div class="form-floating mb-4">
                 <input type="email" class="form-control rounded-2xl bg-white border border-slate-200 text-slate-800 shadow-sm focus:border-teal-500 focus:ring-teal-500" id="floatingInputEmail" placeholder="name@example.com" value="{{ old('email') }}"
                   name="email" required style="height: 58px;">
                 <label for="floatingInputEmail" class="text-slate-500 bg-transparent">Email de Acesso</label>
               </div>

               <div class="position-relative mb-5">
                 <div class="form-floating">
                   <input type="password" class="form-control rounded-2xl bg-white border border-slate-200 text-slate-800 shadow-sm focus:border-teal-500 focus:ring-teal-500" id="floatingInputPassword" placeholder="Senha" 
                     name="password" required minlength="2" style="height: 58px; padding-right: 50px;">
                   <label for="floatingInputPassword" class="text-slate-500 bg-transparent">Senha</label>
                 </div>
                 <span class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer text-slate-400 hover:text-teal-600 transition-colors" id="togglePassword" style="z-index: 10;">
                    <i class="bi bi-eye-slash text-xl" id="iconEye"></i>
                 </span>
               </div>

               <div class="d-flex align-items-center justify-content-between mb-5">
                 <div class="form-check">
                   <input type="checkbox" class="form-check-input border-slate-300" id="flexCheckDefault"
                     name="remember" value="1">
                   <label class="form-check-label text-slate-600 text-sm" for="flexCheckDefault">Lembrar de mim</label>
                 </div>
                 <div class=""><a href="#" class="forgot-link text-teal-600 text-sm font-bold no-underline">Esqueceu?</a></div>
               </div>

               <div class="mb-0 w-100">
                 <button type="submit" class="btn btn2 w-100 py-3 rounded-2xl font-bold shadow-lg shadow-teal-500/20 text-white border-0">LOGAR</button>
               </div>
             </form>
          </div>
       </div>
     <!--end to page content-->

       <style>

            /* Copied Watermark Style from Global */
             /* Background Image Style */
            .bg-watermark {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
            }
            
            .bg-watermark::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: url('{{ asset("app/assets/images/doctor_bg.png") }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }

            .bg-watermark::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.7); /* Lighter overlay to see the doctor better */
                backdrop-filter: blur(2px);
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .bg-watermark::before {
                    background-position: top center; /* Focus on face for mobile */
                }
            }


            .btn2 {
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                width: 100%;
                min-height: 55px;
                border-radius: 25px;
                outline: none;
                border: none;
                background-image: linear-gradient(to right, #135ea2, #69bb64, #135ea2);
                background-size: 200%;
                color: #fff;
                font-family: 'Poppins', sans-serif;
                text-transform: uppercase;
                font-size: 1.1rem; /* Reduzido levemente de 1.3rem para caber melhor */
                font-weight: 700;
                cursor: pointer;
                transition: .5s;
                background-position: right;
            }

            .btn2:hover {
                background-position: left;
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            }
        </style>




    </div>
   <!--end wrapper-->


    <!--JS Files-->
    <script src="{{ asset('app/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('app/assets/js/jquery.min.js') }}"></script>
    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('floatingInputPassword');
            const icon = document.getElementById('iconEye');
            
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
                icon.classList.replace('text-slate-400', 'text-teal-600');
            } else {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
                icon.classList.replace('text-teal-600', 'text-slate-400');
            }
        });
    </script>
    <script src="{{ asset('app/assets/js/loader.js') }}"></script>



  </body>
</html>
