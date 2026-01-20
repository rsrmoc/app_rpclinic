<html>

<head>
    <style>
        @font-face {
            font-family: 'Journal';
            src: url({{ storage_path('fonts/Calibri.ttf') }}) format('truetype');
        }

        @page {
            margin: 60px 0px;
            margin-left: 35px;
            margin-right: 35px;
        }



        @font-face {
            font-family: 'Open Sans';
            font-style: normal;
            font-weight: normal;
            src: {{ storage_path('fonts/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf') }} format('truetype');
        }

        @font-face {
                font-family: 'OpenSans';
                font-style: normal;
                font-weight: normal;
                src: url({{ asset('assets/fonts/OpenSans-Regular.ttf') }}) format('truetype');
        }

    </style>
</head>

<body style="font-family: 'OpenSans';">
    <!-- Define header and footer blocks before your content -->
    <main style="font-family:'Courier New', Courier, monospace; font-size: 14px;">

        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;margin: 0px; padding: 0px;  ">
            <tr>
                <td width="8%" style=" margin: 0px; padding: 0px;">
                    @if($execultante['sn_logo']=='N')
                        <img src = "data:image/png;base64,{{ $execultante['logo'] }}"  height="40" style="  margin: 0px;padding-right: 15px;  " >
                    @endif
                </td>
                <td width="72%" valign="top" style=" margin: 0px; padding-top: 8px; text-align: left;"> <span
                        style="font-family: 'Courier New', Courier, monospace; font-style: italic; font-weight: 900; font-size: 1.8em;        ">Receituário
                        de Controle Especial</span>
                </td>
                <td width="20%" valign="top"
                    style="border:1px solid black; text-align: center; margin: 0px;padding: 0px; right: 0; height: 50px; font-size: 0.7em; font-weight: 900; ">Página 1 de 2

                </td>
            </tr>


        </table>

        <br><br>
        <table style="width: 100%; text-align: center; margin-bottom: 10px; font-size: 14px;" cellpadding="2"
            cellspacing="0">

            <tr>
                <td width="50%" valign="bottom">
                    <table width="100%" style="text-align: center"
                        style="text-align: center; border: 1px solid black; ">
                        <tr>
                            <td height="7"valign="bottom">
                                <b>IDENTIFICAÇÃO DO EMITENTE</b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="text-align: center"
                        style="text-align: left; border: 1px solid black; height: 100px; margin-top: 3px; height: 140px; "
                        cellpadding="4">
                        <tr>
                            <td valign="bottom" style="font-size: 1.0em;  ">
                                <span style="line-height: 20px; "><b>Nome: </b> {{ $execultante['nome'] }}
                                </span><br>
                                <span style="line-height: 20px; "><b>CRM: </b>
                                    {{ $execultante['conselho'] ? $execultante['conselho'] : '--' }} </span><br>
                                <span style="line-height: 20px; "><b>Endereço: </b>
                                    {{ $execultante['end_empresa'] }} </span><br>
                                <span style="line-height: 20px; "><b>Cnes: </b>
                                    {{ '--' }} </span><br>
                                <span style="line-height: 20px; "><b>Telefone: </b>
                                    {{ '--' }} </span><br>
                                <span style="line-height: 20px; "><b>Cidade: </b>
                                    {{ '--' }} </span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%" valign="bottom">
                    <table width="100%" style="text-align: center"
                        style="text-align: center; border: 1px solid black; ">
                        <tr>
                            <td height="7"valign="bottom">
                                <b>1° VIA FARMACIA
                                   @if($execultante['data']=='S')
                                   ___/___/______
                                   @else
                                   {{ date('d/m/Y') }}
                                   @endif
                                    </b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%"
                        style="text-align: left;   height: 100px; margin-top: 3px; height: 140px;border: 1px solid black;  " cellpadding="4">
                        <tr>
                            <td valign="bottom" style="font-size: 1.1em; font-weight: 900; text-align: center;">

                                <span style="font-size: 0.8em;">
                                    @if($execultante['sn_assinatura']=='S')
                                    <br>
                                    <img src = "data:{{ $execultante['tp_assinatura'] }};base64,{{ $execultante['assinatura'] }}"  style=" max-height: 80px;"  />
                                    @else
                                    <br><br><br><br><br>
                                   @endif
                                    _______________________________________________<br>
                                    {{ $execultante['nome'] ? $execultante['nome'] : '--' }} -
                                    {{ $execultante['conselho'] ? $execultante['conselho'] : '--' }}</span>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table><br>
        <span> <b>Paciente: </b> {{ $agendamento->paciente->nm_paciente}}</span><br>
        <span> <b>Sexo: </b>
            @if($agendamento->paciente->sexo=='H') Masculino @endif
            @if($agendamento->paciente->sexo=='M') Feminino @endif
        </span><br>
        <span> <b>Idade: </b> {{ idadeAluno($agendamento->paciente->dt_nasc) }}</span><br>
        <span> <b>Endereço: </b> --  </span><br>

        <table style="width: 100%; text-align: left; margin-top: 30px; margin-bottom: 30px; font-size: 14px; height: 400px" cellpadding="2"  cellspacing="0">
            <tr>
                <td> 
                    {!! HELPERformatTextoDocumento($documento->conteudo) !!}
                </td>
            </tr>
        </table>



        <table style="width: 100%; text-align: center; margin-bottom: 20px; font-size: 14px;" cellpadding="2"
            cellspacing="0">

            <tr>
                <td width="50%" valign="bottom">
                    <table width="100%" style="text-align: center"
                        style="text-align: center; border: 1px solid black; ">
                        <tr>
                            <td height="7"valign="bottom">
                                <b>IDENTIFICAÇÃO DO COMPRADOR</b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="text-align: center"
                        style="text-align: left; border: 1px solid black; height: 100px; margin-top: 3px; height: 145px; "
                        cellpadding="4">
                        <tr>
                            <td valign="bottom" style="font-size: 0.9em; font-weight: 900;">
                                <span style="line-height: 25px; "> Nome:________________________________________________
                                </span>
                                <span style="line-height: 25px; "> Ident.:_______________________
                                    Org.Emissror:____________ </span>
                                <span style="line-height: 30px; ">
                                    ______________________________________________________ </span>
                                <span style="line-height: 25px; "> Cidade:_______________________________
                                    UF:____________ </span>
                                <span style="line-height: 25px; ">
                                    Telefone:______________________________________________ </span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%" valign="bottom">
                    <table width="100%" style="text-align: center"
                        style="text-align: center; border: 1px solid black; ">
                        <tr>
                            <td height="7"valign="bottom">
                                <b>IDENTIFICAÇÃO DO FORNECEDOR</b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="text-align: center"
                        style="text-align: left; border: 1px solid black; height: 100px; margin-top: 3px; height: 145px; "
                        cellpadding="4">
                        <tr>
                            <td valign="bottom" style="font-size: 1.1em; font-weight: 900; text-align: center;">
                                <br> DATA: ____/____/_______<br><br><br><br>
                                <span style="font-size: 0.9em;">______________________________________<br>Assinatura do
                                    Farmacêutico</span>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </main>

    <main style="font-family:'Courier New', Courier, monospace; font-size: 14px;">

        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;margin: 0px; padding: 0px;  ">
            <tr>
                <td width="8%" style=" margin: 0px; padding: 0px;">
                    @if($execultante['sn_logo']=='N')
                        <img src = "data:image/png;base64,{{ $execultante['logo'] }}"  height="40" style="  margin: 0px;padding-right: 15px;  " >
                    @endif
                    </td>
                <td width="72%" valign="top" style=" margin: 0px; padding-top: 8px; text-align: left;"> <span
                        style="font-family: 'Courier New', Courier, monospace; font-style: italic; font-weight: 900; font-size: 1.8em;      ">Receituário
                        de Controle Especial</span>
                </td>
                <td width="20%" valign="top"
                    style="border:1px solid black; text-align: center; margin: 0px;padding: 0px; right: 0; height: 50px; font-size: 0.7em; font-weight: 900; ">Página 2 de 2

                </td>
            </tr>


        </table>

        <br><br>
        <table style="width: 100%; text-align: center; margin-bottom: 10px; font-size: 14px;" cellpadding="2"
            cellspacing="0">

            <tr>
                <td width="50%" valign="bottom">
                    <table width="100%" style="text-align: center"
                        style="text-align: center; border: 1px solid black; ">
                        <tr>
                            <td height="7"valign="bottom">
                                <b>IDENTIFICAÇÃO DO EMITENTE</b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="text-align: center"
                        style="text-align: left; border: 1px solid black; height: 100px; margin-top: 3px; height: 140px; "
                        cellpadding="4">
                        <tr>
                            <td valign="bottom" style="font-size: 1.0em;  ">
                                <span style="line-height: 20px; "><b>Nome: </b> {{ $execultante['nome'] }}
                                </span><br>
                                <span style="line-height: 20px; "><b>CRM: </b>
                                    {{ $execultante['conselho'] ? $execultante['conselho'] : '--' }} </span><br>
                                <span style="line-height: 20px; "><b>Endereço: </b>
                                    {{ $execultante['end_empresa'] }} </span><br>
                                <span style="line-height: 20px; "><b>Cnes: </b>
                                    {{ '--' }} </span><br>
                                <span style="line-height: 20px; "><b>Telefone: </b>
                                    {{ '--' }} </span><br>
                                <span style="line-height: 20px; "><b>Cidade: </b>
                                    {{ '--' }} </span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%" valign="bottom">
                    <table width="100%" style="text-align: center"
                        style="text-align: center; border: 1px solid black; ">
                        <tr>
                            <td height="7"valign="bottom">
                                <b>2° VIA PACIENTE
                                    @if($execultante['data']=='S')
                                    ___/___/______
                                    @else
                                    {{ date('d/m/Y') }}
                                    @endif
                                </b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%"
                        style="text-align: left;   height: 100px; margin-top: 3px; height: 140px;border: 1px solid black;  " cellpadding="4">
                        <tr>
                            <td valign="bottom" style="font-size: 1.1em; font-weight: 900; text-align: center;">

                                <span style="font-size: 0.8em;">
                                    @if($execultante['sn_assinatura']=='S')
                                    <br>
                                    <img src = "data:{{ $execultante['tp_assinatura'] }};base64,{{ $execultante['assinatura'] }}"  style=" max-height: 80px;"  />
                                    @else
                                    <br><br><br><br><br>
                                   @endif
                                    _______________________________________________<br>
                                    {{ $execultante['nome'] ? $execultante['nome'] : '--' }} -
                                    {{ $execultante['conselho'] ? $execultante['conselho'] : '--' }}</span>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table><br>
        <span> <b>Paciente: </b> {{ $agendamento->paciente->nm_paciente}}</span><br>
        <span> <b>Sexo: </b>
            @if($agendamento->paciente->sexo=='H') Masculino @endif
            @if($agendamento->paciente->sexo=='M') Feminino @endif
        </span><br>
        <span> <b>Idade: </b> {{ idadeAluno($agendamento->paciente->dt_nasc) }}</span><br>
        <span> <b>Endereço: </b> --  </span><br>

        <table style="width: 100%; text-align: left; margin-top: 30px; margin-bottom: 30px; font-size: 12px; height: 400px" cellpadding="2"  cellspacing="0">
            <tr>
                <td> 
                    {!! HELPERformatTextoDocumento($documento->conteudo) !!}
                    
                </td>
            </tr>
        </table>



        <table style="width: 100%; text-align: center; margin-bottom: 20px; font-size: 14px;" cellpadding="2"
            cellspacing="0">

            <tr>
                <td width="50%" valign="bottom">
                    <table width="100%" style="text-align: center"
                        style="text-align: center; border: 1px solid black; ">
                        <tr>
                            <td height="7"valign="bottom">
                                <b>IDENTIFICAÇÃO DO COMPRADOR</b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="text-align: center"
                        style="text-align: left; border: 1px solid black; height: 100px; margin-top: 3px; height: 145px; "
                        cellpadding="4">
                        <tr>
                            <td valign="bottom" style="font-size: 0.9em; font-weight: 900;">
                                <span style="line-height: 25px; "> Nome:________________________________________________
                                </span>
                                <span style="line-height: 25px; "> Ident.:_______________________
                                    Org.Emissror:____________ </span>
                                <span style="line-height: 30px; ">
                                    ______________________________________________________ </span>
                                <span style="line-height: 25px; "> Cidade:_______________________________
                                    UF:____________ </span>
                                <span style="line-height: 25px; ">
                                    Telefone:______________________________________________ </span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%" valign="bottom">
                    <table width="100%" style="text-align: center"
                        style="text-align: center; border: 1px solid black; ">
                        <tr>
                            <td height="7"valign="bottom">
                                <b>IDENTIFICAÇÃO DO FORNECEDOR</b>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" style="text-align: center"
                        style="text-align: left; border: 1px solid black; height: 100px; margin-top: 3px; height: 145px; "
                        cellpadding="4">
                        <tr>
                            <td valign="bottom" style="font-size: 1.1em; font-weight: 900; text-align: center;">
                                <br> DATA: ____/____/_______<br><br><br><br>
                                <span style="font-size: 0.9em;">______________________________________<br>Assinatura do
                                    Farmacêutico</span>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </main>
</body>

</html>
