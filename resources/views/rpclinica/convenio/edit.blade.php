@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">


        <table style="width: 100%">
            <tr>
                <td style="width: 60%"> 
                    <h3>Editar de Convênio</h3>
                    <div class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li><a href="{{ route('convenio.listar') }}">Relação de Convênios</a></li>
                        </ol>
                    </div>
                </td>
                <td style="width: 40%; text-align: right; font-size: 300"> 
                    <div class="panel-body" >
                        <div class="btn-group" data-toggle="tooltip" title="Importar XLSX">
                            <button type="button" style="padding: 20px; font-style: italic;" class="btn btn-default" data-toggle="modal" data-placement="top"  data-target="#myModal" ><span aria-hidden="true" class="icon-cloud-upload"></span></button>  
                        </div>
                    </div>
                </td>
            </tr>
        </table> 

<style>

.info{
    color: #31708f;
    background:  #f2f3f9
}
</style>

        <!-- Modal XLS-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form role="form"  enctype="multipart/form-data" action="{{ route('procedimento.conv.import') }}"  method="post" role="form"> 
                @csrf   
                <input type="hidden" name="convenio" value="{{ $convenio->cd_convenio }}">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">                            
                    <span aria-hidden="true" style="padding-top: 20px;"><span aria-hidden="true"
                    class="icon-close"></span></span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ $convenio->nm_convenio }}</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                            <br>
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label for="fname" class="mat-label">Planilha XLS <span class="red normal">*</span></label>
                                <input type="file" class="form-control " required  name="xls"  aria-required="true">
                                @if ($errors->has('cod_proc'))
                                    <div class="error">{{ $errors->first('cod_proc') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
  
                      

                </div>
                <div class="modal-footer"> 

                    
                    <a href="{{ asset('assets/xls/procedimento_convenio.xlsx') }}" class="btn btn-default" style="text-align: left
                    ;"><i class="fa fa-file-excel-o"></i> Planilha Modelo</a>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-upload"></i> Importar Informações</button>
                </div> 
                </form>
            </div>
            </div>
        </div>
        <!-- Fim Modal XLS-->


    </div>
    <div id="main-wrapper" x-data="app">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h5>Houve alguns erros:</h5>

                            <ul>
                                {!! implode('', $errors->all('<li>:message</li>')) !!}
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <h5>Houve alguns erros:</h5> 
                            <ul>
                                {!! session('error') !!}
                            </ul>
                        </div>
                    @endif
  
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified m-b-lg" role="tablist">
                        <li role="presentation" @if(empty($tab)) class="active" @endif >
                            <a href="#tabCadastro" role="tab" data-toggle="tab">
                                <i class="fa fa-user m-r-xs"></i> Cadastro
                            </a>
                        </li>

                        <li role="presentation" class="">
                            <a href="#tabProcedimento" role="tab" data-toggle="tab">
                                <i class="fa fa-pencil-square-o m-r-xs"></i> Procedimentos
                            </a>
                        </li>

                        <li role="presentation"  @if($tab=='RE') class="active" @endif >
                            <a href="#tabRepasse" role="tab" data-toggle="tab">
                                <i class="fa fa-users m-r-xs"></i> Repasse
                            </a>
                        </li>
  
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" @if(empty($tab)) class="tab-pane fade in active" @else class="tab-pane fade" @endif  id="tabCadastro">
                            <form role="form" id="formConvenio" action="{{ route('convenio.update', ['convenio' => $convenio->cd_convenio]) }}" method="post"
                                role="form">
                                @csrf
                                <button type="submit" style="display: none"></button>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nome: <span class="red normal">*</span></label>
                                            <input type="text" class="form-control required" value="{{ $convenio->nm_convenio }}"
                                                name="nome" maxlength="100" aria-required="true" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tipo: <span class="red normal">*</span></label>
                                            <select name="convenio" class="form-control" style="width: 100%;" required>
                                                <option value="">SELECIONE</option>
                                                <option @if ($convenio->tp_convenio == 'CO') selected @endif value="CO">CONVENIO</option>
                                                <option @if ($convenio->tp_convenio == 'SUS') selected @endif value="SUS">SUS</option>
                                                <option @if ($convenio->tp_convenio == 'PA') selected @endif value="PA">PARTICULAR</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>CNPJ: </label>
                                            <input x-mask="99.999.999/9999-99" class="form-control" value="{{ $convenio->cnpj }}"
                                                name="cnpj" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Prazo de Retorno: </label>
                                            <input type="number" class="form-control" value="{{ old('prazo_retorno',$convenio->prazo_retorno) }}"
                                                name="prazo_retorno" maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Registro ANS da Operadora: </label>
                                            <input type="text" class="form-control " name="ans"
                                                maxlength="100" aria-required="true" value="{{ $convenio->registro_ans }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Prazo das Guias: </label>
                                            <input type="number" class="form-control " name="prazo_guia"
                                                maxlength="100" aria-required="true" value="{{ $convenio->prazo_guia }}">
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label>Endereço: </label>
                                            <input type="text" class="form-control " value="{{ $convenio->endereco }}" name="endereco"
                                                maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email: </label>
                                            <input type="email" class="form-control " value="{{ $convenio->email }}" name="email"
                                                maxlength="100" aria-required="true">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Data Contrato: </label>
                                            <input type="date" class="form-control " value="{{ $convenio->dt_contrato }}" name="data_contrato"
                                                maxlength="100" aria-required="true">
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Telefone: </label>
                                            <input type="tel" class="form-control " value="{{ $convenio->telefone }}" name="telefone"
                                                maxlength="100" aria-required="true" x-mask="(99)9999-9999">
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fatura Sancoop: <span class="red normal">*</span></label>
                                            <select name="faturasancoop" class="form-control" style="width: 100%;" required>
                                                <option value="">SELECIONE</option>
                                                <option @if ($convenio->sn_sancoop == 'S') selected @endif value="S">SIM</option>
                                                <option @if ($convenio->sn_sancoop == 'N') selected @endif value="N">NÃO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Link da Plataforma: </label>
                                            <input type="tel" class="form-control " value="{{ old('link_autorizacao',$convenio->link_autorizacao) }}" name="link_autorizacao"
                                                maxlength="255" aria-required="true"  >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Usuário da Plataforma: </label>
                                            <input type="tel" class="form-control " value="{{ old('user_autorizacao',$convenio->user_autorizacao) }}" name="user_autorizacao"
                                                maxlength="255" aria-required="true"  >
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Senha da Plataforma: </label>
                                            <input type="tel" class="form-control " value="{{ old('senha_autorizacao',$convenio->senha_autorizacao) }}" name="senha_autorizacao"
                                                maxlength="255" aria-required="true"  >
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Observação</label> 
                                        <textarea rows="4" class="form-control" name="obs" >{{ old('obs',$convenio->obs) }}</textarea>
                                    </div>
                                </div>
                                <br> 

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Financeiro</h3>
                                    </div>
                                    <div class="panel-body">
                                        <br>
                                        <div class="row">
                              
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label>Cliente: <span class="red normal"></span></label>
                                                    <select name="cliente" class="form-control" style="width: 100%;"  >
                                                        <option value="">SELECIONE</option>
                                                        @foreach ($fornecedor as $forn)
                                                            <option value="{{ $forn->cd_fornecedor }}"
                                                                @if(old('cliente',$convenio->cd_fornecedor)==$forn->cd_fornecedor) selected  @endif 
                                                                >
                                                                {{  $forn->nm_fornecedor }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('cliente'))
                                                    <div class="error">{{ $errors->first('cliente') }}</div>
                                                    @endif
                                                </div>
                                            </div> 
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Categoria: <span class="red normal"></span></label>
                                                    <select name="categoria" class="form-control" style="width: 100%;"   >
                                                        <option value="">SELECIONE</option>
                                                        @foreach ($categoria as $linha)
                                                            <option value="{{ $linha->cd_categoria }}"
                                                                @if(old('categoria',$convenio->cd_categoria)==$linha->cd_categoria) selected  @endif 
                                                                >
                                                                {{  $linha->nm_categoria }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('categoria'))
                                                    <div class="error">{{ $errors->first('categoria') }}</div>
                                                    @endif
                                                </div>
                                            </div> 
                                            <!--
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Conta: <span class="red normal"></span></label>
                                                    <select name="conta" class="form-control"  >
                                                        <option value="">SELECIONE</option>
                                                        @foreach ($conta as $linha)
                                                            <option value="{{ $linha->cd_conta }}"
                                                                @if(old('conta',$convenio->cd_conta)==$linha->cd_conta) selected  @endif 
                                                            >
                                                                {{  $linha->nm_conta }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('conta'))
                                                    <div class="error">{{ $errors->first('conta') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Setor: <span class="red normal"></span></label>
                                                    <select name="setor" class="form-control"  >
                                                        <option value="">SELECIONE</option>
                                                        @foreach ($setor as $linha)
                                                            <option value="{{ $linha->cd_setor }}"
                                                                @if(old('setor',$convenio->cd_setor)==$linha->cd_setor) selected  @endif 
                                                                >
                                                                {{  $linha->nm_setor }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('setor'))
                                                    <div class="error">{{ $errors->first('setor') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Forma de Recebimento: <span class="red normal"></span></label>
                                                    <select name="forma" class="form-control"  >
                                                        <option value="">SELECIONE</option>
                                                        @foreach ($forma as $linha)
                                                            <option value="{{ $linha->cd_forma_pag }}"
                                                                @if(old('forma',$convenio->cd_forma_pag)==$linha->cd_forma_pag) selected  @endif 
                                                                >
                                                                {{  $linha->nm_forma_pag }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('forma'))
                                                    <div class="error">{{ $errors->first('forma') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Marca: <span class="red normal"></span></label>
                                                    <select name="marca" class="form-control"  >
                                                        <option value="">SELECIONE</option>
                                                        @foreach ($marca as $linha)
                                                            <option value="{{ $linha->cd_marca }}"
                                                            @if(old('marca',$convenio->cd_marca)==$linha->cd_marca) selected  @endif    
                                                            >
                                                                {{  $linha->nm_marca }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('marca'))
                                                    <div class="error">{{ $errors->first('marca') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            -->

                                        </div>

                                    </div>
                                </div>

                                <template x-for="entrada, indice in entradasProcedimentos">
                                    <template x-if="!entrada.cd_procedimento_convenio">
                                        <div>
                                            <input type="hidden" x-bind:name="`procedimentos[${indice}][cd_procedimento]`"
                                                x-bind:value="entrada.codigo" />
                                            <input type="hidden" x-bind:name="`procedimentos[${indice}][dt_vigencia]`"
                                                x-bind:value="entrada.dt_vigencia" />
                                            <input type="hidden" x-bind:name="`procedimentos[${indice}][valor]`"
                                                x-bind:value="entrada.valor" />
                                        </div>
                                    </template>
                                </template>
                            </form>
                        </div>

                        <div role="tabpanel" class="tab-pane fade" id="tabProcedimento">
                            <form x-on:submit.prevent="addEntradaProcedimento" x-ref="formProcedimento">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="mat-label">Procedimento <span
                                                    class="red normal">*</span></label>
                                            <select id="procedimento" name="procedimento"
                                                class="js-states form-control select2-hidden-accessible" tabindex="-1"
                                                style="display: none; width: 100%" aria-hidden="true" required>
                                                <option value="">SELECIONE</option>
                                                @foreach ($procedimentos as $procedimento)
                                                    <option value="{{ $procedimento->cd_proc }}" data-codigo="{{ $procedimento->cod_proc }}"
                                                        data-nome="{{ $procedimento->nm_proc }}" >
                                                        {{ $procedimento->cod_proc.' - '. $procedimento->nm_proc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Data da Vigência </label>
                                                <input type="date" class="form-control" id="valor" value=""
                                                    name="valor" maxlength="20" aria-required="true"
                                                    placeholder="Valor" x-model="formProcedimento.dt_vigencia">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Valor </label>
                                                <input x-mask:dynamic="$money($input, ',')" class="form-control"
                                                    id="valor" value="" name="valor" maxlength="20"
                                                    aria-required="true" placeholder="Valor"
                                                    x-model="formProcedimento.valor">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <input class="btn btn-success" value="Adicionar" type="submit"
                                            style="margin-top: 24px" />
                                    </div>

                                    
                                </div>
                            </form>

                            <div class="table-responsive">
                                <div x-show="deletingEntradaProcedimento">
                                    <x-loading message="Excluindo item..." />
                                </div>

                                <table class="display table dataTable table-striped">
                                    <thead>
                                        <tr>
                                            <th>Procedimento</th>
                                            <th>Descrição</th>
                                            <th>Data da Vigência</th>
                                            <th>Situação</th>
                                            <th class="text-right" >Valor</th>
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody id="lista-de-procedimento">

                                        <template x-for="entrada, indice in entradasProcedimentos">
                                            <tr>
                                                <td style="padding: 1px !important;" x-text="entrada.cd_procedimento"></td>
                                                <td style="padding: 1px !important;" x-text="nomeProcedimento(entrada.cd_procedimento)"></td>
                                                <td style="padding: 1px !important;" x-text="entrada.dt_vigencia ? formatDate(entrada.dt_vigencia): ''"></td>
                                                <td style="padding: 1px !important;" x-html=" (entrada.sn_ativo=='N') ? `<code>Inativo</code>` : `<code class='info'>Ativo</code>` ">Valor</td>
                                                <td style="padding: 1px !important;" class="text-right" x-text="(entrada.valor_formatado) ? entrada.valor_formatado : entrada.valor">Valor</td>
                                                <td style="padding: 1px !important;" class="text-center">
                                                    <div class="btn-group">
                                                        <button class="btn btn-danger"
                                                            x-on:click="excluirEntradaProcedimento(indice)">
                                                            <i class="fa fa-trash"></i>
                                                        </button> 
                                                    </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>

                                        <template x-if="entradasProcedimentos.length == 0">
                                            <tr>
                                                <td colspan="6" class="text-center">Nenhum procedimento</td>
                                            </tr>
                                        </template>

                                    </tbody>
                                </table>
   
                            </div>
                        </div>
 
                        <div role="tabpanel" @if($tab=='RE') class="tab-pane fade in active" @else class="tab-pane fade" @endif id="tabRepasse">
                            <form action="{{ route('convenio.repasse', ['convenio' => $convenio->cd_convenio]) }}" method="post" >
                                @csrf   
                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="mat-label">Profissional <span
                                                    class="red normal">*</span></label>
                                            <select   name="profissional"
                                                class=" form-control "  style="display: none; width: 100%" aria-hidden="true" required>
                                                <option value="">Selecione</option>
                                                @foreach ($profissional as $prof)
                                                    <option value="{{ $prof->cd_profissional }}" >
                                                        {{ $prof->nm_profissional }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="mat-label">Procedimento <span  class="red normal">*</span></label>
                                            <select name="procedimento" class=" form-control " tabindex="-1"
                                                style="display: none; width: 100%" aria-hidden="true" required>
                                                <option value="">Selecione</option>
                                                @foreach ($procedimentos as $procedimento)
                                                    <option value="{{ $procedimento->cod_proc }}"   >
                                                        {{ $procedimento->cod_proc.' - '. $procedimento->nm_proc }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label class="mat-label">Tipo <span  class="red normal">*</span></label>
                                            <select name="tipo" class="form-control " tabindex="-1"
                                                style="display: none; width: 100%"  required>
                                                <option value="">...</option>
                                                <option value="%">%</option>
                                                <option value="=">Real</option>
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="mat-div">
                                                <label>Valor </label>
                                                <input x-mask:dynamic="$money($input, ',')" class="form-control"
                                                    id="valor" value="" name="valor" maxlength="20"
                                                    aria-required="true" placeholder="Valor"
                                                    x-model="formProcedimento.valor">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-1"> 
                                            <button type="submit" style="margin-top: 24px;width: 100%" class="btn btn-success" >
                                               Salvar
                                            </button>
                                    </div>

                                    
                                </div>
                            </form>

                            <table class="display table dataTable table-striped">
                                <thead>
                                    <tr>
                                        <th>Profissional</th>
                                        <th>Procedimento</th>
                                        <th>Tipo</th>
                                        <th style="text-align: right">Valor</th>
                                        <th class="text-center">Situação</th> 
                                        <th class="text-center">Ação</th>
                                    </tr>
                                </thead>
                                <tbody  >
                                    @foreach($repasse as $key => $val)
                                    <tr>
                                        <td style="padding: 1px !important;"  >{{ $val->profissional?->nm_profissional }}</td>
                                        <td style="padding: 1px !important;" >{{ $val->procedimento?->cod_proc .' - '.  $val->procedimento?->nm_proc }}</td>
                                        <td style="padding: 1px !important;" >
                                            @if($val->tipo=='%') Porcentagem @endif
                                            @if($val->tipo=='=') Real @endif
                                        </td>
                                        <td style="padding: 1px !important; text-align: right" >{{  ($val->valor) ? number_format($val->valor, 2, ',', '.') : null }}</td>
                                        <td style="padding: 1px !important;" class="text-center" >
                                            @if($val->sn_ativo=='S') Ativo @endif
                                            @if($val->tipo=='N') <code> Inativo </code> @endif
                                        </td>
                                        <td style="padding: 1px !important;" class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-danger" x-on:click="deleteRepasse('{{$val->cd_procedimento_repasse}}')">
                                                    <i class="fa fa-trash"></i>
                                                </button> 
                                            </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
  

                                    

                                </tbody>
                            </table>

                        </div>

                        
                    </div>
                </div>
 
                <div class="panel-footer">
                    <input type="submit" class="btn btn-success" value="Salvar" x-on:click="submitConvenio" />
                    <input type="reset" class="btn btn-default" value="Limpar" x-on:click="limpar" />
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const procedimentos = @js($procedimentos);
        const entradasProcedimentos = @js($convenio->procedimentosConvenio);
    </script>
    <script src="{{ asset('js/rpclinica/convenio.js') }}"></script>
@endsection
