@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Cadastro de Produtos</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('produto.listar') }}">Relação de Produtos</a></li>
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

                    <form role="form" id="addUser" action="{{ route('produto.store') }}" method="post" role="form">
                        @csrf

                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label for="fname">Nome: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required" value="{{ old('nome') }}" name="nome"
                                        maxlength="100" aria-required="true" required>
                                    @if ($errors->has('nome'))
                                        <div class="error">{{ $errors->first('nome') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Classificação <span
                                            class="red normal">*</span></label>
                                    <select class="form-control" required="" name="classificacao">
                                        <option value="">Selecione</option>
                                        @foreach($classificacoes as $classif)
                                            <option value="{{ $classif->cd_classificacao }}">{{ $classif->nm_classificacao }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('classificacao'))
                                        <div class="error">{{ $errors->first('classificacao') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Classificação XYZ <span
                                            class="red normal">*</span></label>
                                    <select class="form-control" required="" name="xyz">
                                        <option value="">Selecione</option>
                                        <option value="X">X - Cuidado Rígido</option>
                                        <option value="Y">Y - Cuidado Normal</option>
                                        <option value="Z">Z - Moderado</option>
                                    </select>
                                    @if ($errors->has('xyz'))
                                        <div class="error">{{ $errors->first('xyz') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Produto Mestre <span
                                            class="red normal"></span></label>
                                    <select class="form-control" name="cd_mestre">
                                        <option value="">Selecione</option>
                                        @foreach ($mestre as $linha )
                                            <option value="{{ $linha->cd_produto }}" @if(old('cd_mestre')==$linha->cd_produto) selected @endif >{{   $linha->nm_produto }}</option>    
                                        @endforeach  
                                    </select>
                                    @if ($errors->has('cd_mestre'))
                                        <div class="error">{{ $errors->first('cd_mestre') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Procedimento <span
                                            class="red normal"></span></label>
                                    <select class="form-control"  name="cd_procedimento">
                                        <option value="">Selecione</option>
                                        @foreach ($proc as $linha )
                                            <option value="{{ $linha->cd_proc }}" @if(old('cd_grupo')==$linha->cd_proc) selected @endif >{{ $linha->cod_proc.' - '. $linha->nm_proc }}</option>    
                                        @endforeach  
                                    </select>
                                    @if ($errors->has('cd_procedimento'))
                                        <div class="error">{{ $errors->first('cd_procedimento') }}</div>
                                    @endif
                                </div>
                            </div>
  
                            
                        </div>

                        
                        <div class="row">
                            <div class="col-md-2"> 
                                <label>
                                    <input type="checkbox"  name="opme" value="S" 
                                        class="flat-red">
                                        OPME 
                                </label>
                            </div> 
                            <div class="col-md-2"> 
                                <label>
                                    <input type="checkbox"  name="medicamento" value="S" 
                                        class="flat-red">
                                        Medicamentos
                                </label>
                            </div> 
                            <div class="col-md-2"> 
                                <label>
                                    <input type="checkbox"  name="lote" value="S" 
                                        class="flat-red">
                                        Controla Lote 
                                </label>
                            </div> 
                            <div class="col-md-2"> 
                                <label>
                                    <input type="checkbox"  name="curva" value="S" 
                                        class="flat-red">
                                        Curva ABC  
                                </label>
                            </div> 
                            <div class="col-md-2"> 
                                <label>
                                    <input type="checkbox"  name="bloqueia" value="S" 
                                        class="flat-red">
                                        Bloqueia  
                                </label>
                         
                            </div>
                        </div>
                        <div class="box-footer" style="margin-top: 15px;">
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
