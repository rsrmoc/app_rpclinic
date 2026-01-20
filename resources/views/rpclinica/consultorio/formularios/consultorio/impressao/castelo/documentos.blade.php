@include('rpclinica.consultorio.formularios.consultorio.impressao.castelo.includes.header_footer_padrao', $relatorio)


<main style="font-family:'OpenSans' ; font-size: 14px; ">

    @if($execultante['sn_ocultar_titulo']=='S')
    <p
        style="font-family: 'OpenSans'; font-style: italic; font-weight: 900; font-size: 2em; margin: 0px; color: #2b2f35; text-align: center; padding: 0px; /*position: fixed;      transform: translate(-50%, -50%); */  ">
        {{ $TpDocumento }}
    </p>
    @else 
        <br>
    @endif 

    @if ($tipo == 'anamnese')

        <table width="99%" align="center" style="margin-top: 15px;">
          
            <tr>
                <td width="60%" style="line-height: 0.7;"> <em><strong>Prestador:</strong></em>
                    {{ $agendamento->profissional->nm_profissional ? PrimeiraLetraMaiuscula($agendamento->profissional->nm_profissional) : '--' }}
                </td>
                <td width="40%" style="line-height: 0.7;"> <em><strong>Data do Atendimento:</strong></em>
                    {{ $agendamento->dt_agenda ? date_format(date_create($agendamento->dt_agenda), 'd/m/Y') : '--' }}
                </td>
            </tr>
            <tr>
                <td width="60%" style="line-height: 0.7;"> <em><strong>Convênio:</strong></em>
                    {{ isset($agendamento->convenio->nm_convenio) ? PrimeiraLetraMaiuscula($agendamento->convenio->nm_convenio) : '--' }}</td>
                <td width="40%" style="line-height: 0.7;"> <em><strong>Especialidade:</strong></em>
                    {{ isset($agendamento->especialidade->nm_especialidade) ? PrimeiraLetraMaiuscula($agendamento->especialidade->nm_especialidade) : '--' }}
                </td>
            </tr>
        </table>
        <br>
 
        @if ($agendamento->historia_pregressa)
                <br>
                <table width="99%" align="center">
                    <tr>
                        <td style=" padding-top: 8px; padding-bottom: 8px; padding-left: 15px; font-style: italic; font-size: 17px; background: #bebdbd; ">  <strong>Historia Pregressa</strong> </td>
                    </tr>
                </table>
                <div style="margin-left: 15px; margin-top: 5px;  line-height: 0.9; font-size: 12px; ">
                    {!!  HELPERformatTextoDocumento($agendamento->historia_pregressa)  !!}
                </div>
        @endif
        
        @if ($agendamento->anamnese)
            <br>
            <table width="99%" align="center">
                <tr>
                    <td style=" padding-top: 8px; padding-bottom: 8px; padding-left: 15px; font-style: italic; font-size: 17px; background: #bebdbd; ">  <strong>Anamnese</strong> </td>
                </tr>
            </table>
            
            <div style="margin-left: 15px; margin-top: 5px;  line-height: 0.9; font-size: 12px; ">
                {!! HELPERformatTextoDocumento($agendamento->anamnese) !!}
            </div>
        @endif

        @if ($agendamento->exame_fisico)
            <br>  
            <table width="99%" align="center">
                <tr>
                    <td style=" padding-top: 8px; padding-bottom: 8px; padding-left: 15px; font-style: italic; font-size: 17px; background: #bebdbd; ">  <strong>Exame Fisico</strong> </td>
                </tr>
            </table>
            <div style="margin-left: 15px; margin-top: 5px; line-height: 0.9; font-size: 12px; ">
                {!! HELPERformatTextoDocumento($agendamento->exame_fisico) !!}
            </div>
        @endif

        @if ($agendamento->hipotese_diagnostica)
            <br>  
            <table width="99%" align="center">
                <tr>
                    <td style=" padding-top: 8px; padding-bottom: 8px; padding-left: 15px; font-style: italic; font-size: 17px; background: #bebdbd; ">  <strong>Hipótese Diagnóstica</strong> </td>
                </tr>
            </table>
            <div style="margin-left: 15px; margin-top: 5px;  line-height: 0.9; font-size: 12px; ">
                {!!  HELPERformatTextoDocumento($agendamento->hipotese_diagnostica) !!}
            </div>
        @endif

        @if ($agendamento->conduta)
            <br>  
            <table width="99%" align="center">
                <tr>
                    <td style=" padding-top: 8px; padding-bottom: 8px; padding-left: 15px; font-style: italic; font-size: 17px; background: #bebdbd; ">  <strong> Conduta</strong> </td>
                </tr>
            </table>
            <div style="margin-left: 15px; margin-top: 5px; line-height: 0.9; font-size: 12px; ">
            {!!  HELPERformatTextoDocumento($agendamento->conduta) !!}
            </div>
        @endif

    @endif

    @if ($tipo == 'documento')
        <div style="margin-left: 15px; margin-top: 60px; line-height: 0.9; font-size: 12px; ">

            {!!  HELPERformatTextoDocumento($documento->conteudo) !!}

        </div>
    @endif

</main>
</body>

</html>
