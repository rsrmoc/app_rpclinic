@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="d-flex align-items-center gap-3">
        <div class="brand-logo" style="width: auto;">
            <a href="javascript:;" class="d-flex justify-content-center align-items-center">
                <img src="{{ asset('assets/images/logo_menu.svg') }}" 
                     alt="Logo" 
                     style="height: 60px; width: auto;" 
                     class="">
            </a>
        </div>
        <div class="border-start border-slate-300 h-6 mx-1"></div>
        <h6 class="mb-0 text-slate-700 font-bold uppercase tracking-tight">Atendimento</h6>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appConsultaPaciente" style="padding-bottom: 80px;">

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
                    <button type="button" class="btn btn-sm btn-icon text-slate-400 hover:text-emerald-500 transition-colors" x-on:click="modalFinalizar" title="Finalizar Atendimento">
                        <i class="bi bi-check-circle text-2xl"></i>
                    </button>
                </div>
             </div>
        </div>

        <!-- Abas de Navegação -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-1 mb-4 grid grid-cols-4 gap-1">
            <button @click="tab = 0" 
                    :class="tab === 0 ? 'bg-teal-50 text-teal-700 shadow-sm border-teal-100' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600 border-transparent'"
                    class="flex flex-col items-center justify-center py-2 px-1 rounded-lg border transition-all duration-200">
                <i class="bi bi-file-medical text-lg mb-1"></i>
                <span class="text-[0.6rem] font-bold uppercase tracking-wide">Anamnese</span>
            </button>
            <button @click="tab = 1" 
                    :class="tab === 1 ? 'bg-teal-50 text-teal-700 shadow-sm border-teal-100' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600 border-transparent'"
                    class="flex flex-col items-center justify-center py-2 px-1 rounded-lg border transition-all duration-200">
                 <i class="bi bi-paperclip text-lg mb-1"></i>
                 <span class="text-[0.6rem] font-bold uppercase tracking-wide">Docs</span>
            </button>
            <button @click="tab = 2" 
                    :class="tab === 2 ? 'bg-rose-50 text-rose-600 shadow-sm border-rose-100' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600 border-transparent'"
                    class="flex flex-col items-center justify-center py-2 px-1 rounded-lg border transition-all duration-200">
                <i class="bi bi-exclamation-triangle text-lg mb-1"></i>
                <span class="text-[0.6rem] font-bold uppercase tracking-wide">Alertas</span>
             </button>
             <button @click="tab = 3" 
                    :class="tab === 3 ? 'bg-indigo-50 text-indigo-600 shadow-sm border-indigo-100' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600 border-transparent'"
                    class="flex flex-col items-center justify-center py-2 px-1 rounded-lg border transition-all duration-200">
                 <i class="bi bi-clock-history text-lg mb-1"></i>
                 <span class="text-[0.6rem] font-bold uppercase tracking-wide">Histórico</span>
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

                <div class="fixed bottom-4 left-0 right-0 px-4 z-10 flex justify-center">
                    <button type="submit" class="shadow-lg shadow-teal-900/20 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-full px-6 py-3 w-full max-w-sm flex items-center justify-center gap-2 transition-all transform active:scale-95" x-bind:disabled="loadingSaveAnamnese">
                        <template x-if="loadingSaveAnamnese">
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        </template>
                        <i class="bi bi-save" x-show="!loadingSaveAnamnese"></i>
                        <span>Salvar Anamnese</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Documentos --}}
        <div x-show="(tab == 1)" x-transition.opacity>
            <form x-on:submit.prevent="saveDoc" class="py-1 mb-3" id="formDoc">
                <input type="hidden" name="cd_agendamento" value="{{ $agendamento->cd_agendamento }}" />
                <input type="hidden" name="cd_paciente" value="{{ $agendamento->paciente->cd_paciente }}" />

                <div class="mb-4">
                    <label class="text-slate-700 font-bold text-sm mb-2 block">Modelo de Documento</label>
                    <select class="form-select w-full rounded-xl border-slate-200 text-slate-700 font-bold shadow-sm h-12" required name="cd_formulario" x-model="docsFormularioSelected">
                        <option value="">Selecione um modelo...</option>
                        @foreach ($formularios as $formulario)
                            <option value="{{ $formulario->cd_formulario }}">{{ $formulario->nm_formulario }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-1 mb-3" x-show="docsFormularioSelected">
                    <textarea class="form-control border-0" style="height: 300px;" required name="conteudo" id="editor"></textarea>
                </div>

                <div class="d-grid mt-3" x-show="docsFormularioSelected">
                    <button type="submit" class="btn btn-primary bg-teal-600 border-teal-600 text-white font-bold rounded-xl shadow-md py-3" x-bind:disabled="loadingSaveDoc">
                        <template x-if="loadingSaveDoc">
                            <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        </template>
                        <span>Salvar Documento</span>
                    </button>
                </div>
            </form>

            <div x-show="docs.length > 0" class="mt-4">
                <label class="text-slate-500 font-bold text-xs uppercase mb-3 pl-1 block">Documentos desta consulta</label>
                <div class="space-y-3">
                    <template x-for="item, index in docs" x-bind:key="index">
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 transition-all hover:border-teal-200">
                            <div class="flex justify-between items-center mb-2 border-b border-slate-100 pb-2">
                                <h6 class="font-bold text-slate-800 text-sm mb-0" x-text="item.nm_formulario"></h6>
                                <span class="text-[0.65rem] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded" x-text="formatDate(item.created_at)"></span>
                            </div>

                            <div class="review-text text-sm text-slate-600 prose prose-sm max-w-none mb-3">
                                <div x-html="item.conteudo"></div>
                            </div>
                            
                            <div class="flex justify-end pt-2">
                                <a x-bind:href="`/rpclinica/json/imprimirDocumentoGeral/`+item.cd_agendamento+`/`+item.cd_documento" 
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
                
                <div class="bg-rose-50 rounded-2xl shadow-sm border border-rose-100 p-1 mb-3">
                    <div class="px-3 py-2 border-b border-rose-100 mb-0 flex items-center justify-between">
                        <label class="text-rose-700 font-bold text-sm mb-0 flex items-center gap-2"><i class="bi bi-exclamation-triangle-fill"></i> Alertas e Alergias</label>
                    </div>
                    <textarea class="form-control border-0 bg-transparent" cols="30" rows="10" name="conteudo" id='editor-alertas' required>{{ $agendamento->paciente->historico_problemas }}</textarea>
                </div>
                
                 <div class="fixed bottom-4 left-0 right-0 px-4 z-10 flex justify-center">
                    <button type="submit" class="shadow-lg shadow-rose-900/20 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-full px-6 py-3 w-full max-w-sm flex items-center justify-center gap-2 transition-all transform active:scale-95" x-bind:disabled="loadingSaveAlertas">
                        <template x-if="loadingSaveAlertas">
                            <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                        </template>
                        <i class="bi bi-shield-check" x-show="!loadingSaveAlertas"></i>
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
                        <button type="button" class="btn bg-teal-600 text-white font-bold rounded-xl px-4 hover:bg-teal-700 shadow-md transition-all"
                            x-bind:disabled="loadingFinalizar"
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
    </div>
    <!--end to page content-->
@endsection

@push('scripts')
    <script>
        const cdAgendamento = {{ $agendamento->cd_agendamento }};
        const cdPaciente = '{{ $agendamento->paciente->cd_paciente }}';
        const formularios = @js($formularios);
        
        const routeConsultaFinalizar = @js(url('app_rpclinic/api/consulta-finalizar'));
        const routeConsultaDocs = @js(url('app_rpclinic/api/consulta-docs'));
        
        const routeConsultaHistorico = @js(url('app_rpclinic/api/consulta-paciente-historico'));
        const routeConsultaAnamnese = @js(url('app_rpclinic/api/consulta-paciente-anamnese'));
        const routeConsultaAlertas = @js(url('app_rpclinic/api/consulta-paciente-alertas'));
        const routeConsultaDoc = @js(url('app_rpclinic/api/consulta-paciente-doc'));
    </script>
    <script src="{{ asset('/js/app_rpclinica/consulta-paciente.js') }}"></script>
@endpush
