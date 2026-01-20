<html>
<head>
  <style>
    @page { margin: 170px 25px; }
    header { position: fixed; top: -120px; left: 0px; right: 0px;  line-height: 15px;   text-align: center;  }
    footer { position: fixed; bottom: -110px; left: 0px; right: 0px;  height: 100px; }
    p { line-height: 13px; }  

  </style>
</head>
<body >

    <header>

        <table style="width: 100%;"> 
            <tr> 
                <td colspan="2" width="100%" style="text-align: center;">

                    @if($execultante['sn_ocultar_titulo']<>'S')
                        <div style="font-size: 23px; font-style: italic; "> {{ $relatorio['titulo'] }} </div> 
                    @else 
                        <br>
                    @endif 
 
                </td> 
            </tr>
            <tr> 
                <td width="70%"  >
                    <table width="100%">
                        <tr> 
                            <td colspan="2" width="100%" style="text-align: center;">
                                <div style="font-size: 16px; text-align: left; font-style: italic"> 
                                    <span style="font-style: italic; font-weight: 600"> Paciente: </span>  {{ mb_convert_case( $relatorio['paciente'] , MB_CASE_TITLE, 'UTF-8') }}
                                </div> 
                            </td> 
                        </tr>
                        <tr> 
                            <td width="50%"  >
                                <div style="font-size: 16px; text-align: left; font-style: italic"> <span style="font-style: italic; font-weight: 600"> Idade: </span>  
                                {{ ($relatorio['dt_nasc']) ? idadeAluno($relatorio['dt_nasc']) : ' -- ' }}
                                </div> 
                            </td>
                            <td width="50%"  >
                                
                            </td>
                        </tr> 
                    </table>
                    
                </td> 
                <td width="30%"  >
                    <table width="100%">
                        <tr> 
                            <td   width="100%" style="text-align: center;">
                                <div style="font-size: 16px; text-align: right; font-style: italic"> 
                                    <span style="font-style: italic; font-weight: 600"> Atendimento: </span>  {{ (isset($agendamento->cd_agendamento )) ? $agendamento->cd_agendamento  : ' -- ' }}
                                </div> 
                            </td> 
                        </tr>
                        <tr> 
                            <td   width="100%" style="text-align: center;">
                                <div style="font-size: 16px; text-align: right; font-style: italic"> 
                                    <span style="font-style: italic; font-weight: 600"> Data: </span>  {{ \Carbon\Carbon::parse($relatorio['data'])->format('d/m/Y')}}
                                </div> 
                            </td> 
                        </tr>
                        
                    </table>
                </td> 
            </tr>
            <tr> 
                <td colspan="2" width="100%" style="text-align: center; border-bottom: 1px solid black; ">
                    
                </td> 
            </tr>
        </table> 
    </header>

    <footer>
                <table width="100%" style="text-align: center;color: black" >
        
                    <tr>
                        <td style="text-align: center; line-height: 18px;"> 
                                        
                        @if($relatorio['tp_assinatura'])      
                            <img src = "data:{{ $relatorio['tp_assinatura'] }};base64,{{ $relatorio['assinatura'] }}" style=" max-height: 50px;"  /><br>
                        @else
                            <br><br><br> 
                        @endif 
                        _____________________________________________<br>
                        <div style="font-style: italic; font-weight: 800; line-height: 19px;"> {{ mb_convert_case($relatorio['nm_profissional'], MB_CASE_TITLE, 'UTF-8') }}  </div>   
                        <div style="font-style: italic; font-weight: 600; font-size: 13px; line-height: 13px;"> 
                            {{ ($relatorio['conselho']) ? $relatorio['conselho'] .' - ' : null }}
                            {{ ($relatorio['crm']) ? $relatorio['crm'] : null }}    
                        </div>
                        </td> 
                    </tr> 
                </table>

    </footer>

     