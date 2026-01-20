<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Relatório de Movimento de Agendamento Particular    </title>
<link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon" />
<style>
    body {
        background: radial-gradient(circle at top left, #1e293b, #0f172a) !important;
        color: #e2e8f0 !important;
    }
    table, td, tr {
        color: #e2e8f0 !important;
        border-color: #475569 !important;
    }
    .page-title, h1, h2, h3, h4, h5 {
        color: #f1f5f9 !important;
    }
    @media print {
        body {
            background: white !important;
            color: black !important;
        }
        table, td, tr {
            color: black !important;
            border-color: #000 !important;
        }
    }
</style>
</head>

<body style="font-family:Arial, Helvetica, sans-serif;">

<table width="1000" border="0" style="font-size:16px;" cellspacing="0" cellpadding="0">
  <tr>
    <td width="40%" height="15" style="font-size:13px;">{{ mb_strtoupper($empresa->nm_empresa) }}</td>
    <td width="20%">&nbsp;</td>
    <td width="40%"><div align="right"></div></td>
  </tr>
  <tr>
    <td width="40%" height="15" style="font-size:13px;">RPCLINIC - MOVIMENTO DE ATENDIMENTO PARTICULAR</td>
    <td width="20%">&nbsp;</td>
    <td width="40%"><div align="right">Emitido por: {{ Auth::user()->email }}</div></td>
  </tr>
  <tr style="border-bottom:2px solid #000000;">
    <td width="40%" height="15" style="font-size:13px; border-bottom:2px solid #000000;">Relatório Movimento  Particular</td>
    <td width="20%" style="border-bottom:2px solid #000000;font-size:13px;"><div align="center">Periodo:
        {{  \Carbon\Carbon::parse($request['dti'])->format('d/m/y') }} até {{  \Carbon\Carbon::parse($request['dtf'])->format('d/m/y') }}
    </div></td>
    <td width="40%" style="border-bottom:2px solid #000000;"><div align="right">Em :
      <?=date('d/m/Y h:i')?>
    </div></td>
  </tr>
  <tr>
    <td height="25" colspan="3" style="font-size:13px;">&nbsp;</td>
  </tr>
  <tr>
    <td height="15" colspan="3" style="font-size:13px;"><div align="right">

     <table width="100%" border="0" cellspacing="0" cellpadding="0">


        <tr >
          <td style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><strong>Atendimento</strong></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><strong>Data</strong></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><strong>Paciente</strong></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><strong>Profissional</strong></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><div align="right"><strong>Desconto</strong></div></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><div align="right"><strong>Acrescimo</strong></div></td>
          <td style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><div align="right"><strong>Valor </strong></div></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><div align="left"><strong>&nbsp;&nbsp;Recebido</strong></div></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><div align="left"><strong>Situação</strong></div></td>
        </tr>
        @php
            $Total=0; $Desconto=0; $Acrescimo=0;
        @endphp
        @foreach ($relatorio as $INDEX => $val )
        @php
/*
            $Desc = (trim($val->vl_desconto)) ? $val->vl_desconto : 0;
            $Acres = (empty($val->vl_acrescimo)) ? 0 : $val->vl_acrescimo;
            $vl = (empty($val->valor)) ? 0 : $val->valor;
            $Total=($vl+$Total);
            $Desconto=($Desc+$Desconto);
            $Acrescimo=($Acres+$Acrescimo);
        */
        @endphp
          <tr >
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{ $val['cd_agendamento'] }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{  \Carbon\Carbon::parse($val['dt_agenda'])->format('d/m/Y') }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{  mb_strtoupper(substr($val->paciente?->nm_paciente,0,30)) }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div  align="left">
                {{ mb_strtoupper( substr($val->profissional?->nm_profissional,0,25)) }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="right">
                {{  'R$ '.number_format($val->vl_desconto, 2, ',', '.') }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="right">
                {{  'R$ '.number_format($val->vl_acrescimo, 2, ',', '.') }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="right">
                {{ $val->valor }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                &nbsp;&nbsp;{{  ($val->recebido == true) ? 'SIM' : 'NÃO' }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{ mb_strtoupper(  $val->situacao) }}
            </div></td>
          </tr>
        @endforeach
        <tr >
            <td colspan="2" style="height:20px;font-size:15px; font-weight: bold; border-bottom:1px solid #000000;"><div align="center">
             Atendimentos <br> {{ (isset($INDEX)) ? $INDEX : 0 }}
            </div></td>
            <td colspan="2" style="height:20px;font-size:15px; font-weight: bold; border-bottom:1px solid #000000;"><div align="center">
                Valor Total <br>  {{  'R$ '.number_format($Total, 2, ',', '.') }}
             </div></td>
             <td colspan="2" style="height:20px;font-size:15px; font-weight: bold; border-bottom:1px solid #000000;"><div align="center">
             Descontos <br>  {{  'R$ '.number_format($Desconto, 2, ',', '.') }}
             </div></td>
             <td colspan="3" style="height:20px;font-size:15px; font-weight: bold; border-bottom:1px solid #000000;"><div align="center">
                Acrescimos <br>  {{  'R$ '.number_format($Acrescimo, 2, ',', '.') }}
                </div></td>

        </tr>
      </table>

    </div></td>
  </tr>
</table>

<BR/><BR/><BR/>
</body>
</html>
