
@extends('rpclinica.layout.layout')
  
@section('content') 

<div class="row">
    <div class="col-xs-12" style="text-align: center;">

        <img style="text-align: center;" src="{{ asset('assets/images/rp_sys_erro.png') }}"> <br>
        <code>{{ $dados }}</code>

    </div>
</div>

 @endsection

 
 

@section('script') 


  
@endsection