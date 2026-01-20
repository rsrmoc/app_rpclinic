
@extends('dpessoal.layout.layout')
 

@section('content') 


<div class="page-title">
    <h3>Alteração de Senha</h3>
    <div class="page-breadcrumb">
        <ol class="breadcrumb">
            <li><a href="index-2.html">Usuario</a></li> 
        </ol>
    </div>
</div>
<div >

    <div id="main-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-white"> 
                    <div class="panel-body">
                        <form>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Senha Atual</label>
                                        <input type="password" class="form-control" id="exampleInputEmail1" placeholder="Senha Atual">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Nova Senha</label>
                                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Nova Senha">
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Confirme a Senha </label>
                                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Confirme a Senha">
                                    </div>
                                </div> 
                                
                            </div>
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