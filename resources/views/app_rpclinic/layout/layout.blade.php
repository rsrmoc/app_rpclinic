<!doctype html>
<html lang="en" class="light-theme">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>RPclinic - Consultório</title>

     <!-- Plugins -->
     <link rel="stylesheet" type="text/css" href="{{ asset('app/assets/plugins/metismenu/metisMenu.min.css') }}" />
     <link rel="stylesheet" type="text/css" href="{{ asset('app/assets/plugins/metismenu/mm-vertical.css') }}" />
     <link rel="stylesheet" type="text/css" href="{{ asset('app/assets/plugins/slick/slick.css') }}" />
     <link rel="stylesheet" type="text/css" href="{{ asset('app/assets/plugins/slick/slick-theme.css') }}" />

    <!--CSS Files-->
    <link href="{{ asset('app/assets/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <link href="{{ asset('assets/plugins/fontawesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('app/assets/css/style.css') }}" rel="stylesheet"/>
    <!-- <link href="{{ asset('app/assets/css/dark-theme.css') }}" rel="stylesheet"/> -->
 
    <link href="{{ asset('app/assets/css/toastr.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    
    <style>

.event-bg {
            color: #7A6600;
            background-color: #F9F1C8;
            border-color: #E6A21A;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .event-am {
            color: #7A6600;
            background-color: #FFFCEB;
            border-color: #FFD500;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .event-ac {
            color: #00627A;
            background-color: #EFF8FB;
            border-color: #00CCFF;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .event-ae {
            color: #00417A;
            background-color: #C2E2FF;
            border-color: #008CFF;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .event-lc {
            color: #803C00;
            background-color: #FFF1E5;
            border-color: #FF7700;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .event-le {
            color: #7A2100;
            background-color: #FFD2C2;
            border-color: #FF4400;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;

        }

        .event-ll {
            color: #41007A;
            background-color: #F5EBFF;
            border-color: #8800FF;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .event-rx {
            color: #00107A;
            background-color: #C2CAFF;
            border-color: #0022FF;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .event-vc {
            color: #226218;
            background-color: #F0FBEF;
            border-color: #40BF4A;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .event-ve {
            color: #226218;
            background-color: #CAFFC2;
            border-color: #00800B;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
}
        }

        .event-rs {
            color: #7A0062;
            background-color: #FFEBFB;
            border-color: #FF00CC;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .event-vm {
            color: #7A0018;
            background-color: #FFC2CE;
            border-color: #FF0033;
            padding: 3px;
            border: 1px solid;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }
        .swal2-popup{
            border-radius: 0px;
        }
        .swal-button{
            margin: 2px;
        }     

       .label-warning {
            background: #f6d433;
            color: white;
            padding: 3px;
            border: 1px solid #c5a60a;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }
        
        .label-aguardando{
            background: #FF9800;
            color: white;
            padding: 3px;
            border: 1px solid #af7011;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }
        .label-info {
            background: #12AFCB;
            color: white;
            padding: 3px;
            border: 1px solid #0a6879;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        } 
        .label-primary {
            background: #7a6fbe;
            color: white;
            padding: 3px;
            border: 1px solid #4a446d;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        .label-danger {
            background: #ea0033;
            color: white;
            padding: 3px;
            border: 1px solid #70061d;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }
         
        .label-success {
            background-color: #5cb85c;
            color: white;
            padding: 3px;
            border: 1px solid #428342;
            border-radius: 4px;
            padding-right: 8px;
            padding-left: 8px;
        }

        /* Ajuste Sidenav Glassmorphism */
        .sidebar-nav .metismenu a {
            color: #cbd5e1 !important; /* slate-300 */
            border-bottom: none !important;
            background-color: transparent !important;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        .sidebar-nav .metismenu a:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        .sidebar-nav .metismenu li.mm-active > a,
        .sidebar-nav .metismenu li.active > a {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        /* Air Datepicker Global Fix for Mobile */
        .air-datepicker {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            box-sizing: border-box !important;
            left: 0 !important;
            right: 0 !important;
        }

        .air-datepicker--content {
            padding: 0 !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .air-datepicker--body {
            width: 100% !important;
        }

        .air-datepicker--days {
            grid-template-columns: repeat(7, 1fr) !important;
        }
        
        .datePickerAgendamento {
             width: 100% !important;
             max-width: 100% !important;
             box-sizing: border-box !important;
        }

        /* Global Select Styles for Dark Mode */
        select, option {
            background-color: #1e293b !important; /* slate-800 */
            color: #f1f5f9 !important; /* slate-100 */
        }

        select:focus {
            outline: 2px solid #2dd4bf !important; /* teal-400 */
        }

        /* GPU Acceleration for Flickering Fix */
        .bottom-nav-card, .top-header, .app-wrapper {
            will-change: transform;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: translateZ(0); 
        }

        /* Full Width App Container - WHITE THEME UPDATE */
        body {
            background-color: #f8fafc !important; /* Slate-50 - very light grey/white */
            overscroll-behavior: none !important;
            -webkit-tap-highlight-color: transparent;
            margin: 0;
            padding: 0;
            touch-action: manipulation;
            color: #1e293b; /* Slate-800 - Dark text */
        }

        .wrapper {
            width: 100% !important;
            min-height: 100vh;
            position: relative;
            background: transparent; /* Changed from #f8fafc to transparent to show body/watermark if needed, though watermark handles it */
            padding-bottom: 90px; /* Space for footer */
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        /* Fix Select and Options for Native Feel - Light Theme */
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23334155' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            font-size: 16px !important;
            color: #1e293b !important;
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
        }

        select option {
            background-color: #ffffff;
            color: #1e293b;
            padding: 15px;
        }

        /* Light Theme Cards */
        .card {
            background-color: white !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }

        .card-body {
            background-color: transparent !important;
            color: #1e293b !important;
        }

        /* Fix text colors for Light Theme */
        .text-dark {
            color: #1e293b !important;
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: #0f172a !important;
        }

        .form-floating label {
            color: #64748b !important;
        }

        .form-control {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #1e293b !important;
        }
        
        .form-control:focus {
            border-color: #0d9488 !important; /* Teal-600 */
            box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.2) !important;
        }

        /* Full-width Modal Overrides (Tema Light) */
        .modal-content {
            background-color: #ffffff !important;
            color: #1e293b !important;
            border: none !important;
            border-radius: 20px !important;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        .modal-header, .modal-footer {
            border-color: #e2e8f0 !important;
            background: transparent !important;
        }

        .modal-title {
            color: #0f172a !important;
            font-weight: 600;
        }

        .modal-body label, .modal-body span, .modal-body p {
            color: #334155 !important;
        }

        /* Fixed Top Header Stability */
        .top-header {
            width: 100% !important;
            left: 0 !important;
            right: 0 !important;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(15px) !important;
            -webkit-backdrop-filter: blur(15px) !important;
            z-index: 10001; 
            height: 80px !important;
        }

        /* Bottom Nav Sustainability - KEPT DARK AS REQUESTED */
        .bottom-nav-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            z-index: 10000;
            padding: 0 10px 10px 10px;
            pointer-events: none; 
        }

        .bottom-nav-card {
            background: rgba(30, 41, 59, 0.98); /* Keeping this Dark Slate */
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 70px;
            box-shadow: 0 -5px 25px rgba(0,0,0,0.2);
            max-width: 600px;
            margin: 0 auto;
            pointer-events: auto; 
        }

        .nav-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            color: #94a3b8;
            text-decoration: none !important;
            height: 100%;
        }

        .nav-btn.active {
            color: #2dd4bf;
        }

        .nav-btn i {
            font-size: 20px;
            margin-bottom: 2px;
        }

        .nav-btn span {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .home-btn-wrap {
            position: relative;
            width: 60px;
            display: flex;
            justify-content: center;
        }

        .home-btn {
            position: absolute;
            top: -25px;
            width: 52px;
            height: 52px;
            background: #14b8a6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);
            border: 4px solid #f8fafc; /* Updated border to match light bg */
            color: white !important;
        }

        .home-label {
            position: absolute;
            bottom: 8px;
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
        }
        
        /* Centralized Layout for Watermark */
        .page-content {
            position: relative;
            z-index: 1;
            padding-top: 80px !important; /* Adjusted for larger header */
        }

        /* Background Stethoscope Watermark - Centered */
        .bg-watermark {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            opacity: 0.15;
            background-image: url('{{ asset("app/assets/images/doctor_bg.png") }}');
            background-size: cover;
            background-position: center top;
            background-repeat: no-repeat;
        }

        /* LOGO CENTERING FIX */
        .brand-logo img {
            max-height: none !important; /* Allow logo to grow */
            height: auto;
            width: auto;
            display: block; 
            margin: 0 auto;
        }

        /* FORCE WHITE BACKGROUND - CRITICAL OVERRIDE */
        html, body {
            background-color: #ffffff !important;
            background: #ffffff !important;
            font-weight: 500 !important; /* Global Bold */
        }
    </style>

    @stack('styles')

     
  </head>
  <body class="min-h-screen bg-fixed">

    <!--page loader-->
    <div class="loader-wrapper" style="background: #0f172a; z-index: 10005;">
      <div class="d-flex justify-content-center align-items-center position-absolute top-50 start-50 translate-middle">
        <div class="spinner-border text-teal-400" role="status">
          <span class="visually-hidden">Carregando...</span>
        </div>
      </div>
    </div>
   <!--end loader-->

   <!--start wrapper-->
    <div class="wrapper">
       <!--start to page content-->
       <div class="page-content bg-transparent">
             <!-- Background Stethoscope Watermark -->
             <div class="bg-watermark">
            </div>

       <!--start to header-->
       <!--start to header-->
       <header class="top-header fixed-top border-bottom d-flex align-items-center shadow-sm" style="background: #ffffff !important; height: 80px !important;">
        <nav class="navbar navbar-expand w-100 p-0 align-items-center px-3 position-relative">
            <!-- Left: Menu Toggle -->
            <div class="nav-button me-auto" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidenav" style="cursor: pointer;">
                <a href="javascript:;" class="text-slate-700 hover:text-teal-600 transition-colors">
                    <i class="bi bi-list" style="font-size: 2rem;"></i>
                </a>
            </div>

            <!-- Center: Logo -->
            <div class="position-absolute top-50 start-50 translate-middle d-flex justify-content-center align-items-center">
                @yield('button_left')
            </div>

            <!-- Right: Actions -->
            <ul class="navbar-nav ms-auto d-flex align-items-center gap-2">
                @yield('button_add')
            </ul>
        </nav>
       </header>
        <!--end to header-->
        <!--end to header-->

        <!-- conteudo da pagina -->
           @yield('content')
        <!-- conteudo da pagina -->



        <!--start to footer-->
     </div>
    <!--end wrapper-->

    <!--start to footer-->
    <div class="bottom-nav-container no-print">
           <nav class="bottom-nav-card shadow-2xl">
             
               <!-- Paciente -->
               <a href="{{ route('app.paciente') }}" class="nav-btn {{ request()->routeIs('app.paciente') ? 'active' : '' }}">
                 <i class="bi bi-people"></i>
                 <span>Paciente</span>
               </a>

               <!-- Agenda -->
               <a href="{{ route('app.agendamento') }}" class="nav-btn {{ request()->routeIs('app.agendamento') ? 'active' : '' }}">
                 <i class="bi bi-calendar3"></i>
                 <span>Agenda</span>
               </a>

               <!-- Home (Floating) -->
               <div class="home-btn-wrap">
                 <a href="{{ route('app.inicial') }}" class="home-btn hover:scale-105 transition-transform">
                   <i class="bi bi-house-fill text-xl"></i>
                 </a>
                 <span class="home-label {{ request()->routeIs('app.inicial') ? 'text-teal-400' : '' }}">Home</span>
               </div>

               <!-- Consulta -->
               <a href="{{ route('app.consultorio') }}" class="nav-btn {{ request()->routeIs('app.consultorio') ? 'active' : '' }}">
                 <i class="bi bi-calendar2-heart"></i>
                 <span>Consulta</span>
               </a>

               <!-- Perfil -->
               <a href="{{ route('app.perfil') }}" class="nav-btn {{ request()->routeIs('app.perfil') ? 'active' : '' }}">
                 <i class="bi bi-person-rolodex"></i>
                 <span>Perfil</span>
               </a>

           </nav>
        </div>
        <!--end to footer-->

        <!--end to footer-->



        <!--start sidenav-->
        <div class="sidenav">
            <div class="offcanvas offcanvas-start bg-slate-900/95 backdrop-blur-xl border-r border-white/10" tabindex="-1" id="offcanvasSidenav">
            <div class="offcanvas-header bg-transparent border-b border-white/10">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-teal-500/20 rounded-xl p-2 text-teal-400">
                    <i class="bi bi-person-circle h4 mb-0"></i>
                </div>
                <div class="">
                    <h5 class="mb-0 text-white fw-bold truncate">{{ auth()->guard('rpclinica')->user()->nm_profissional ?? 'Usuario' }}</h5>
                    <small class="text-slate-400">RP Clinic</small>
                </div>
                <div class="ms-auto">
                    <a href="javascript:;" class="text-slate-400 hover:text-white transition-colors" data-bs-dismiss="offcanvas"><i class="bi bi-x-lg text-xl"></i></a>
                </div>
            </div>
            </div>
            <div class="offcanvas-body p-0">
            <nav class="sidebar-nav">
                <ul class="metismenu p-3 space-y-2" id="sidenav">
                <li>
                    <a href="{{ route('app.inicial') }}" class="group flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                        <i class="bi bi-house-door text-lg me-3 group-hover:text-teal-400 transition-colors"></i>
                        <span class="font-medium">Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.paciente') }}" class="group flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                        <i class="bi bi-people text-lg me-3 group-hover:text-sky-400 transition-colors"></i>
                        <span class="font-medium">Paciente</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.agendamento') }}" class="group flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                        <i class="bi bi-calendar-check text-lg me-3 group-hover:text-teal-400 transition-colors"></i>
                        <span class="font-medium">Agendamento</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.consultorio') }}" class="group flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                        <i class="bi bi-calendar2-heart text-lg me-3 group-hover:text-rose-400 transition-colors"></i>
                        <span class="font-medium">Consultório</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('app.assinar') }}" class="group flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                        <i class="bi bi-file-earmark-medical text-lg me-3 group-hover:text-indigo-400 transition-colors"></i>
                        <span class="font-medium">Documentos</span>
                    </a>
                </li>
                <li>
                  <a href="{{ route('app.disponibilidade') }}" class="group flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                      <i class="bi bi-clock-history text-lg me-3 group-hover:text-yellow-400 transition-colors"></i>
                      <span class="font-medium">Disponibilidade</span>
                  </a>
              </li>
              <li>
                <a href="{{ route('app.producao') }}" class="group flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                    <i class="bi bi-graph-up-arrow text-lg me-3 group-hover:text-green-400 transition-colors"></i>
                    <span class="font-medium">Produção</span>
                </a>
            </li>
            <li>
                <a href="{{ route('app.indicadores') }}" class="group flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                    <i class="bi bi-pie-chart text-lg me-3 group-hover:text-purple-400 transition-colors"></i>
                    <span class="font-medium">Indicadores</span>
                </a>
            </li>
                <li>
                    <a href="{{ route('app.perfil') }}" class="group flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-white/10 rounded-xl transition-all">
                        <i class="bi bi-person-rolodex text-lg me-3 group-hover:text-orange-400 transition-colors"></i>
                        <span class="font-medium">Perfil</span>
                    </a>
                </li> 
                <li>
                      <form action="{{ route('app.logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                          class="group flex items-center px-4 py-3 w-full text-slate-300 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition-all border-0 bg-transparent text-start">
                            <i class="bi bi-box-arrow-right text-lg me-3"></i>
                            <span class="font-medium">Sair</span>
                        </button>
                      </form>
                </li>
                </ul>
            </nav>
            </div>
            <div class="offcanvas-footer border-t border-white/10 p-4 bg-black/20">
                <div class="flex items-center justify-between text-slate-400">
                    <span class="text-sm font-medium">Versão 3.0</span>
                    <i class="bi bi-shield-check text-teal-500"></i>
                </div>
            </div>
        </div>
        </div>
        <!--end sidenav-->



       </div>
      <!--end wrapper-->


       <!--JS Files-->
       
       
       <script src="{{ asset('app/assets/js/bootstrap.bundle.min.js') }}"></script>
       
       <script src="{{ asset('app/assets/js/jquery.min.js') }}"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
       <script src="{{ asset('app/assets/js/cookies-theme-switcher.js') }}"></script>
       <script src="{{ asset('app/assets/plugins/metismenu/metisMenu.min.js') }}"></script>
       <script src="{{ asset('app/assets/plugins/slick/slick.min.js') }}"></script>
       <script src="{{ asset('app/assets/js/main.js') }}"></script>
       <script src="{{ asset('app/assets/js/index.js') }}"></script>
       <script src="{{ asset('app/assets/js/loader.js') }}"></script>
       <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>

       <script src="{{ asset('js/app.js') }}"></script>
       @stack('scripts')
     </body>
   </html>


