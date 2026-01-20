@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Editar Grupo de Procedimento</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('grupo.procedimento.listar') }}">Relação de Procedimentos</a></li>
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

                    <form role="form"
                        action="{{ route('grupo.procedimento.update', ['grupo' => $grupo->cd_grupo]) }}"
                        method="post"
                        role="form">
                        @csrf
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Descrição do Grupo  <span class="red normal">*</span></label>
                                    <input type="text" class="form-control " value="{{ old('nm_grupo',$grupo->nm_grupo) }}" name="nm_grupo"
                                        maxlength="200" aria-required="true">
                                    @if ($errors->has('nm_grupo'))
                                        <div class="error">{{ $errors->first('nm_grupo') }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group @if ($errors->has('tp_grupo')) has-error @endif ">
                                    <label>Tipo do Grupo: <span class="red normal">*</span></label>
                                    <select class="form-control" required="" name="tp_grupo">
                                        <option value="">...</option>
                                        <option value="SH" @if(old('tp_grupo',$grupo->tp_grupo)=='SH') selected @endif >Serviços Hospitalares</option>
                                        <option value="SP" @if(old('tp_grupo',$grupo->tp_grupo)=='SP') selected @endif >Serviços Profissionais</option>
                                        <option value="SD" @if(old('tp_grupo',$grupo->tp_grupo)=='SD') selected @endif >Serviços Diagnósticos</option>
                                        <option value="ME" @if(old('tp_grupo',$grupo->tp_grupo)=='ME') selected @endif >Medicamentos</option>
                                        <option value="OP" @if(old('tp_grupo',$grupo->tp_grupo)=='OP') selected @endif >Orteses e Proteses</option>
                                        <option value="OU" @if(old('tp_grupo',$grupo->tp_grupo)=='OU') selected @endif >Outros Lançamentos</option>
                                    </select>
                                    @if ($errors->has('tp_grupo'))
                                        <div class="error">{{ $errors->first('tp_grupo') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3"> 
                                <label>
                                    <input type="checkbox" name="sn_produto" value="S" @if ($grupo->sn_produto == 'S') checked @endif  
                                        class="flat-red">
                                    Integra com a tela de Produto?
                                </label>
                            </div>
                            <div class="col-md-3"> 
                                <label>
                                    <input type="checkbox" @if ($grupo->sn_ativo == 'S') checked @endif name="ativo" value="S" 
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
