<html>
    <head>
        <style>

            @font-face {
                font-family: 'OpenSans';
                font-style: normal;
                font-weight: normal;
                src: url({{ asset('assets/fonts/OpenSans-Regular.ttf') }}) format('truetype');
            }

            /*
            @page {
                margin: 160px 25px;
                margin-bottom: 310px;
            }

            header {
                position: fixed;
                top: -150px;
                left: 0px;
                right: 0px;
                height: 150px;
            }

            footer {
                position: fixed;
                top: 640px;
                bottom: -120px;
                left: 0px;
                right: 0px;
                height: 270px;
            }
            */




            header {
                position: fixed;
                top: -150px;
                left: 0px;
                right: 0px;
                height: 150px;
            }

        </style>

        @if ($execultante['footer']=='S')
            <style>

                @page {
                    margin: 160px 25px;
                    margin-bottom: 200px;
                    
                }
                footer {
                    position: fixed;
                    top: 730px;
                    bottom: -110px;
                    left: 0px;
                    right: 0px;
                    height: 175px; 
                }
            </style>
        @endif
        @if($execultante['footer']=='N')
            <style>

                @page {
                    margin: 160px 25px;
                    margin-bottom: 175px;
                }
                footer {
                    position: fixed;
                    top: 780px;
                    bottom: -120px;
                    left: 0px;
                    right: 0px;
                    height: 230px; 
                    
                }
                
                p{
                    line-height: 0.7;
                    margin-top: 7px;
                    margin-bottom: 2px;
                }
            </style>
        @endif

    </head>
    <body>
        <header>
            <br><br>
            <table border="0" cellpadding="0" cellspacing="0"  style="width: 100%;margin: 0px; padding: 0px;  height: 20px;  margin-top: 18px;">
                <tr >
                  <td valign="top" style=" margin: 0px; padding: 0px; text-align: left;"><br>
                    @if($execultante['header']=='N')

                        <span   style="font-family: 'Courier New', Courier, monospace; font-style: italic; font-weight: 900; font-size: 1.8em;    color: #2b2f35; text-align: right;
                                        margin: 0px;padding: 0px; right: 0;   ">

                            {{ PrimeiraLetraMaiuscula($Paciente->nm_paciente) }}

                        </span><br>
                        <span   style="font-family: 'Courier New', Courier, monospace; font-style: italic; font-weight: 900; font-size: 0.9em;    color: #2b2f35; text-align: right;
                        margin: 0px;padding: 0px; right: 0;   ">
                            Idade: {{ idadeAluno($Paciente->dt_nasc) }}
                        </span>

                    @endif


                  </td>
                  <td style=" margin: 0px; padding: 0px; text-align: right;"> 
                    @if($execultante['sn_logo']=='N')
                        <img src = "data:image/png;base64,{{ $execultante['logo'] }}"  height="120" style="  margin: 0px;padding: 0px; margin-right: 20px;  " >
                    @endif 
                   </td>
                </tr>
              </table>
        </header>

        <footer>
            <table style="width: 100%; text-align: center; margin-bottom: 10px; font-size: 14px;">
               <tr>
                   <td height="10" valign="top" style="text-align: right; margin-right: 10px;">
                     @if($execultante['data']=='S')
                     <span style=" font-style: italic; font-weight: 700; font-family:'OpenSans' ; ">
                          Montes Claros , ____/____/______   </span>
                     @else
                       <?php
                            $data = date_format(date_create ($agendamento->dt_agenda), 'D');
                            $mes = date_format(date_create ($agendamento->dt_agenda), 'M');
                            $dia = date_format(date_create ($agendamento->dt_agenda), 'd');
                            $ano = date_format(date_create ($agendamento->dt_agenda), 'Y');
                            /*
                           $data = date('D');
                           $mes = date('M');
                           $dia = date('d');
                           $ano = date('Y');
                            */
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
                                        margin-bottom: 205px;
                                    }
                                    footer {
                                        position: fixed;
                                        top: 690px;
                                        bottom: -120px;
                                        left: 0px;
                                        right: 0px;
                                        height: 230px; 
                                        
                                    }
                                </style>
                                <br>
                                <img src = "data:{{ $execultante['tp_assinatura'] }};base64,{{ $execultante['assinatura'] }}"  style=" max-height: 80px;"  />
                                <br>__________________________________________ 
                           @else
                            <div style="height: 20px;" >
                                __________________________________________<br>
                               
                            </div>
                          @endif

                       <table width="100%" style="text-align: center;  " >

                         <tr>
                             <td height="10"valign="bottom">
                                <div style="line-height: 14px;">
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
            height: 45px; color: #2b2f35; font-style: italic; font-weight: bold; line-height: 20px; text-align: center; font-size: 15px;">  {{ $HeaderFooter->endereco }} <br> {{ $HeaderFooter->nome }}  </div>
            @endif
       </footer>

        <main style="font-family:'OpenSans' ; font-size: 14px;">

            <p  style="font-family: 'OpenSans'; font-style: italic; font-weight: 900; font-size: 2em;      margin: 0px;  color: #2b2f35;   text-align: center;  padding: 0px; /*position: fixed;      transform: translate(-50%, -50%); */  ">
                {{ $TpDocumento }}
            </p>

            @if($tipo=='anamnese')

                <table width="99%" align="center" style="margin-top: 15px;">
                    <tr>
                        <td width="60%" style="line-height: 0.7;" >  <strong>Paciente:</strong> {{ PrimeiraLetraMaiuscula($Paciente->cd_paciente .' - '.$Paciente->nm_paciente) }}</td>
                        <td width="40%" style="line-height: 0.7;" > <em><strong>Idade:</strong></em> {{ idadeAluno($Paciente->dt_nasc) }}</td>
                    </tr>
                    <tr>
                        <td width="60%" style="line-height: 0.7;" > <em><strong>Prestador:</strong></em> {{ ($Profissional->nm_profissional) ? PrimeiraLetraMaiuscula($Profissional->nm_profissional) : '--'  }}</td>
                        <td width="40%" style="line-height: 0.7;" > <em><strong>Data do Atendimento:</strong></em> {{ ($agendamento->dt_agenda) ?  date_format(date_create ($agendamento->dt_agenda), 'd/m/Y'): '--' }}</td>
                    </tr>
                    <tr>
                        <td width="60%" style="line-height: 0.7;" > <em><strong>Convênio:</strong></em> {{ (isset($Convenio->nm_convenio)) ? PrimeiraLetraMaiuscula($Convenio->nm_convenio) : '--'  }}</td>
                        <td width="40%" style="line-height: 0.7;" > <em><strong>Especialidade:</strong></em> {{ (isset($Especialidade->nm_especialidade)) ? PrimeiraLetraMaiuscula($Especialidade->nm_especialidade) : '--' }}</td>
                    </tr>
                </table>
                <br>
                @if(trim($agendamento->anamnese))

                    <table width="99%" align="center">
                        <tr>
                            <td style="border-bottom :2px solid black;"> <em><strong>Anamnese</strong></em></td>
                        </tr>
                    </table>
                    <div style="margin-left: 15px; margin-top: 25px;   ">
                        {!!  HELPERcorrigiTexto($agendamento->anamnese) !!}
                    </div>
                @endif

                @if(trim($agendamento->exame_fisico) <> 'undefined')
                    <br><br>
                    <table width="99%" align="center">
                        <tr>
                            <td style="border-bottom :2px solid black;  "> <em><strong>Exame Fisico</strong></em></td>
                        </tr>
                    </table>
                    {!!  HELPERcorrigiTexto($agendamento->exame_fisico)   !!}
                @endif

                @if(trim($agendamento->hipotese_diagnostica) <> 'undefined')
                    <br><br>
                    <table width="99%" align="center">
                        <tr>
                            <td style="border-bottom :2px solid black;  "> <em><strong>Hipótese Diagnóstica</strong></em></td>
                        </tr>
                    </table>
                    {!! HELPERcorrigiTexto($agendamento->hipotese_diagnostica)   !!}
                @endif

                @if(trim($agendamento->conduta) <> 'undefined')
                    <br><br>
                    <table width="99%" align="center">
                        <tr>
                            <td style="border-bottom :2px solid black;  "> <em><strong>Conduta</strong></em></td>
                        </tr>
                    </table>
                    {!! HELPERcorrigiTexto($agendamento->conduta)  !!}
                @endif

            @endif

            @if($tipo=='documento')
            <div style="margin-left: 15px; margin-top: 30px; line-height: 0.7; ">
       
                {!! HELPERcorrigiTexto($documento->conteudo) !!}
  
            </div>

            @endif

        </main>
    </body>
</html>
