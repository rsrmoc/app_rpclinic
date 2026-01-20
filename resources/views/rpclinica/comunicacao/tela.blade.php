@extends('rpclinica.layout.layout')

@section('content')
<meta http-equiv="refresh" content="15">
<div id="app" x-data="app">
    <div class="page-title">
        <table style=" width: 100%">
            <tr>
                <td>
                    <h3>Cadastro de Comunicação</h3>
                    <div class="page-breadcrumb"> 
                            <ol class="breadcrumb">
                                <li><a href="index-2.html">Cadastrar</a></li>
                            </ol>  
                    </div>
                </td>  
              
            </tr>   
        </table>
 
    </div> 
    <div id="main-wrapper" style="text-align: center;">  

        @if(isset($data['sn_api'])) 

            @if($data['sn_api']=='S')

                @if($data['phoneConnected'] == true)
                <div style="text-align: center">
                    <img style="text-align: right; cursor: pointer; margin-left: 10px;" src="{{ asset('assets\images\logo-whatsapp.webp') }}"  height="200">
                    <div class="alert alert-success" role="alert">
                        <b>Telefone Conectado!</b>
                    </div> 
                    
                    <h3 style="font-weight: normal; font-size: 20px; line-height: 13px;">{{ $data['description'] }}</h3> 
                    <h3 style="font-weight: 300; font-size: 15px; line-height: 13px;">{{ $data['name'] }}</h3>
                    <img src="{{ $data['img'] }}" style=" height: 90px; text-align: center;"  alt="">
                    <h3 style="font-weight: 300; font-size: 15px; line-height: 13px;"><code><b>API:</b> {{ $data['tp_api'] }}</code></h3>
                    <br>
                    <a href="{{ route('comunicacao.desc') }}" style="margin-top: 25px;" class="btn btn-danger btn-addon m-b-sm btn-rounded btn-sm">
                        <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Desconetar do Whatsapp
                    </a>
                
                </div>
                @endif
                
                @if($data['phoneConnected'] == false)
                <div class="row">
                    <div class="col-md-12 ">
                        <img style="text-align: right; cursor: pointer; margin-left: 10px;" src="{{ asset('assets\images\logo-whatsapp.webp') }}"  height="200">
                        @if($data['image_qrcode'])
                            <h3 style="font-weight: 300; font-size: 15px; line-height: 13px;">Pareamento do Whatsapp</h3>
                            @if($data['tp_api']=='api-wa.me')
                                <img src="{{ $data['image_qrcode'] }}" style="   text-align: center;"  alt=""> 
                            @endif
                            @if($data['tp_api']=='kentro')
                                @if($data['image_qrcode'] <> '{"message":"Qrcode not found"}')
                                     
                                    {!! $data['image_qrcode'] !!}
                                    <br><br>
                                @endif
                            @endif 
                        @else 
                            <h3 class="red" style="font-weight: 300; font-size: 15px; line-height: 13px;">Erro ao carregar QRcode <br> <b>Atualize a pagina.</b></h3>
                        @endif
                    </div>
                </div>
                @endif

            @else

                <img style="text-align: right; cursor: pointer; margin-left: 1px;" src="{{ asset('assets\images\logo-whatsapp.webp') }}"  height="150">
                <br>
                <code> {{ $data['msg']}} </code>
            @endif

        @else
 
                <img style="text-align: right; cursor: pointer; margin-left: 1px;" src="{{ asset('assets\images\logo-whatsapp.webp') }}"  height="150">
                <br>
                <code> API não configurada! </code>

        @endif

 
    </div>



 
</div>
@endsection

@section('scripts')
  
<script src="{{ asset('js/rpclinica/comunicacao.js') }}"></script>

@endsection