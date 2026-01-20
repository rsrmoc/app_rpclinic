
@extends('dpessoal.layout.layout')
 

@section('content') 


<div class="page-title">
    <h3>Tela de Usuário</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="index-2.html">Alteração</a></li> 
        </ol>
    </div>
</div>
<div >

    <div id="main-wrapper"  >
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-white" > 
                    <div class="panel-body">
                        <form>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Funcionário </label>
                                        <select class=" js-states form-control"  name="funcionario" style="display: none; width: 100%">
                                            <option value="">SELECIONE</option>
                                            <option value="1">DSAD SADDSA</option>
                                            <option value="1">RENATO</option>
                                            <option value="1">DEIXA</option>
                                            <option value="1">TESTE</option>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Perfil</label> 
                                        <select class="js-example-tokenizer js-states form-control"  tabindex="-1" name="perfil" multiple="multiple" style="display: none; width: 100%">
                                            <option value="FU">FUNCIONÁRIO</option>
                                            <option value="DP" >DEPARTAMENTO PESSOAL</option>
                                            <option value="GE">GESTOR</option>
                                            <option value="SU">SUPERVISOR</option>
                                        </select> 
                                    </div>
                                </div>   
                            </div> 
                            <div class="row">
                                <div class="col-md-3"> 
                                        <label>
                                            <input type="checkbox" name="ativo" value="S"> Ativo
                                        </label> 
                                </div>
                                <div class="col-md-3"> 
                                    <label>
                                        <input type="checkbox" name="reset" value="S"> Resetar Senha
                                    </label> 
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-info"> <i class="fa fa-check-square-o"></i> Alterar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
</div><!-- Main Wrapper -->

@endsection

@section('script') 

@endsection