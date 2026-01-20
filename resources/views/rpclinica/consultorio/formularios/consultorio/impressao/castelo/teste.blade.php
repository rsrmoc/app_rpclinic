<html>
<head>
  <style>
 
    @page { margin: 220px 28px; font-family: Arial, Helvetica, sans-serif;  }
    
    header { position: fixed; top: -165px; left: 0px; right: 0px;  height: 150px; }

    footer { position: fixed; bottom: -260px; left: 0px; right: 0px; border: 2px solid red; height: 280px; font-size: 11px; }

    p { page-break-after: always;                     
        line-height: 1.5;
        margin-top: 7px;
        margin-bottom: 2px;
        font-size: 12px;
    }

    p:last-child { page-break-after: never; }

  </style>
</head>
<body>
    <header> 
        <table border="0" cellpadding="0" cellspacing="0"  style="width: 100%;margin: 0px; padding: 0px;  height: 20px;  margin-top: 18px;">
            <tr >
              <td valign="top" style=" margin: 0px; padding: 0px; text-align: left;"><br>
                @if($execultante['header']=='N')

                    <span   style="font-family: 'Courier New', Courier, monospace; font-style: italic; font-weight: 900; font-size: 1.8em;    color: #2b2f35; text-align: right;
                                    margin: 0px;padding: 0px; right: 0;   ">

                        {{ PrimeiraLetraMaiuscula($agendamento->paciente->nm_paciente) }}

                    </span><br>
                    <span   style="font-family: 'Courier New', Courier, monospace; font-style: italic; font-weight: 900; font-size: 0.9em;    color: #2b2f35; text-align: right;
                    margin: 0px;padding: 0px; right: 0;   ">
                        Idade: {{ idadeAluno($agendamento->paciente->dt_nasc) }}
                    </span>

                @endif

              </td>
              <td style=" margin: 0px; padding: 0px; text-align: right;"> 
                @if($execultante['sn_logo']=='N')
                    <img src = "data:{{ $Empresa->type_logo }};base64,{{ $Empresa->logo }}"  height="100" style="  margin: 0px;padding: 0px; margin-right: 20px;  " >
                @endif 
               </td>
            </tr>
          </table>
    </header>


    <footer style="margin-top: 20px;">
        <table style="width: 100%; text-align: center; margin-bottom: 5px; font-size: 12px;">
           <tr>
               <td height="10" valign="top" style="text-align: right; margin-right: 5px;">
                 @if($execultante['data']=='S')
                 <span style=" font-style: italic; font-weight: 700; font-family:'OpenSans' ; ">
                      Montes Claros , ____/____/______   </span>
                 @else
                   <?php
                        $data = date_format(date_create ($agendamento->dt_agenda), 'D');
                        $mes = date_format(date_create ($agendamento->dt_agenda), 'M');
                        $dia = date_format(date_create ($agendamento->dt_agenda), 'd');
                        $ano = date_format(date_create ($agendamento->dt_agenda), 'Y');
                        
                       $semana = array(
                           'Sun' => 'Domingo',
                           'Mon' => 'Segunda-Feira',
                           'Tue' => 'Terca-Feira',
                           'Wed' => 'Quarta-Feira',
                           'Thu' => 'Quinta-Feira',
                           'Fri' => 'Sexta-Feira',
                                   'Sat' => 'Sábado'
                       );

                       $mes_extenso = array(
                           'Jan' => 'Janeiro',
                           'Feb' => 'Fevereiro',
                           'Mar' => 'Marco',
                           'Apr' => 'Abril',
                           'May' => 'Maio',
                           'Jun' => 'Junho',
                           'Jul' => 'Julho',
                           'Aug' => 'Agosto',
                           'Nov' => 'Novembro',
                           'Sep' => 'Setembro',
                           'Oct' => 'Outubro',
                           'Dec' => 'Dezembro'
                       );
                       $data= $semana["$data"] . ", {$dia} de " . $mes_extenso["$mes"] . " de {$ano}";
                   ?>
                 <span style=" font-style: italic; font-weight: 700">
                   Montes Claros , {{ $data }}  </span>
                 @endif
             </td>
           </tr>

           <tr>
               <td valign="bottom" >
                   
                       @if($execultante['sn_assinatura']=='S')

                            <style>

                                @page {
                                    margin: 160px 25px; 
                                }
                               
                            </style>
                            <br>
                            <img src = "data:{{ $execultante['tp_assinatura'] }};base64,{{ $execultante['assinatura'] }}"  style=" max-height: 60px;"  />
                            <br>__________________________________________ 
                       @else
                        <div style="height: 20px;" >
                            __________________________________________<br>
                           
                        </div>
                      @endif

                   <table width="100%" style="text-align: center;  " >

                     <tr>
                         <td height="10"valign="bottom">
                            <div style="line-height: 10px;">
                                {{ ($execultante['nome']) ? $execultante['nome'] : '--'  }}<br>
                                @if($execultante['espec'])
                                    {{ $execultante['espec']  }}<br>
                                @endif
                                {{ ($execultante['conselho']) ? $execultante['conselho'] : '--'  }}
                            </div>
                         </td>
                     </tr>

                   </table>

               </td>
           </tr>
        </table>
        @if($execultante['footer']=='N')
        <div style="width: 100%;  border-top: 3px solid #2b2f35;
        height: 35px; color: #2b2f35; font-style: italic; font-weight: bold; line-height: 12px; text-align: center; font-size: 11px;">  {{ $Empresa->end }} <br> {{ $Empresa->nm_empresa }}  </div>
        @endif
   </footer>

  <main>
    <p>
        {!! nl2br($documento->conteudo) !!}
    </p>
  </main>
</body>
</html>