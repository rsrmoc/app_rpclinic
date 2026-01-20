@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Edição de Categoria</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('categoria.listar') }}">Relação de Categoria</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @error('error')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form role="form" action="{{ route('categoria.update',['categoria' => $categoria->cd_categoria]) }}" method="post" role="form">
                        @csrf
 
                    <input type="hidden" name="categoria_pai" value="{{$categoria->cd_categoria_pai}}" />

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>
                                        Categoria Pai: <span class="red normal"> </span>
                                    </label>

                                    <select name="cd_categoria_pai" disabled: true
                                        class="form-control" 
                                        aria-invalid="false">
                                        <option value="">Selecione</option>
                                        @foreach ($categorias as $linha)
                                            <option value="{{ $linha->cd_categoria }}" 
                                                @if( old('cd_categoria_pai',$categoria->cd_categoria_pai) == $linha->cd_categoria ) selected @endif >
                                                {{  $linha->cod_estrutural.' - '.$linha->nm_categoria }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cod. Estrutural: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control  " value="{{ old('estrutural',$categoria->cod_estrutural) }}" name="estrutural"
                                        maxlength="100" aria-required="true" readonly required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Categoria: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required" value="{{ old('nm_categoria',$categoria->nm_categoria) }}" name="nm_categoria"
                                        maxlength="100" aria-required="true" required>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>
                                        Permitir Lançamento: <span class="red normal">*</span>
                                    </label>

                                    <select name="sn_lancamento"
                                        class="form-control"
                                        required=""
                                        aria-invalid="false">
                                        <option value="">Selecione</option>
                                        <option value="S" @if(old('sn_lancamento',$categoria->sn_lancamento)=='S') selected @endif>Sim</option>
                                        <option value="N" @if(old('sn_lancamento',$categoria->sn_lancamento)=='N') selected @endif>Não</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <label style="margin-left: 10px; "><b>Padrões para Lançamento:</b>  </label>
                        <div class="row box-footer" style="border: 1px solid #eee; padding: 15px; margin: 10px;">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        Tipo de Lançamento: <span class="red normal"> </span>
                                    </label>

                                    <select name="tp_lancamento"
                                        class="form-control" 
                                        aria-invalid="false">
                                        <option value="">Selecione</option>
                                        <option value="DESPESA" @if(old('tp_lancamento',$categoria->tp_lancamento)=='DESPESA') selected @endif >DESPESA </option>
                                        <option value="RECEITA" @if(old('tp_lancamento',$categoria->tp_lancamento)=='RECEITA') selected @endif >RECEITA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Descrição: <span class="red normal"> </span></label>
                                    <input type="text" class="form-control required" value="{{ old('descricao',$categoria->descricao) }}" name="descricao"
                                        maxlength="100" aria-required="true" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        Conta Bancária ou Cartão: <span class="red normal"> </span>
                                    </label> 
                                    <select name="cd_conta"
                                        class="form-control" 
                                        aria-invalid="false">
                                        <option value="">Selecione</option> 
                                        @foreach ( $conta  as $item) 
                                        <option value="{{$item->cd_conta }}" @if (old('cd_conta',$categoria->cd_conta)==$item->cd_conta) selected @endif>{{ $item->nm_conta }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        Forma de Pagamento: <span class="red normal"> </span>
                                    </label>

                                    <select name="cd_forma"
                                        class="form-control" 
                                        aria-invalid="false">
                                        <option value="">Selecione</option> 
                                        @foreach ( $forma  as $item) 
                                        <option value="{{$item->cd_forma_pag }}" @if (old('cd_forma',$categoria->cd_forma)==$item->cd_forma_pag) selected @endif>{{ $item->nm_forma_pag }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                           
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Cliente e Fornecedor: <span class="red normal"> </span>
                                    </label>

                                    <select name="cd_fornecedor"
                                        class="form-control" 
                                        aria-invalid="false">
                                        <option value="">Selecione</option> 
                                        @foreach ( $fornecedor  as $item) 
                                        <option value="{{$item->cd_fornecedor }}" @if (old('cd_fornecedor',$categoria->cd_fornecedor)==$item->cd_fornecedor) selected @endif>{{ $item->nm_fornecedor }}</option> 
                                        @endforeach
                                    </select>
                                </div>
                            </div> 

                        </div>
                    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Salvar" />

                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                        </div>
                    </form>
                </div>
            </div>
        </div> 
    </div><!-- Main Wrapper -->
@endsection
