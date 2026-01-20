@extends('app_rpclinic.layout.layout')

@section('button_left')
    <div class="brand-logo">
        <a href="javascript:;"><img src="{{ asset('app/assets/images/logo_menu.svg') }}" width="190" alt=""></a>
    </div>
@endsection

@section('content')
    <!--start to page content-->
    <div class="page-content" x-data="appConsultaPaciente">

        <div class="card rounded-3 overflow-hidden">
            <div class="card-body">

                <div class="d-flex flex-row gap-3">
                    <div style="padding-left: 0.2em;" class="address-info form-check flex-grow-1">
                        <div class="d-flex flex-row align-items-start align-items-stretch gap-3">
                            <!--
                                    <div class="product-img">
                                        <img src="{{ asset('app/assets/images/user2.png') }}" class="rounded-3" width="100" alt="">
                                    </div>
                                    -->
                            <div class="product-info flex-grow-1">
                                <h6 class="fw-bold mb-0 text-dark">{{ $agendamento->paciente->nm_paciente }}</h6>
                                <div class="product-price d-flex align-items-center   mt-2">
                                    <div class="fw-light text-muted">
                                        {{ date_format(date_create($agendamento->paciente->dt_nasc), 'd/m/Y') }} -
                                        {{ idadeAluno($agendamento->paciente->dt_nasc) }}</div>
                                </div>
                                <div class="product-price d-flex align-items-center mt-2"
                                    style="margin-top: 0.1rem!important;">
                                    <div class="fw-light text-muted  ">{{ $agendamento->paciente->nm_mae }}</div>
                                </div>
                                <div class="product-price d-flex align-items-center mt-2"
                                    style="margin-top: 0.1rem!important;">
                                    <div class="fw-light text-muted  ">{{ $agendamento->paciente->nm_pai }}</div>
                                </div>
                                <div class="product-price d-flex align-items-center mt-2"
                                    style="margin-top: 0.1rem!important;">
                                    <div class="fw-light text-muted  ">{{ $agendamento->convenio->nm_convenio }}</div>
                                </div>

                                <div class="mt-2">
                                    <template x-if="atendido">
                                        <span class="badge" style="text-transform: uppercase"
                                            x-bind:class="classStatusAgendamento['atendido']">ATENDIDO</span>
                                    </template>

                                    <template x-if="!atendido">
                                        <span class="badge" style="text-transform: uppercase"
                                            x-bind:class="classStatusAgendamento['{{ $agendamento->situacao }}']">{{ $agendamento->situacao }}</span>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 align-self-start align-self-center">
                        <i style="font-size: 1.8em;" class="fa fa-check-square-o" x-on:click="modalFinalizar"></i>
                    </div>

                </div>

            </div>

            <div class="card-footer bg-transparent p-0">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-grid flex-fill">
                        <button class="btn btn-ecomm" x-bind:class="{ 'active': tab == 0 }" x-on:click="tab = 0"
                            style="height: 50px;">
                            <img style="font-size: 14px; width: 25px;"
                                src="{{ asset('app/assets/images/af_anamnese.svg') }}">
                        </button>
                    </div>
                    <div class="vr"></div>
                    <div class="d-grid flex-fill">
                        <button class="btn btn-ecomm" x-bind:class="{ 'active': tab == 1 }" x-on:click="tab = 1"
                            style="height: 50px;">
                            <img style="font-size: 14px; width: 25px;"
                                src="{{ asset('app/assets/images/af_medical-certificate.svg') }}">
                        </button>
                    </div>
                    <div class="vr"></div>
                    <div class="d-grid flex-fill">
                        <button class="btn btn-ecomm" x-bind:class="{ 'active': tab == 2 }" x-on:click="tab = 2"
                            style="height: 50px;">
                            <img style="font-size: 14px; width: 25px;"
                                src="{{ asset('app/assets/images/af_observation.svg') }}">
                        </button>
                    </div>
                    <div class="vr"></div>
                    <div class="d-grid flex-fill">
                        <button class="btn btn-ecomm" x-bind:class="{ 'active': tab == 3 }" x-on:click="tab = 3"
                            style="height: 50px;">
                            <img style="font-size: 14px; width: 25px;"
                                src="{{ asset('app/assets/images/af_attach.svg') }}">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Anamnese --}}
        <div x-show="(tab == 0)">
            <form x-on:submit.prevent="saveAnamnese" class="py-3" id="formAnamnese">
                <label>Anamnese</label>
                <input type="hidden" name="cd_agendamento" value="{{ $agendamento->cd_agendamento }}" />
                <textarea class="form-control mb-3" cols="30" rows="10" name="conteudo" id='editor-anamnese' required>{{ $agendamento->anamnese }}</textarea>
                <button type="submit" class="btn btn-ecomm rounded-3 btn-dark w-100"
                    style="height: 60px; font-weight: 600; padding: 1.2rem 1.5rem;" x-bind:disabled="loadingSaveAnamnese">
                    <template x-if="loadingSaveAnamnese">
                        <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                    </template>
                    <span>Salvar</span>
                </button>
            </form>
        </div>

        {{-- Documentos --}}
        <div x-show="(tab == 1)">
            <form x-on:submit.prevent="saveDoc" class="py-3 mb-3" id="formDoc">
                <input type="hidden" name="cd_agendamento" value="{{ $agendamento->cd_agendamento }}" />
                <input type="hidden" name="cd_paciente" value="{{ $agendamento->paciente->cd_paciente }}" />

                <div class="mb-3">
                    <div class="form-floating">
                        <select class="form-control rounded-3" required name="cd_formulario" x-model="docsFormularioSelected">
                            <option value="">Selecione</option>
                            @foreach ($formularios as $formulario)
                                <option value="{{ $formulario->cd_formulario }}">{{ $formulario->nm_formulario }}
                                </option>
                            @endforeach
                        </select>
                        <label for="floatingFirstName">Formulario</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div>  
                        <textarea class="form-control rounded-3" style="height: 150px;" required name="conteudo"
                            id="editor"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-ecomm rounded-3 btn-dark w-100"
                    style="  height: 60px; font-weight: 600; padding: 1.2rem 1.5rem;" x-bind:disabled="loadingSaveDoc">
                    <template x-if="loadingSaveDoc">
                        <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                    </template>
                    <span>Salvar</span>
                </button>
            </form>

            <label>Documentos realizados</label>
            <div>
                <template x-for="item, index in docs" x-bind:key="index">
                    <div class="review-item p-3 border rounded-3 bg-light my-3">
                        <h6 class="client-name fw-bold" x-text="item.nm_formulario"></h6>

                        <div class="review-text">
                            <div x-html="item.conteudo"></div>
                            <p class="text-end mb-0 reviw-date">
                                <a class="text-end mb-0 reviw-date" 
                                    x-bind:href="`/rpclinica/json/imprimirDocumentoGeral/`+item.cd_agendamento+`/`+item.cd_documento" 
                                     target="_blank">
                                    Imprimir</a>
                            </p>
                            <p class="text-end mb-0 reviw-date" x-text="formatDate(item.created_at)"></p>
                            <!--
                                    <p class="text-end mb-0 reviw-date" style="font-size: 1.5em;">
                                        <i class="bi bi-pencil-square me-2"></i>
                                        <i class="bi bi-file-earmark-medical me-2"></i>
                                    </p>
                                    -->
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Alerta --}}
        <div x-show="(tab == 2)">
            <form x-on:submit.prevent="saveAlertas" class="py-3" id="formAlertas">
                <label>Alertas</label>
                <input type="hidden" name="cd_agendamento" value="{{ $agendamento->cd_agendamento }}" />
                <input type="hidden" name="cd_paciente" value="{{ $agendamento->paciente->cd_paciente }}" />
                <textarea class="form-control mb-3" cols="30" rows="10" name="conteudo" id='editor-alertas' required>{{ $agendamento->paciente->historico_problemas }}</textarea>
                <button type="submit" class="btn btn-ecomm rounded-3 btn-dark w-100"
                    style="height: 60px; font-weight: 600; padding: 1.2rem 1.5rem;" x-bind:disabled="loadingSaveAlertas">
                    <template x-if="loadingSaveAlertas">
                        <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                    </template>
                    <span>Salvar</span>
                </button>
            </form>
        </div>

        {{-- Historico --}}
        <div x-show="(tab == 3)">
            <template x-for="item, index in historico" x-bind:key="index">
                <div class="review-item p-3 border rounded-3 bg-light my-3">
                    <h6 class="client-name fw-bold" x-text="item.nm_formulario"></h6>

                    <div class="review-text">
                        <div x-html="item.conteudo"></div>
                        <p class="text-end mb-0 reviw-date" x-text="item.nm_usuario"></p>
                        <p class="text-end mb-0 reviw-date" x-text="item.data"></p>
                        <!--
                                <p class="text-end mb-0 reviw-date" style="font-size: 1.5em;">
                                    <i class="bi bi-pencil-square me-2"></i>
                                    <i class="bi bi-file-earmark-medical me-2"></i>
                                </p>
                                -->
                    </div>
                </div>
            </template>
        </div>


        <div class="modal" tabindex="-1" id="modalFinalizar">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Finalizar consulta</h5>
                    </div>
                    <div class="modal-body">
                        <p>Tem certeza que quer finalizar esse agendamento?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-ecomm rounded-3 btn-dark" data-bs-dismiss="modal">Não</button>
                        <button type="button" class="btn btn-ecomm rounded-3" style="background: #009b88"
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
    </script>
    <script src="{{ asset('/js/app_rpclinica/consulta-paciente.js') }}"></script>
@endpush
