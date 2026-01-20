@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Contas</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{route('conta.bancaria.listar')}}">Cadastrar</a></li>
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

                    <form role="form" id="addUser" action="{{ route('conta.bancaria.store') }}" method="post"
                        role="form">
                        @csrf

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Descrição da conta: <span class="red normal">*</span></label>

                                    <input type="text" class="form-control required" value="{{ old('numero_da_conta') }}" name="numero_da_conta"
                                        maxlength="100" aria-required="true" required>
                                </div>
                            </div>
 
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipo de Conta: <span class="red normal">*</span></label>
                                    <select name="tp_conta" class="form-control" required>
                                        <option value=""> Selecione </option> 
                                        @foreach($contaTipo as $key => $val) 
                                            <option value="{{$val->cd_tipo_conta}}" @if(old('tp_conta')== $val->cd_tipo_conta) selected  @endif> {{ $val->nm_tipo_conta }} </option> 
                                        @endforeach   
                                    </select>
                                </div>
                            </div>
                
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tipo Saldo: <span class="red normal"></span></label> 
                                    <select name="tp_saldo" class="form-control">
                                        <option value="">Selecione</option>
                                        <option value="R" @if(old('tp_saldo')=='R') selected  @endif >Receita</option>
                                        <option value="D" @if(old('tp_saldo')=='D') selected  @endif >Despesa</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Saldo inicial: <span class="red normal"></span></label> 
                                    <input x-mask:dynamic="$money($input, ',')"
                                        class="form-control required" value="{{ old('saldo_inicial') }}" name="saldo_inicial" onKeyPress="return(moeda(this,'.',',',event))"
                                        maxlength="100" aria-required="true"  >
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Data do Saldo: <span class="red normal"></span></label>

                                    <input  type="date"
                                        class="form-control required" value="{{ old('dt_saldo') }}" name="dt_saldo" 
                                        maxlength="10" aria-required="true"  >
                                </div>
                            </div> 

                        </div>
                        
                        <div class="row">  
                            <div class="col-md-2 ">
                                <div class="line">
                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                        <label style="padding: 0">
                                            <div class="checker"> 
                                                <span>
                                                    <input type="checkbox" value="S" name="investimento"   />
                                                </span>
                                            </div> Investimento
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 ">
                                <div class="line">
                                    <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                        <label style="padding: 0">
                                            <div class="checker"> 
                                                <span>
                                                    <input type="checkbox" value="S" name="exibir_resumo"   />
                                                </span>
                                            </div> Exibir resumo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                      
                        <br>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Salvar" />
                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
