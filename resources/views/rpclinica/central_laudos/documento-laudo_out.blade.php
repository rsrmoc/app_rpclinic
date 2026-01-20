<html>
    <head>
        <style>
            /** Define the margins of your page **/

            * {
                font-family:Georgia, 'Times New Roman', Times, serif; 
            }
            @page {
                margin: 100px 50px;
                margin-left: 20px;
                margin-right: 20px;
                line-height: 7px;
                font-size: 18px;
            }

            header {
                position: fixed;
                top: -80px;
                left: 0px;
                right: 0px;
                height: {{$empresa->laudo_header_height}}px;   
            
            }

            footer {
                position: fixed; 
                bottom: -85px; 
                left: 0px; 
                right: 0px;
                height: 200px; 
                font-weight: 600;
                font-style: italic; 

                /** Extra personal styles **/ 
                
                color: #116437;
                text-align: center;
                line-height: 20px;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>

            <table   border="0" width="100%" style="border-bottom: #116437 2px solid;"  >
                <tr>
                    <td   style="text-align:center;">
                        <img src = "data:{{$empresa->type_logo}};base64,{{$empresa->logo}}" height="{{ $empresa->laudo_img_height }}"  > 
                    </td>  
                    <td  style="text-align: center; font-style: italic; font-size: 30px; line-height: 25px; color: #116437; font-weight: bold">
                        {{ $empresa->laudo_titulo }}<br>
                        <span style="font-size: 22px;font-weight: normal "> {{ $empresa->laudo_sub_titulo }} </span>
                    </td>  
                </tr> 
            </table>

            <table   border="0" cellpadding="3" cellspacing="3" width="100%" 
                style="font-size: 14px; padding: 0px; padding-left: 4px; padding-top: 8px;">
                <tr>
                    <td width="10%" style="text-align: left; font-size: 15px; line-height: 10px;">
                        <b>Paciente: </b>
                    </td>  
                    <td width="60%"   style="text-align: left; font-size: 15px; line-height: 10px;">
                    {{ $item->atendimento?->paciente?->nm_paciente }}
                    </td>  
                    <td width="22%" style="text-align: left; font-size: 15px; line-height: 10px;">
                        <b>Data de Nascimento: </b>
                    </td>  
                    <td width="18%"   style="text-align: left; font-size: 15px; line-height: 10px;">

                        {{ ($item->atendimento?->paciente?->dt_nasc) ? formatDate($item->atendimento?->paciente?->dt_nasc) : ' -- ' }}
                    </td>  
                </tr>   
            </table>

            <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
                style="font-size: 14px; padding: 0px; padding-left: 4px;">
                <tr>
                    <td width="18%" style="text-align: left; font-size: 14px; line-height: 10px;">
                        <b>Solicitante: Dr.(a): </b>
                    </td>  
                    <td width="52%"   style="text-align: left; font-size: 14px; line-height: 10px;">
                        NOME DO MEDICO SOLICITANTE
                    </td>  
                    <td width="22%" style="text-align: left; font-size: 15px; line-height: 10px;">
                        <b>Data de Realização: </b>
                    </td>  
                    <td width="18%"   style="text-align: left; font-size: 15px; line-height: 10px;">
                        {{ ($item->dt_laudo) ? formatDate($item->dt_laudo) : ' -- ' }}
                    </td>  
                </tr>  
            </table>

            <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
                style="font-size: 14px; padding: 0px; padding-left: 4px; ">
                <tr>
                    <td width="10%" style="text-align: left; font-size: 14px; line-height: 10px;">
                        <b>Convênio:  </b>
                    </td>  
                    <td width="60%"   style="text-align: left; font-size: 14px; line-height: 10px;">
                        {{ $item->atendimento?->convenio?->nm_convenio   }}
                    </td>  
                    <td width="22%" style="text-align: left; font-size: 15px; line-height: 10px;">
                        <b>Codigo: </b>
                    </td>  
                    <td width="18%"   style="text-align: left; font-size: 15px; line-height: 10px;">
                        {{  str_pad($item->cd_agendamento_item , 8 , '0' , STR_PAD_LEFT)   }}
                    </td>  
                </tr>  
            </table>
            <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
            style="font-size: 14px; padding: 0px; padding-left: 4px;">
            <tr>
                <td width="100%" style="text-align: center; font-size: 16px; line-height: 10px; font-style: italic">
                    <b>{{ $item->exame?->nm_exame }}  </b>
                </td>  
            </tr>
            </table>
            
        </header>

        <footer>
            
            <table width="100%" style="text-align: center;color: black" >
                <tr>
                    <td style="text-align: center; line-height: 18px;"> 
                @if($item->atendimento?->profissional?->tp_assinatura)                        
                    <img src = "data:{{$item->atendimento?->profissional->tp_assinatura}};base64,{{$item->atendimento?->profissional->assinatura}}"  
                        style="max-width: 70px;text-align: center;"  /><br>
                @else
                <br><br><br> 
                @endif
                    _____________________________________________<br>
                    {{ $item->atendimento?->profissional->nm_profissional }}<br>
                    <spam style="font-size: 14px">{{ ($item->atendimento?->profissional->conselho) ? $item->atendimento?->profissional->conselho.' - ' : null }}
                    {{ ($item->atendimento?->profissional->crm) ? $item->atendimento?->profissional->crm : null }} </spam>
                    </td> 
                </tr> 
            </table>
            <br><br> 
            <table width="100%" style="text-align: center; border-top: #116437 2px solid; font-size: 15px;
                line-height: 13px; ">
                <tr>
                    <td width="30%" style="text-align: left;font-size: 9px;"> 
                        Data Liberação: {{ date('d/d/Y H:i')}} <br>
                        Liberação:  {{ (isset($item->usuario_laudo) ? $item->usuario_laudo : ' -- ') }}
                    </td> 
                    <td width="40%" style="text-align: center"> 
                        {{$empresa->laudo_footer}}<br>
                        {{$empresa->laudo_footer2}}
                    </td> 
                    <td width="30%" style="text-align: right;font-size: 9px;line-height: 13px;"> 
                        Data da Impressão: {{ date('d/d/Y H:i')}} <br>
                        Impresso: {{ (isset($usuario->cd_usuario) ? $usuario->cd_usuario : ' externo ') }} 
                        
                    </td> 
                </tr> 
            </table>
 
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main> 
            <p style="{{$empresa->laudo_main_css}}">
                {!! $item->conteudo_laudo !!}    
            </p>  
            @if($Imagens)
                <div style="page-break-after: always"></div>
                <p style="{{$empresa->laudo_main_css}}">
                    <h4 style="text-align: center;font-weight: bold; font-style: italic;"> Imagens </h4>
                    @foreach($Imagens as $key => $img) 
                            <img src="{{ $img['conteudo_img'] }}" style="max-height: 600px;max-width: 100%;"   >
                        
                    @endforeach
                </p> 

            @endif
           

        </main>

        
        
    </body>
</html>