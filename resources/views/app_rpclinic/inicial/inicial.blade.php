@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="brand-logo">
        <a href="javascript:;"><img src="{{ asset('app/assets/images/logo_menu.svg') }}" width="190" alt=""></a>
    </div>
@endsection

 

@section('content')
      <!--start to page content-->
      <div class="page-content px-4 py-6 min-h-screen">


        <div class="features-section mb-6">
          <div class="grid grid-cols-2 gap-4">
            <div class="flex">
              <div class="w-full bg-white rounded-2xl shadow-sm p-4 border border-slate-200 relative overflow-hidden group">
                 <div class="absolute top-0 right-0 p-3 opacity-10">
                    <i class="bi bi-calendar-check text-4xl text-teal-600"></i>
                 </div>
                <div class="flex flex-col items-center justify-center">
                    <div class="text-3xl font-bold text-slate-900 mb-1">365</div>
                    <p class="text-sm text-teal-600 font-bold">Agendamentos</p>
                </div>
              </div>
            </div>
            <div class="flex">
              <div class="w-full bg-white rounded-2xl shadow-sm p-4 border border-slate-200 relative overflow-hidden group">
                 <div class="absolute top-0 right-0 p-3 opacity-10">
                    <i class="bi bi-people text-4xl text-purple-600"></i>
                 </div>
                <div class="flex flex-col items-center justify-center">
                    <div class="text-3xl font-bold text-slate-900 mb-1">3443</div>
                    <p class="text-sm text-purple-600 font-bold">Atendimentos</p>
                </div>
              </div>
            </div>
        
          </div>
        </div>

        <div class="space-y-3">
            
            <a href="{{ route('app.paciente') }}" class="block group">
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-4 flex items-center gap-4 border border-slate-200">
                    <div class="h-12 w-12 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center text-xl border border-sky-100">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-slate-900 text-lg">Pacientes</h3>
                        <p class="text-xs text-slate-500 font-medium">Gerenciar lista de pacientes</p>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-100 group-hover:text-slate-600 transition-colors">
                        <i class="bi bi-chevron-right text-sm"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('app.agendamento') }}" class="block group">
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-4 flex items-center gap-4 border border-slate-200">
                    <div class="h-12 w-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-xl border border-teal-100">
                        <i class="bi bi-calendar3"></i>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-slate-900 text-lg">Agendamentos</h3>
                        <p class="text-xs text-slate-500 font-medium">Visualizar sua agenda</p>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-100 group-hover:text-slate-600 transition-colors">
                        <i class="bi bi-chevron-right text-sm"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('app.consultorio') }}" class="block group">
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-4 flex items-center gap-4 border border-slate-200">
                    <div class="h-12 w-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-xl border border-rose-100">
                         <i class="bi bi-calendar2-heart"></i>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-slate-900 text-lg">Consultório</h3>
                        <p class="text-xs text-slate-500 font-medium">Atendimento clínico</p>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-100 group-hover:text-slate-600 transition-colors">
                        <i class="bi bi-chevron-right text-sm"></i>
                    </div>
                </div>
            </a>

            <a href="{{ route('app.perfil') }}" class="block group">
                 <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-4 flex items-center gap-4 border border-slate-200">
                    <div class="h-12 w-12 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center text-xl border border-orange-100">
                        <i class="bi bi-person-rolodex"></i>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-slate-900 text-lg">Perfil Profissional</h3>
                         <p class="text-xs text-slate-500 font-medium">Meus dados e configurações</p>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-100 group-hover:text-slate-600 transition-colors">
                        <i class="bi bi-chevron-right text-sm"></i>
                    </div>
                </div>
            </a>

             <a href="{{ route('app.assinar') }}" class="block group">
                 <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-4 flex items-center gap-4 border border-slate-200">
                    <div class="h-12 w-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl border border-indigo-100">
                         <i class="bi bi-file-earmark-medical"></i>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-slate-900 text-lg">Documentos</h3>
                         <p class="text-xs text-slate-500 font-medium">Assinatura digital e arquivos</p>
                    </div>
                    <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-slate-100 group-hover:text-slate-600 transition-colors">
                        <i class="bi bi-chevron-right text-sm"></i>
                    </div>
                </div>
            </a>

        </div>


      </div>
      <!--end to page content-->
@endsection
