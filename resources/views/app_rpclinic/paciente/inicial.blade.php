@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="brand-logo">
        <a href="javascript:;"><img src="{{ asset('app/assets/images/logo_menu.svg') }}" width="190" alt=""></a>
    </div>
@endsection

@section('button_add')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('app.paciente.add') }}"><i style="font-size: 1.8rem;" class="bi bi-person-plus-fill"></i></a>
    </li>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appPacienteList">

        <form x-on:submit.prevent="getPacientes">
            <div class="position-relative">
                <input type="text" class="w-full bg-white/10 border border-white/20 rounded-2xl px-5 py-3 text-white placeholder-slate-400 focus:outline-none focus:border-teal-400 focus:ring-1 focus:ring-teal-400 transition-all backdrop-blur-sm" style="font-size: 1.1rem;" placeholder="Pesquisar Paciente..."
                    x-model.debounce="search">
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <template x-if="!loading">
                        <i class="bi bi-search text-xl"></i>
                    </template>
                    <template x-if="loading">
                        <span class="spinner-border spinner-border-sm text-teal-400" aria-hidden="true"></span>
                    </template>
                </span>
            </div>
        </form>

        <div class="py-2"></div>

        <div>
            <template x-if="pacientes.length==0">
                <div class="mb-3">
                    <div style="text-align: center; margin-top: 30px;" >
                        <img src="{{ asset('app/assets/images/multidao.png') }}" class="img-fluid" alt="">
                    </div>
                </div>
            </template>
        </div>

        <div>
            <template x-for="paciente, index in pacientes" x-bind:key="index">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-lg border border-white/10 mb-4 p-4 transition-all duration-300 hover:bg-white/15">
                     <div class="flex flex-row gap-4">
                         <div class="flex-grow text-slate-200">
                                 <span class="font-bold text-lg text-white block mb-1" x-text="paciente.nm_paciente"></span>
                                 <div class="text-sm space-y-1">
                                     <div><strong class="text-teal-400">Mãe:</strong> <span x-text="paciente.nm_mae"></span></div>
                                     <div><strong class="text-teal-400">Pai:</strong> <span x-text="paciente.nm_pai"></span></div>
                                     <div><strong class="text-teal-400">Nascimento:</strong> <span x-text="formatDate(paciente.dt_nasc)"></span></div>
                                     <div><strong class="text-teal-400">Celular:</strong> <span class="font-bold" x-text="paciente.celular"></span></div>
                                 </div>
                         </div>
                         
                         <div class="flex flex-col gap-2 justify-start">
                             <a x-bind:href="`/app_rpclinic/paciente-edit/${paciente.cd_paciente}`" class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-300 flex items-center justify-center hover:bg-sky-500/30 transition-colors border border-sky-500/30">
                                 <i class="bi bi-pencil"></i>
                             </a>
     
                             <a x-bind:href="`/app_rpclinic/paciente-doc/${paciente.cd_paciente}`" class="w-10 h-10 rounded-xl bg-teal-500/20 text-teal-300 flex items-center justify-center hover:bg-teal-500/30 transition-colors border border-teal-500/30">
                                 <img src="{{ asset('app/assets/images/af_evolution.svg') }}" class="w-5 h-5 opacity-80 invert">
                             </a>
     
                             <a x-bind:href="`/app_rpclinic/paciente-hist/${paciente.cd_paciente}`" class="w-10 h-10 rounded-xl bg-orange-500/20 text-orange-300 flex items-center justify-center hover:bg-orange-500/30 transition-colors border border-orange-500/30">
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
