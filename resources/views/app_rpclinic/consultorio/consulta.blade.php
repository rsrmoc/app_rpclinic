@extends('app_rpclinic.layout.layout')

@section('button_back')
    <a href="{{ route('app.consultorio') }}" class="text-slate-500 hover:text-teal-600 transition-colors p-2 ms-2">
        <i class="bi bi-arrow-left text-2xl"></i>
    </a>
@endsection

@section('button_left')
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 m-0">
        <div class="brand-logo mb-0">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 40px; width: auto;">
            </a>
        </div>
        <h6 class="mb-0 text-slate-500 text-[10px] font-bold uppercase tracking-widest mt-0 leading-none">Atendimento</h6>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appConsultaPaciente" style="padding-bottom: 80px; padding-top: 0 !important; margin-top: -30px !important;">

        <!-- Card Paciente -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-4">
             <div class="flex justify-between items-start">
                 <div class="flex-grow">
                     <h5 class="text-slate-800 font-extrabold text-lg leading-tight mb-1">{{ $agendamento->paciente->nm_paciente }}</h5>
                     <div class="flex flex-wrap gap-2 text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">
                        <span>{{ date_format(date_create($agendamento->paciente->dt_nasc), 'd/m/Y') }}</span>
                        <span>•</span>
                        <span>{{ idadeAluno($agendamento->paciente->dt_nasc) }}</span>
                     </div>
                     <div class="text-sm font-bold text-slate-600 mb-1">{{ $agendamento->paciente->nm_mae }}</div>
                     <div class="text-xs font-bold text-teal-600 uppercase">{{ $agendamento->convenio->nm_convenio ?? 'Particular' }}</div>
                     
                     <div class="mt-2">
                        <template x-if="atendido">
                            <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full border border-emerald-200 inline-flex items-center gap-1">
                                <i class="bi bi-check-circle-fill"></i> ATENDIDO
                            </span>
                        </template>

                        <template x-if="!atendido">
                            <span class="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1 rounded-full border border-slate-200 inline-flex items-center gap-1"
                                x-text="capitalizeFirstLetter('{{ $agendamento->situacao }}')"></span>
                        </template>
                     </div>
                 </div>
                 
                <div class="d-grid gap-2 align-self-start">
                    <button type="button" class="btn btn-sm btn-icon text-teal-600 bg-teal-100 hover:bg-teal-200 ring-2 ring-teal-100 transition-all rounded-full h-10 w-10 flex items-center justify-center p-0" x-on:click="modalFinalizar" title="Finalizar Atendimento">
                        <i class="bi bi-check-lg text-xl font-bold"></i>
                    </button>
                </div>
             </div>
        </div>

        <!-- Abas de Navegação -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-1 mb-4 grid grid-cols-4 gap-1">
            <button type="button" @click="tab = 0" 
                    :class="tab === 0 ? 'bg-white border-teal-200 shadow-sm' : 'bg-transparent border-transparent opacity-60'"
                    class="flex flex-col items-center justify-center py-2 px-1 rounded-xl border transition-all duration-200 group">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-1 transition-colors bg-teal-50 text-teal-600 group-hover:bg-teal-100">
                    <i class="bi bi-file-medical text-xl"></i>
                </div>
                <span class="text-[0.6rem] font-bold uppercase tracking-wide text-slate-600" :class="tab === 0 ? 'text-teal-700' : ''">Anamnese</span>
            </button>
            <button type="button" @click="tab = 1" 
                    :class="tab === 1 ? 'bg-white border-teal-200 shadow-sm' : 'bg-transparent border-transparent opacity-60'"
                    class="flex flex-col items-center justify-center py-2 px-1 rounded-xl border transition-all duration-200 group">
                 <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-1 transition-colors bg-teal-50 text-teal-600 group-hover:bg-teal-100">
                    <i class="bi bi-paperclip text-xl"></i>
                 </div>
                 <span class="text-[0.6rem] font-bold uppercase tracking-wide text-slate-600" :class="tab === 1 ? 'text-teal-700' : ''">Docs</span>
            </button>
            <button type="button" @click="tab = 2" 
                    :class="tab === 2 ? 'bg-white border-teal-200 shadow-sm' : 'bg-transparent border-transparent opacity-60'"
                    class="flex flex-col items-center justify-center py-2 px-1 rounded-xl border transition-all duration-200 group">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-1 transition-colors bg-teal-50 text-teal-600 group-hover:bg-teal-100">
                    <i class="bi bi-exclamation-triangle text-xl"></i>
                </div>
                <span class="text-[0.6rem] font-bold uppercase tracking-wide text-slate-600" :class="tab === 2 ? 'text-teal-700' : ''">Alertas</span>
             </button>
             <button type="button" @click="tab = 3" 
                    :class="tab === 3 ? 'bg-white border-teal-200 shadow-sm' : 'bg-transparent border-transparent opacity-60'"
                    class="flex flex-col items-center justify-center py-2 px-1 rounded-xl border transition-all duration-200 group">
                 <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-1 transition-colors bg-teal-50 text-teal-600 group-hover:bg-teal-100">
                    <i class="bi bi-clock-history text-xl"></i>
                 </div>
                 <span class="text-[0.6rem] font-bold uppercase tracking-wide text-slate-600" :class="tab === 3 ? 'text-teal-700' : ''">Histórico</span>
            </button>
        </div>


        {{-- Anamnese --}}
        <div x-show="(tab == 0)" x-transition.opacity>
            <form x-on:submit.prevent="saveAnamnese" class="py-1" id="formAnamnese">
                <input type="hidden" name="cd_agendamento" value="{{ $agendamento->cd_agendamento }}" />
                
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-1 mb-3">
                    <div class="bg-slate-50 px-3 py-2 border-b border-slate-100 rounded-t-xl mb-0">
                        <label class="text-slate-700 font-bold text-sm mb-0">Anamnese / Evolução</label>
                    </div>
                    <textarea class="form-control border-0" cols="30" rows="10" name="conteudo" id='editor-anamnese' required>{{ $agendamento->anamnese }}</textarea>
                </div>

                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-teal text-white font-bold rounded-xl shadow-md py-3" x-bind:disabled="loadingSaveAnamnese">
                        <template x-if="loadingSaveAnamnese">
                            <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        </template>
                        <i class="bi bi-save me-1" x-show="!loadingSaveAnamnese"></i>
                        <span>Salvar Anamnese</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Documentos --}}
        <div x-show="(tab == 1)" x-transition.opacity class="mb-3">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-3">
                <h5 class="mb-0 text-dark mb-3">Tela de Documentos</h5>
                <form x-on:submit.prevent="saveDoc" id="formDoc">
                    <input type="hidden" name="cd_agendamento" value="{{ $agendamento->cd_agendamento }}" />
                    <input type="hidden" name="cd_paciente" value="{{ $agendamento->paciente->cd_paciente }}" />

                    <div class="mb-4">
                        <div class="form-floating position-relative">
                            <input
                                type="text"
                                class="form-control rounded-xl border-slate-200 shadow-sm font-bold text-slate-700"
                                placeholder=" "
                                readonly
                                x-bind:value="docsFormularioName || 'Selecione'"
                                x-on:click="openModalModelos"
                                style="height: 60px; padding-top: 1.625rem;"
                            >
                            <label class="text-slate-500 font-bold">Escolher Modelo de Documento</label>
                            <i class="bi bi-chevron-down position-absolute end-3 top-50 translate-middle-y text-slate-400" style="margin-top: 5px;"></i>
                        </div>
                        <input type="hidden" name="cd_formulario" x-model="docsFormularioSelected">
                    </div>

                    <div class="border border-slate-200 rounded-2xl overflow-hidden mb-4">
                        <textarea class="form-control border-0" style="height: 300px;" required name="conteudo" id="editor"></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-teal text-white font-bold rounded-xl shadow-md py-3" x-bind:disabled="loadingSaveDoc">
                            <template x-if="loadingSaveDoc">
                                <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                            </template>
                            <span>Salvar Documento</span>
                        </button>
                    </div>
                </form>
            </div>

            <div x-show="docs.length > 0" class="mt-4">
                <label class="text-slate-500 font-bold text-xs uppercase mb-3 pl-1 block">Documentos desta consulta</label>
                <div class="space-y-3">
                    <template x-for="item, index in docs" x-bind:key="index">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 transition-all hover:border-teal-200">
                            <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-2">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-file-earmark-check-fill text-lg" style="background: linear-gradient(45deg, #10b981, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                    <h6 class="font-bold text-slate-800 text-sm mb-0" x-text="item.nm_formulario"></h6>
                                </div>
                                <span class="text-[0.65rem] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded" x-text="formatDate(item.created_at)"></span>
                            </div>

                            <div class="review-text text-sm text-slate-600 prose prose-sm max-w-none mb-3">
                                <div x-html="item.conteudo"></div>
                            </div>
                            
                            <div class="flex justify-end pt-2">
                                <a x-bind:href="`{{ url('rpclinica/json/imprimirDocumentoGeral') }}/`+item.cd_agendamento+`/`+item.cd_documento" 
                                     target="_blank"
                                     class="text-teal-600 bg-teal-50 hover:bg-teal-100 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1 transition-colors">
                                    <i class="bi bi-printer"></i> Imprimir
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Alerta --}}
        <div x-show="(tab == 2)" x-transition.opacity>
            <form x-on:submit.prevent="saveAlertas" class="py-1" id="formAlertas">
                <input type="hidden" name="cd_agendamento" value="{{ $agendamento->cd_agendamento }}" />
                <input type="hidden" name="cd_paciente" value="{{ $agendamento->paciente->cd_paciente }}" />
                
                <div class="bg-teal-50 rounded-2xl shadow-sm border border-teal-100 p-1 mb-3">
                    <div class="px-3 py-2 border-b border-teal-100 mb-0 flex items-center justify-between">
                        <label class="text-teal-700 font-bold text-sm mb-0 flex items-center gap-2"><i class="bi bi-exclamation-triangle-fill"></i> Alertas e Alergias</label>
                    </div>
                    <textarea class="form-control border-0 bg-transparent" cols="30" rows="10" name="conteudo" id='editor-alertas' required>{{ $agendamento->paciente->historico_problemas }}</textarea>
                </div>
                
                 <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-teal text-white font-bold rounded-xl shadow-md py-3" x-bind:disabled="loadingSaveAlertas">
                        <template x-if="loadingSaveAlertas">
                            <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        </template>
                        <i class="bi bi-save me-1" x-show="!loadingSaveAlertas"></i>
                        <span>Salvar Alertas</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Historico --}}
        <div x-show="(tab == 3)" x-transition.opacity>
            <div class="space-y-3">
                <template x-for="item, index in historico" x-bind:key="index">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-3 transition-all">
                        <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-2">
                             <h6 class="text-indigo-700 font-bold text-sm mb-0" x-text="item.nm_formulario"></h6>
                             <span class="text-[0.65rem] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded" x-text="item.data"></span>
                        </div>
                        <div class="review-text text-sm text-slate-700 prose prose-sm max-w-none">
                             <div x-html="item.conteudo"></div>
                        </div>
                         <div class="mt-2 text-right">
                             <span class="text-[0.6rem] font-bold text-slate-300 uppercase tracking-widest" x-text="item.nm_usuario"></span>
                         </div>
                    </div>
                </template>
                
                 <template x-if="historico.length == 0">
                    <div class="text-center py-8 bg-white rounded-2xl border border-dashed border-slate-200">
                        <i class="bi bi-journal-x text-3xl text-slate-300 mb-2 block"></i>
                        <span class="text-slate-400 font-bold text-sm">Sem histórico anterior</span>
                    </div>
                </template>
            </div>
        </div>


        <!-- Modal de Seleção de Modelos (Custom Picker) - Movido para fora das abas para manter escopo Alpine -->
        <template x-teleport="body">
            <div class="modal fade" id="modalMeusModelos" tabindex="-1" aria-hidden="true" style="z-index: 10006;">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content rounded-2xl border-0 shadow-2xl h-[80vh]">
                        <div class="modal-header border-b border-slate-100 py-3">
                            <h5 class="modal-title font-bold text-slate-800 text-sm">Selecione um Modelo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0 bg-slate-50">
                            <div class="list-group list-group-flush">
                                @foreach ($formularios as $formulario)
                                    <button type="button" 
                                        class="list-group-item list-group-item-action p-4 border-b border-slate-100 hover:bg-teal-50 transition-colors flex items-center justify-between group"
                                        x-on:click="selectModelo('{{ $formulario->cd_formulario }}', '{{ str_replace("'", "\'", $formulario->nm_formulario) }}')">
                                        <div class="flex items-center gap-3">
                                            <i class="bi bi-file-earmark-text-fill text-2xl" style="background: linear-gradient(45deg, #d946ef, #4f46e5); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                            <span class="font-bold text-slate-700 group-hover:text-teal-700 text-sm whitespace-normal leading-snug text-left">
                                                {{ $formulario->nm_formulario }}
                                            </span>
                                        </div>
                                        <i class="bi bi-chevron-right text-slate-300 group-hover:text-teal-400"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-teleport="body">
            <div class="modal fade" tabindex="-1" id="modalFinalizar">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-2xl border-0 shadow-lg">
                        <div class="modal-header border-0 bg-slate-50 rounded-t-2xl">
                            <h5 class="modal-title font-bold text-slate-800">Finalizar consulta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <div class="bg-teal-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="bi bi-check-lg text-3xl text-teal-600"></i>
                            </div>
                            <p class="text-slate-600 font-medium">Tem certeza que deseja finalizar este atendimento?</p>
                        </div>
                        <div class="modal-footer border-0 bg-slate-50 rounded-b-2xl justify-center pb-4">
                            <button type="button" class="btn btn-light text-slate-500 font-bold rounded-xl px-4" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" id="btnConfirmFinalizar" class="btn btn-teal text-white font-bold rounded-xl px-4 shadow-md transition-all"
                                x-on:click="finalizarConsulta">
                                <template x-if="loadingFinalizar">
                                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                </template>
                                <span>Sim, finalizar!</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
    <!--end to page content-->
@endsection

@push('scripts')
    <!-- Dados injetados para o JS -->
    <script id="data-formularios" type="application/json">
        @json($formularios)
    </script>
    <input type="hidden" id="data-cd-agendamento" value="{{ $agendamento->cd_agendamento }}">
    <input type="hidden" id="data-cd-paciente" value="{{ $agendamento->paciente->cd_paciente }}">
    <input type="hidden" id="data-initial-atendido" value="{{ $agendamento->situacao == 'atendido' ? 'true' : 'false' }}">

    <!-- Rotas da API -->
    <input type="hidden" id="route-consulta-finalizar" value="{{ route('app.api.consulta-finalizar', ['idAgendamento' => 0]) }}">
    <input type="hidden" id="route-consulta-docs" value="{{ route('app.api.consulta-docs', ['cdAgendamento' => 0]) }}">
    <input type="hidden" id="route-consulta-historico" value="{{ route('app.api.consulta-paciente-historico') }}">
    <input type="hidden" id="route-consulta-anamnese" value="{{ route('app.api.consulta-paciente-anamnese') }}">
    <input type="hidden" id="route-consulta-alertas" value="{{ route('app.api.consulta-paciente-alertas') }}">
    <input type="hidden" id="route-consulta-doc" value="{{ route('app.api.consulta-paciente-doc') }}">

    <script src="{{ asset('/js/app_rpclinica/consulta-paciente.js') }}"></script>
@endpush
