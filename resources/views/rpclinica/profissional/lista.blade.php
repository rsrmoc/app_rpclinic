@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">
        <h3>Relação de Profissionais</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Relação</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel glass-panel" style="background-color: rgba(30, 41, 59, 0.45) !important; border: 1px solid rgba(255,255,255,0.1) !important;"><br>
                <div class="panel-heading clearfix" style="padding-bottom: 4px;">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-7">
                            <h4 class="panel-title"> </h4>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-5">
                            <form method="GET" id="searchList">
                                <div class="input-group m-b-sm">
                                    <input type="text" name="b" class="form-control"
                                        placeholder="Pesquisar por ID, código ou nome...">
                                    <span class="input-group-btn">
                                        <a href="{{ route('profissional.create') }}"
                                            class="btn btn-success btn-addon m-b-sm"><span class="item"><span
                                            aria-hidden="true" class="icon-note"></span>&nbsp;Novo</span></a>
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
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>RG</th>
                                    <th>Conselho</th>
                                    <th>Situação</th>
                                    <th class="text-center" style="width: 80px;">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($profissionais as $profissional)
                                    <tr id="prof-{{ $profissional->cd_profissional }}">
                                        <th>{{ $profissional->cd_profissional }}</th>
                                        <td>{{ $profissional->nm_profissional }}</td>
                                        <td>{{ $profissional->doc }}</td>
                                        <td>{{ $profissional->rg }}</td>
                                        <td>{{ $profissional->nr_conselho }}</td>
                                   
                                        <td>
                                            <span class="{{ CSS_SN_ATIVO[$profissional->sn_ativo] }}">{{ SN_ATIVO[$profissional->sn_ativo] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('profissional.edit', ['profissional' => $profissional->cd_profissional]) }}" class="btn btn-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <button onclick="delete_cadastro('{{ route('profissional.delete', ['profissional' => $profissional->cd_profissional]) }}', '#prof-{{ $profissional->cd_profissional }}')"
                                                    class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- Main Wrapper -->
@endsection

@section('script')
    <script>
        function carregar_delete(cod) {
            Swal.fire({
                title: 'Confirmação',
                text: "Tem certeza que deseja excluir esse cadastro?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Não',
                confirmButtonText: 'Sim'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("dadas", {
                            cod: cod
                        },
                        function(pegar_dados) {
                            $("#" + cod).remove();
                            Swal.fire(
                                'Deletado!',
                                'Cadastro deletado com sucesso!.',
                                'success');
                        }
                    );
                }
            });
        }
    </script>
@endsection
