@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Relação de Motivos</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('motivo.listar') }}">Relação de motivos</a></li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper" x-data="app">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-heading clearfix" style="padding-bottom: 4px;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-7">
                            <h4 class="panel-title"> </h4>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-5">
                            <form method="GET" id="searchList">
                                <div class="input-group m-b-sm">
                                    <input type="text" name="b" class="form-control" required
                                        placeholder="Pesquisar por ID, código ou nome..." value="{{ request()->query('b') }}">

                                    <span class="input-group-btn">
                                        <button type="button" href="#" class="btn btn-success btn-addon m-b-sm"
                                            x-on:click="openModalMotivo()">
                                            <span class="item">
                                                <span aria-hidden="true" class="icon-note"></span>&nbsp;Novo
                                            </span>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="display table dataTable table-striped">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Motivo</th>
                                    <th class="text-center" style="width: 80px;">Ação</th>
                                </tr>
                            </thead>

                            <tbody>
                                <template x-for="motivo in motivos">
                                    <tr x-bind:id="`formulario-${motivo.cd_motivo}`">
                                        <th x-text="motivo.cd_motivo"></th>

                                        <td x-text="motivo.motivo"></td>

                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button x-on:click="openModalMotivo(motivo)" class="btn btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button
                                                    x-on:click="delete_cadastro(`motivo-delete/${motivo.cd_motivo}`, `#formulario-${motivo.cd_motivo}`)"
                                                    class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <form class="modal" id="modal-form-motivo" x-on:submit.prevent="saveMotivo">
            <div class="modal-dialog">
                <div x-show="loadingModal">
                    <x-absolute-loading message="Salvando..." />
                </div>

                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <h4 class="modal-title" x-text="`${editionMotivo !== null ? 'Editar': 'Adicionar'} motivo`"></h4>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Motivo</label>
                        <input class="form-control" maxlength="255" x-model="inputMotivo" required />
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Salvar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const motivos = @js($motivos);
    </script>
    <script src="{{ asset('js/rpclinica/motivo.js') }}"></script>
@endsection
