@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 m-0">
        <div class="brand-logo mb-0">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 40px; width: auto;" 
                     class="">
            </a>
        </div>
        <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none">Pacientes</h6>
    </div>
@endsection

@section('button_add')
    <li class="nav-item">
        <a class="nav-link text-slate-700 hover:text-teal-600 transition-colors" href="{{ route('app.paciente.add') }}"><i style="font-size: 1.8rem;" class="bi bi-person-plus-fill"></i></a>
    </li>
@endsection

@section('content')
    <!--start to page content-->
    <div class="px-2 pt-0 pb-6 min-h-screen" x-data="appPacienteList" style="margin-top: -30px !important;">

        <form x-on:submit.prevent="getPacientes">
            <div class="position-relative">
                <input type="text" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-slate-800 font-semibold text-sm placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-all shadow-sm" placeholder="Pesquisar..."
                    x-model.debounce="search">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <template x-if="!loading">
                        <i class="bi bi-search text-lg"></i>
                    </template>
                    <template x-if="loading">
                        <span class="spinner-border spinner-border-sm text-teal-600" aria-hidden="true"></span>
                    </template>
                </span>
            </div>
        </form>

        <div class="py-1"></div>

        <div>
            <template x-if="pacientes.length==0">
                <div class="mb-3">
                    <div class="flex items-center justify-center min-h-[60vh] w-full">
                        <img src="{{ asset('app/assets/images/multidao.png') }}" class="img-fluid max-w-[80%] opacity-80" alt="Nenhum paciente encontrado">
                    </div>
                </div>
            </template>
        </div>

        <div class="space-y-3">
            <template x-for="paciente, index in pacientes" x-bind:key="index">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden group">
                     
                    <!-- Corpo do Card -->
                    <div class="p-3">
                         <div class="flex gap-3">
                             <!-- Avatar -->
                             <div class="flex-shrink-0 pt-1">
                                 <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-lg font-bold border border-slate-200">
                                     <span x-text="paciente.nm_paciente.charAt(0).toUpperCase()"></span>
                                 </div>
                             </div>

                             <!-- Info List -->
                             <div class="flex-grow min-w-0">
                                  <!-- Nome -->
                                  <h3 class="text-sm font-bold text-slate-800 leading-tight mb-2" style="word-break: break-word;" x-text="paciente.nm_paciente"></h3>
                                  
                                  <div class="space-y-1.5">
                                      <!-- Data Nasc -->
                                      <div class="flex items-center gap-2 text-xs text-slate-600">
                                          <i class="bi bi-calendar4 text-slate-400"></i>
                                          <span x-text="formatDate(paciente.dt_nasc) || 'Data não inf.'"></span>
                                      </div>
                                      
                                      <!-- Celular -->
                                      <div class="flex items-center gap-2 text-xs text-slate-600">
                                          <i class="bi bi-whatsapp text-emerald-500"></i>
                                          <span x-text="paciente.celular || 'Sem celular'"></span>
                                      </div>

                                      <!-- Mae/Pai --> 
                                      <template x-if="paciente.nm_mae">
                                        <div class="flex items-center gap-2 text-xs text-slate-500 pt-1 border-t border-slate-50 mt-1">
                                            <span class="text-[10px] uppercase text-slate-400 font-bold w-6">Mãe:</span>
                                            <span class="truncate" x-text="paciente.nm_mae"></span>
                                        </div>
                                      </template>
                                  </div>
                             </div>
                         </div>
                    </div>

                    <!-- Barra de Ações (Rodapé do Card) - BOTOES COLORIDOS -->
                    <div class="grid grid-cols-3 divide-x divide-white border-t border-slate-100">
                        <a x-bind:href="`${routePacienteEditBase}/${paciente.cd_paciente}`" 
                           class="flex flex-col items-center justify-center py-2.5 bg-sky-50 text-sky-600 hover:bg-sky-100 transition-colors group/btn">
                            <i class="bi bi-pencil mb-0.5 text-base group-hover/btn:scale-110 transition-transform"></i>
                            <span class="text-[9px] font-bold uppercase tracking-wide">Editar</span>
                        </a>

                        <a x-bind:href="`${routePacienteDocBase}/${paciente.cd_paciente}`" 
                           class="flex flex-col items-center justify-center py-2.5 bg-teal-50 text-teal-600 hover:bg-teal-100 transition-colors group/btn">
                            <i class="bi bi-file-earmark-medical mb-0.5 text-base group-hover/btn:scale-110 transition-transform"></i>
                            <span class="text-[9px] font-bold uppercase tracking-wide">Docs</span>
                        </a>

                        <a x-bind:href="`${routePacienteHistBase}/${paciente.cd_paciente}`" 
                           class="flex flex-col items-center justify-center py-2.5 bg-orange-50 text-orange-600 hover:bg-orange-100 transition-colors group/btn">
                            <i class="bi bi-clock-history mb-0.5 text-base group-hover/btn:scale-110 transition-transform"></i>
                            <span class="text-[9px] font-bold uppercase tracking-wide">Histórico</span>
                        </a>
                    </div>

                </div>
            </template>
        </div>

        <template x-if="loading">
            <div class="mb-3 text-center py-4">
                <span class="spinner-border spinner-border-sm text-slate-400" aria-hidden="true"></span>
            </div>
        </template>
     


        <template x-if="nextPage">
            <button class="btn w-100 bg-white border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 rounded-xl py-3 shadow-sm" x-on:click="getPacientes">
                Ver mais pacientes
            </button>
        </template>
    </div>
@endsection


@push('scripts')
    <script>
        const routePacienteList = @js(url('app_rpclinic/api/paciente-list'));
        const routePacienteEditBase = @js(url('app_rpclinic/paciente-edit'));
        const routePacienteDocBase = @js(url('app_rpclinic/paciente-doc'));
        const routePacienteHistBase = @js(url('app_rpclinic/paciente-hist'));
    </script>
    <script src="{{ asset('js/app_rpclinica/paciente-list.js') }}"></script>
@endpush
