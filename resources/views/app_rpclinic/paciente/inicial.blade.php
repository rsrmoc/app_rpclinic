@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="brand-logo">
        <a href="javascript:;"><img src="{{ asset('app/assets/images/logo_menu.svg') }}" width="190" alt=""></a>
    </div>
@endsection

@section('button_add')
    <li class="nav-item">
        <a class="nav-link text-slate-700 hover:text-teal-600 transition-colors" href="{{ route('app.paciente.add') }}"><i style="font-size: 1.8rem;" class="bi bi-person-plus-fill"></i></a>
    </li>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appPacienteList">

        <form x-on:submit.prevent="getPacientes">
            <div class="position-relative">
                <input type="text" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3 text-slate-900 placeholder-slate-500 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-all shadow-sm" style="font-size: 1.1rem;" placeholder="Pesquisar Paciente..."
                    x-model.debounce="search">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500">
                    <template x-if="!loading">
                        <i class="bi bi-search text-xl"></i>
                    </template>
                    <template x-if="loading">
                        <span class="spinner-border spinner-border-sm text-teal-600" aria-hidden="true"></span>
                    </template>
                </span>
            </div>
        </form>

        <div class="py-2"></div>

        <div>
            <template x-if="pacientes.length==0">
                <div class="mb-3">
                    <div style="text-align: center; margin-top: 30px;" >
                        <img src="{{ asset('app/assets/images/multidao.png') }}" class="img-fluid" style="opacity: 0.6;" alt="">
                    </div>
                </div>
            </template>
        </div>

        <div>
            <template x-for="paciente, index in pacientes" x-bind:key="index">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-4 p-4 transition-all duration-300 hover:shadow-md">
                     <div class="flex flex-row gap-4">
                         <div class="flex-grow text-slate-600">
                                  <span class="font-bold text-lg text-slate-900 block mb-1" x-text="paciente.nm_paciente"></span>
                                  <div class="text-sm space-y-1">
                                      <div><strong class="text-teal-600">Mãe:</strong> <span x-text="paciente.nm_mae"></span></div>
                                      <div><strong class="text-teal-600">Pai:</strong> <span x-text="paciente.nm_pai"></span></div>
                                      <div><strong class="text-teal-600">Nascimento:</strong> <span x-text="formatDate(paciente.dt_nasc)"></span></div>
                                      <div><strong class="text-teal-600">Celular:</strong> <span class="font-bold" x-text="paciente.celular"></span></div>
                                  </div>
                         </div>
                         
                         <div class="flex flex-col gap-2 justify-start">
                             <a x-bind:href="`/app_rpclinic/paciente-edit/${paciente.cd_paciente}`" class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center hover:bg-sky-100 transition-colors border border-sky-100">
                                 <i class="bi bi-pencil"></i>
                             </a>
     
                             <a x-bind:href="`/app_rpclinic/paciente-doc/${paciente.cd_paciente}`" class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center hover:bg-teal-100 transition-colors border border-teal-100">
                                 <img src="{{ asset('app/assets/images/af_evolution.svg') }}" class="w-5 h-5 opacity-80" style="filter: brightness(0) saturate(100%) invert(29%) sepia(35%) saturate(3015%) hue-rotate(159deg) brightness(95%) contrast(92%);">
                             </a>
     
                             <a x-bind:href="`/app_rpclinic/paciente-hist/${paciente.cd_paciente}`" class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center hover:bg-orange-100 transition-colors border border-orange-100">
                                 <i class="bi bi-search-heart"></i>
                             </a>
                         </div>
                     </div>
                </div>
            </template>
        </div>

        <template x-if="loading">
            <div class="mb-3">
                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>&ensp;
                <span>Carregando...</span>
            </div>
        </template>
     


        <template x-if="nextPage">
            <button class="btn btn-outline-secondary w-100" x-on:click="getPacientes">Ver mais</button>
        </template>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('js/app_rpclinica/paciente-list.js') }}"></script>
@endpush
