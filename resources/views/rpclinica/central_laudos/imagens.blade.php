<!DOCTYPE html>
<html lang="en">
<head>
  <title>Central de Laudos - RPclinic</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href='{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/fontawesome/css/font-awesome.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/line-icons/simple-line-icons.css') }}' rel="stylesheet" type="text/css" />
  <script src="{{ asset('assets/plugins/jquery/jquery-2.1.4.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
</head>
<body id="myvideo">
<style>
.navbar-inverse {
    background-color: #22baa0;
    border-color: #fff;
}
.navbar-inverse .navbar-brand {
    color: #fff;
}
.navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:focus, .navbar-inverse .navbar-nav>.active>a:hover {
    color: #fff;
    background-color: #1c937f;
}
.navbar-inverse .navbar-nav>li>a {
    color: #fff;
}

@media (min-width: 1200px) {
    .container {
        width: 98%;
    }
}

@media (min-width: 992px) {
    .container {
        width: 98%;
    }
}
@media (min-width: 768px) {
    .container {
        width: 98%;
    }
}
</style>
 
<nav class="navbar navbar-inverse" style="border-radius: 0px;     line-height: 8px;">
  <div class="container-fluid">
    <div class="navbar-header">
     
        <div class="row">  
            <a class="navbar-brand" href="#" 
              style=" padding-bottom: 5px; padding-top: 5px; height: auto; padding: 2px 0px; margin-left: 5px;"><i class="fa fa-user"></i> {{ mb_convert_case($item->atendimento?->paciente?->nm_paciente, MB_CASE_TITLE, "UTF-8") }}</a><br>
            <small class="navbar-brand" style="font-size: 13px;padding-top: 5px; height: auto; padding: 0px 0px; margin-left: 8px;"> {{ mb_convert_case($item->exame->nm_exame, MB_CASE_TITLE, "UTF-8") }} [ {{ $item->cd_agendamento_item }} ]</small>
          
        </div> 
  

    </div>
    
 
  </div>
    
  </div>
</nav>
  
<div class="container">
  <div class="row">
    
    @if(isset($dados['array_img'])) 
      @if($dados['array_img']) 

        @foreach($dados['array_img'] as $key => $img)
              
              <div class="col-md-6"> 
                <h3>{{ $img['descricao'] }} <small> {{ $img['data']}} </small></h3> 

                @if($img['sn_visualiza']=='S')

                    @if($img['tipo']=='img')
                      <img class="img-fluid"
                      style="max-height: 600px;max-width: 100%;"
                      src="{{$img['conteudo_img']}}" />
                    @endif

                    @if($img['tipo']=='pdf')
                      <iframe 
                          src="{{  'data:' . $img['mime_type'] . ';base64,' . base64_encode(file_get_contents($img['CaminhoImg'])) }}"
                          frameBorder="0"
                          scrolling="auto" 
                          style="height: 600px; width: 100%; border: 5px solid #525659">
                      </iframe>
                    @endif

                @else
                      
                    @if($img['tipo']=='pdf') 
                        <a href="/rpclinica/central-laudos-visualizar/doc/{{ $img['codigo'] }}" style="text-align: center;"
                            target="_blank">
                            <img class="img-fluid "
                                style="height: 400px;  border: 2px solid #525659; padding: 30px;"
                                src="{{ asset('assets\images\ficheiro-pdf.png') }}">
                        </a><br>
                        <code><i class="fa fa-exclamation-triangle"></i> Arquivo acima do tamanho para Visualização... Click para Visualizar!</code>
                    @endif

                    @if($img['tipo']=='img') 
                        <a href="/rpclinica/central-laudos-visualizar/doc/{{ $img['codigo'] }}" style="text-align: center;"
                            target="_blank">
                            <img class="img-fluid " 
                                style="height: 400px;   border: 2px solid #525659; padding: 30px;"
                                src="{{ asset('assets\images\ficheiro-imagem.png') }}">
                        </a>
                    @endif

                @endif
          
              </div>  

        @endforeach

      @else 
          <div class="col-md-12" style="text-align: center;"> 
            <img src="{{asset('assets\images\vazio.png')}}"><br><h3 style="font-size: 18px;">Não anexos para esse exame!</h3> 
          </div>
      @endif 
    
    @else    
          <div class="col-md-12" style="text-align: center;"> 
            <img src="{{asset('assets\images\vazio.png')}}"><br><h3 style="font-size: 18px;">Não anexos para esse exame!</h3> 
          </div>
    @endif

  </div>
  
   
</div>

</body>
</html>