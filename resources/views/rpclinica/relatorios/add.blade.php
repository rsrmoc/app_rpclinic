@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Criação de Relatórios</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="index-2.html">Reports</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper" x-data="add_relatorios">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @error('record')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form role="form" id="addUser" method="post"  role="form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-md-offset-1">
                                <div class="form-group">
                                    <label for="titulo" class="mat-label">Título <span class="red normal">*</span></label>
                                    <input type="text" class="form-control" required="" name="titulo">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="area" class="mat-label">Área <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="area">
                                        <option value="">Selecione</option>
                                        <option value="CO">Consultório</option>
                                        <option value="FI">Financeiro</option>
                                        <option value="ES">Estoque</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-1">
                                <div class="form-group">
                                    <label for="conteudo" class="mat-label">Conteudo <span class="red normal">*</span></label>
                                    <select x-on:change="getConteudoView()" class="form-control" required="" name="conteudo">
                                        <option value="">Selecione</option>
                                        @foreach ($views as $view)
                                            <option value="{{ $view->view }}">{{ $view->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="tipo_relatorio" class="mat-label">Tipo de Relatório <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="tipo_relatorio">
                                        <option value="">Selecione</option>
                                        <option value="REL">Relatório HTML</option>
                                        <option value="XLS">Relatório XLS</option>
                                        <option value="PDF">Relatório PDF</option>
                                        <option value="GCOL">Grafico Coluna</option> 
                                        <option value="GPIZ">Grafico Pizza</option> 
                                        <!--<option value="GCOLC">Grafico Coluna Comparativo</option> -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="layout" class="mat-label">Layout <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="layout">
                                        <option value="">Selecione</option>
                                        <option value="R">Retrato</option>
                                        <option value="P">Paissagem</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="layout" class="mat-label">Restrição <span class="red normal"></span></label>
                                    <select class="form-control"  name="restricao">
                                        <option value="">Selecione</option>
                                        <option value="PROF">Usuario Logado [ Profisional ]</option> 
                                    </select>
                                </div>
                            </div>
                        </div>
 

                        <div  role="tabpanel">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tabEscala" role="tab" data-toggle="tab" style="border-bottom: 0px;" aria-expanded="true">Campos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#tabManual" role="tab" data-toggle="tab" style="border-bottom: 0px;"aria-expanded="true">Parametros</a>
                                </li>
                                <li role="presentation">
                                    <a href="#tabCalculo" role="tab" data-toggle="tab" style="border-bottom: 0px;"aria-expanded="true">Calculos</a>
                                </li>
                                <li role="presentation">
                                    <a href="#tabOrdenar" role="tab" data-toggle="tab" style="border-bottom: 0px;"aria-expanded="true">Ordenar</a>
                                </li>
                                <!-- <li role="presentation">
                                    <a href="#tabVisu" role="tab" data-toggle="tab" style="border-bottom: 0px;"aria-expanded="true">Pre-Visualização</a>
                                </li> -->

                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active fade in" id="tabEscala">


                                        <div class="row">
                                            <div class="col-md-3 ">
                                                <div class="form-group">
                                                    <label for="c_campos" class="mat-label">Campos da Tabela<span class="red normal">*</span></label>
                                                    <select class="form-control" required="" name="c_campos">
                                                        <option value="">Selecione</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 " style="text-align:  "  >
                                                <div class="form-group">
                                                    <label for="fname" class="mat-label">Alinhamento<span class="red normal">*</span></label>
                                                    <select class="form-control" required="" name="c_alinhamento">
                                                        <option value="">Selecione</option>
                                                        <option value="center">Center</option>
                                                        <option value="left">Left</option>
                                                        <option value="right">Right</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 " style="text-align:  "  >
                                                <div class="form-group">
                                                    <label for="fname" class="mat-label">Máscara<span class="red normal">*</span></label>
                                                    <select class="form-control" required="" name="c_mascara">
                                                        <option value="">Selecione</option>
                                                        <option value="dt">Data</option>
                                                        <option value="dthr">Data e Hora</option>
                                                        <option value="hr">Hora</option>
                                                        <option value="mo">Moeda</option>
                                                        <option value="in">Inteiro</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for="fname" class="mat-label">Limite [Carac.] </label>
                                                    <input type="text" class="form-control" required="" name="c_limite">
                                                </div>
                                            </div>
                                            <div class="col-md-1" style="padding-top: 22px;">
                                                <input type="button"  class="btn btn-default" value="Inserir" x-on:click="insertCampos();" />
                                            </div>

                                        </div>

                                        <div class="table-responsive">
                                            <table class="display table dataTable table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Dados do Campo</th>
                                                        <th>Alinhamento</th>
                                                        <th>Máscara</th>
                                                        <th>Limite</th>
                                                        <th class="text-center">Ação</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-campos"></tbody>
                                            </table>
                                        </div>


                                </div>

                                <div role="tabpanel" class="tab-pane " id="tabManual">

                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <label for="fname" class="mat-label">Campos da Tabela<span class="red normal">*</span></label>
                                                <select class="form-control" required="" name="p_campos" style="width: 100%">
                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <label for="fname" class="mat-label">Operador<span class="red normal">*</span></label>
                                                <select class="form-control" required="" name="p_operador" style="width: 100%">
                                                    <option value="">Selecione</option>
                                                    <option value="=">=</option>
                                                    <option value="<>"><></option>
                                                    <option value="<"><</option>
                                                    <option value="<="><=</option>
                                                    <option value=">">></option>
                                                    <option value=">=">>=</option>
                                                    <option value="between">Entre [ between ]</option>
                                                    <option value="in">Na Lista [ in ]</option>
                                                    <option value="not_in">Não esta na lista[ not in ]</option>
                                                    <option value="is_null">Vazio [ is null ]</option>
                                                    <option value="is_not_null">Não Vazio [ is not null ]</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 ">
                                            <div class="form-group">
                                                <label for="fname" class="mat-label">Campo obrigatório<span class="red normal">*</span></label>
                                                <select class="form-control" required="" name="p_obrigatorio" style="width: 100%">
                                                    <option value="">Selecione</option>
                                                    <option value="S">Sim</option>
                                                    <option value="N">Não</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <label for="fname" class="mat-label">Parametros Padrão<span class="red normal"></span></label>
                                                <select class="form-control" name="p_padrao"style="width: 100%">
                                                    <option value="">Selecione</option>
                                                    <option value="cd_profissional">Profissional</option>
                                                    <option value="cd_exame">Exames</option>
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-1" style="padding-top: 22px;">
                                            <input type="button"  class="btn btn-default" value="Inserir" x-on:click="insertParametros();" />
                                        </div>
                                    </div>
                           

                                    <div class="table-responsive">
                                        <table class="display table dataTable table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Dados do Campo</th>
                                                    <th>Operador</th>
                                                    <th>Obrigatório</th>
                                                    <th>Campo Padrão</th>
                                                    <th class="text-center">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody-parametros"></tbody>
                                        </table>
                                    </div>

                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tabCalculo">

                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <label for="ca_campos" class="mat-label">Campos da Tabela<span class="red normal">*</span></label>
                                                <select class="form-control" required="" name="ca_campos" style="width: 100%">
                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <label for="ca_funcao" class="mat-label">Função<span class="red normal">*</span></label>
                                                <select class="form-control" required="" name="ca_funcao" style="width: 100%">
                                                    <option value="">Selecione</option>
                                                    <option value="sum">Somar</option>
                                                    <option value="count">Contar</option>
                                                    <option value="max">Máximo</option>
                                                    <option value="min">Minimo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1" style="padding-top: 22px;">
                                            <input type="button"  class="btn btn-default" value="Inserir" x-on:click="insertCalculos();" />
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="display table dataTable table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Dados do Campo</th>
                                                    <th>Função</th>
                                                    <th class="text-center">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody-calculos"></tbody>
                                        </table>
                                    </div>

                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="tabOrdenar">

                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <label for="fname" class="mat-label">Campos da Tabela<span class="red normal">*</span></label>
                                                <select class="form-control" required="" name="o_campos" style="width: 100%">
                                                    <option value="">Selecione</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 ">
                                            <div class="form-group">
                                                <label for="fname" class="mat-label">Tipo<span class="red normal">*</span></label>
                                                <select class="form-control" required="" name="o_tipo" style="width: 100%">
                                                    <option value="">Selecione</option>
                                                    <option value="asc">Crescente</option>
                                                    <option value="desc">Decrescente</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-1" style="padding-top: 22px;">
                                            <input type="button"  class="btn btn-default" value="Inserir" x-on:click="insertOrdenar()" />
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="display table dataTable table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Dados do Campo</th>
                                                    <th>Tipo</th>
                                                    <th class="text-center">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody-ordenar"></tbody>
                                        </table>
                                    </div>

                                </div>
                                <!-- <div role="tabpanel" class="tab-pane fade" id="tabVisu">

                                </div> -->




                            </div>
                        </div>


                        <div class="box-footer">
                            <input type="button" class="btn btn-success" value="Salvar" x-on:click="createRelatorio()" />
                            <input type="reset" class="btn btn-default" value="Limpar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/rpclinica/relatorios-add.js') }}"></script>
@endsection
