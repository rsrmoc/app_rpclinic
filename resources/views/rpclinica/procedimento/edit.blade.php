@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Editar Procedimento</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('procedimento.listar') }}">Relação de Procedimentos</a></li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper">
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
                    <form role="form"
                        action="{{ route('procedimento.update', ['procedimento' => $procedimento->cd_proc]) }}" method="post" role="form">
                        @csrf
                        <div class="row">
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Codigo de Procedimento <span class="red normal">*</span></label>
                                    <input type="text" class="form-control " value="{{ old('cod_proc',$procedimento->cod_proc) }}" readonly name="cod_proc"
                                        maxlength="200" aria-required="true">
                                    @if ($errors->has('cod_proc'))
                                        <div class="error">{{ $errors->first('cod_proc') }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group @if ($errors->has('descricao')) has-error @endif ">
                                    <label>Descrição: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control " value="{{ old('nm_proc',$procedimento->nm_proc) }}" name="descricao"
                                        maxlength="200" aria-required="true">
                                    @if ($errors->has('descricao'))
                                        <div class="error">{{ $errors->first('descricao') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group @if ($errors->has('cd_grupo')) has-error @endif ">
                                    <label>Grupo de Procedimento: <span class="red normal">*</span></label>
                                    <select class="form-control"  name="cd_grupo">
                                        <option value="">...</option>
                                        @foreach ($grupo as $linha )
                                            <option value="{{ $linha->cd_grupo }}" @if(old('cd_grupo',$procedimento->cd_grupo)==$linha->cd_grupo) selected @endif >{{ $linha->nm_grupo }}</option>    
                                        @endforeach  
                                    </select>
                                    @if ($errors->has('cd_grupo'))
                                        <div class="error">{{ $errors->first('cd_grupo') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group @if ($errors->has('unidade')) has-error @endif ">
                                    <label for="fname" class="mat-label">Unidade <span class="red normal">*</span></label>
                                    <input type="text" class="form-control " value="{{ old('unidade',$procedimento->unidade) }}" name="unidade"
                                        maxlength="15" aria-required="true">
                                    @if ($errors->has('unidade'))
                                        <div class="error">{{ $errors->first('unidade') }}</div>
                                    @endif
                                </div>
                            </div>
 
                        </div>


                        <div class="row">
                            <div class="col-md-2"> 
                                <label>
                                    <input type="checkbox" @if (old('unidade',$procedimento->sn_pacote) == 'S') checked @endif name="pacote" value="S" 
                                        class="flat-red">
                                    Pacote
                                </label>
                            </div> 
                            <div class="col-md-3"> 
                                <label>
                                    <input type="checkbox" @if (old('unidade',$procedimento->sn_ativo) == 'S') checked @endif name="ativo" value="S" 
                                        class="flat-red">
                                    Ativo
                                </label>
                            </div> 
                        </div>

                        <hr />

                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Salvar" />
                            <input type="reset" class="btn btn-default" value="Limpar" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!-- Main Wrapper -->
@endsection

@section('script')
@endsection
