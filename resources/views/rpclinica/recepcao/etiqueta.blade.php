<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}' rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: 'Courier New', Courier, monospace;
            margin: 0px;
            margin-bottom: 0px;
            padding: 0px;
            padding-top: 2px;
        } 
        table {
            width: 100%;
            border-collapse: collapse;
        }

        

        th, td {
            padding: 1px;
            text-align: left;
            line-height: 11px;
        }
    </style>
</head>
<body> 
   
    <table width="200" border="0" width="100%" style="font-size: 14px; padding: 0px;">
        <tr>
            @if($empresa->mini_logo)

                <td   style="text-align: center" style="padding: 0px;">  
                    <img height="30" src = "data:{{$empresa->type_mini_logo}};base64,{{$empresa->mini_logo}}" > 
                </td>  

            @endif
            <td  style="font-size: 17px; text-align: left;   line-height: 20px;  text-transform: uppercase;   font-weight:bold;  "> 
                {{ substr(formatPrimeiraLetraMaiuscula($dados->paciente?->nm_paciente),0,35)}} <br>
                <span style="font-size: 16px; letter-spacing: inherit;">
                    <b>IDADE: </b> <span style="font-weight: normal; ">{{ ($dados->paciente?->dt_nasc) ? idadeAluno($dados->paciente?->dt_nasc) : '--' }} </span> 
                     
                    DATA: <span style="font-weight: normal; ">{{formatDate($dados->dt_agenda)}}</span> 
                </span>
            </td>  
        </tr>
    </table>

    <table width="200" border="0" width="100%" style="font-size: 14px; padding: 0px;">
        <tr>
            <td width="100%" style="text-align: left; font-size: 17px; line-height: 15px;">
                <b>PROFIS.: </b> <span style="font-weight: normal; ">{{ $dados->paciente?->profissao }}</span> 
            </td>  
        </tr> 
    </table>

    <table width="200" border="0" width="100%" style="font-size: 14px; padding: 0px;">
        <tr>
            <td width="40%" style="text-align: left; font-size: 17px; line-height: 15px;">
                <b>ATEND.: </b> <span style="font-weight: normal; ">{{ $dados->cd_agendamento }}</span> 
            </td> 
            <td width="60%" style="text-align: left;font-size: 17px; line-height: 15px;">
                <b>CONV.: </b> <span style="font-weight: normal; ">{{ $dados->convenio?->nm_convenio }}</span> 
            </td> 
        </tr> 
    </table>
 
    <table width="200" border="0" width="100%" style="font-size: 14px; padding: 0px;">
        <tr>
            <td width="100%" style="text-align: left; font-size: 17px; line-height: 15px;">
                <b>TP.ATEND.: </b> <span style="font-weight: normal; ">{{ $dados->tipo_atend?->nm_tipo_atendimento }}</span> 
            </td>  
        </tr> 
    </table>

    <table width="200" border="0" width="100%" style="font-size: 14px; padding: 0px;">
        <tr>
            <td width="100%" style="text-align: left; font-size: 17px; line-height: 15px;">
                <b>LADO: </b> <span style="font-weight: normal; ">--</span> 
            </td>  
        </tr> 
    </table>

    <table width="200" border="0" width="100%" style="font-size: 14px; padding: 0px;">
        <tr>
            <td width="100%" style="text-align: left; font-size: 17px; line-height: 15px;">
                <b>MÉDICO: </b> <span style="font-weight: normal; "> {{ substr(formatPrimeiraLetraMaiuscula($dados->profissional?->nm_profissional),0,35) }}</span> 
            </td>  
        </tr> 
    </table>



</body>
</html>


