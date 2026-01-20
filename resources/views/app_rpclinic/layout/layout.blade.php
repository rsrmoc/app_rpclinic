<!doctype html>
<html lang="en" class="light-theme">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <link href="{{ asset('app/assets/css/dark-theme.css') }}" rel="stylesheet"/>
 
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

    </style>

    @stack('styles')

     
  </head>
  <body class="bg-gradient-to-br from-slate-900 via-sky-900 to-teal-900 min-h-screen bg-fixed">

   <!--page loader-->
    <div class="loader-wrapper">
      <div class="d-flex justify-content-center align-items-center position-absolute top-50 start-50 translate-middle">
        <div class="spinner-border text-white" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
   <!--end loader-->

   <!--start wrapper-->
    <div class="wrapper">

       <!--start to header-->
       <header class="top-header fixed-top border-bottom d-flex align-items-center">
        <nav class="navbar navbar-expand w-100 p-0 gap-3 align-items-center">
            <div class="nav-button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSidenav"><a href="javascript:;"><i class="bi bi-list"></i></a></div>


            @yield('button_left')


            <ul class="navbar-nav ms-auto d-flex align-items-center top-right-menu">

                @yield('button_add')

            </ul>
        </nav>
       </header>
        <!--end to header-->

        <!-- conteudo da pagina -->
           @yield('content')
        <!-- conteudo da pagina -->



        <!--start to footer-->
     </div>
    <!--end wrapper-->

    <!--start to footer-->
    <div class="fixed bottom-0 w-full z-50 px-4 pb-4" style="position: fixed; bottom: 0; left: 0; right: 0;">
           <nav class="flex justify-between items-center px-4 h-[4.5rem] max-w-lg mx-auto relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl">
             
               <!-- Paciente -->
               <a href="{{ route('app.paciente') }}" class="flex flex-col items-center justify-center w-full py-2 rounded-xl transition-all duration-300 group hover:no-underline hover:bg-white/5">
                 <i class="bi bi-people text-2xl mb-1 {{ request()->routeIs('app.paciente') ? 'text-sky-400' : 'text-slate-300' }}"></i>
                 <span class="text-[0.65rem] font-bold {{ request()->routeIs('app.paciente') ? 'text-sky-400' : 'text-slate-300' }}">Paciente</span>
               </a>

               <!-- Agenda -->
               <a href="{{ route('app.agendamento') }}" class="flex flex-col items-center justify-center w-full py-2 rounded-xl transition-all duration-300 group hover:no-underline hover:bg-white/5">
                 <i class="bi bi-calendar3 text-2xl mb-1 {{ request()->routeIs('app.agendamento') ? 'text-teal-400' : 'text-slate-300' }}"></i>
                 <span class="text-[0.65rem] font-bold {{ request()->routeIs('app.agendamento') ? 'text-teal-400' : 'text-slate-300' }}">Agenda</span>
               </a>

               <!-- Home (Floating) -->
               <div class="relative w-full flex justify-center group pointer-events-none">
                 <a href="{{ route('app.inicial') }}" class="pointer-events-auto absolute -top-8 w-14 h-14 rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(45,212,191,0.5)] border-[4px] border-slate-900 transform transition-all duration-300 hover:scale-105 hover:no-underline bg-gradient-to-br from-teal-500 to-emerald-500">
                   <i class="bi bi-house-fill text-xl text-white"></i>
                 </a>
                 <span class="absolute bottom-2 text-[0.65rem] font-bold {{ request()->routeIs('app.inicial') ? 'text-emerald-400' : 'text-slate-300' }} pt-7">Home</span>
               </div>

               <!-- Consulta -->
               <a href="{{ route('app.consultorio') }}" class="flex flex-col items-center justify-center w-full py-2 rounded-xl transition-all duration-300 group hover:no-underline hover:bg-white/5">
                 <i class="bi bi-calendar2-heart text-2xl mb-1 {{ request()->routeIs('app.consultorio') ? 'text-rose-400' : 'text-slate-300' }}"></i>
                 <span class="text-[0.65rem] font-bold {{ request()->routeIs('app.consultorio') ? 'text-rose-400' : 'text-slate-300' }}">Consulta</span>
               </a>

               <!-- Perfil -->
               <a href="{{ route('app.perfil') }}" class="flex flex-col items-center justify-center w-full py-2 rounded-xl transition-all duration-300 group hover:no-underline hover:bg-white/5">
                 <i class="bi bi-person-rolodex text-2xl mb-1 {{ request()->routeIs('app.perfil') ? 'text-orange-400' : 'text-slate-300' }}"></i>
                 <span class="text-[0.65rem] font-bold {{ request()->routeIs('app.perfil') ? 'text-orange-400' : 'text-slate-300' }}">Perfil</span>
               </a>

           </nav>
        </div>
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


