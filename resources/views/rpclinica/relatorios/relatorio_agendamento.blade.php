<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Relatório de Movimento de Agendamento    </title>
<link rel="shortcut icon" href="imagens/favicon.ico" type="image/x-icon" />
</head>
<style>
    /* Dark Theme Styles for Screen Viewing */
    body {
        background-color: #0f172a !important; /* Dark Slate Background */
        color: #e2e8f0 !important; /* Light Slate Text */
    }
    table {
        color: #e2e8f0 !important;
    }
    td, th {
        border-color: #334155 !important; /* Darker borders */
    }
    /* Specific borders that were black */
    tr[style*="border-bottom:2px solid #000000"] td {
        border-bottom: 2px solid #e2e8f0 !important;
    }
    td[style*="border-bottom:1px solid #000000"] {
        border-bottom: 1px solid #334155 !important;
    }

    /* Print Styles - Reset to White/Black for Printing */
    @media print {
        body {
            background-color: white !important;
            color: black !important;
        }
        table {
            color: black !important;
        }
        td, th {
            border-color: black !important;
        }
        tr[style*="border-bottom:2px solid #000000"] td {
            border-bottom: 2px solid #000000 !important;
        }
        td[style*="border-bottom:1px solid #000000"] {
            border-bottom: 1px solid #000000 !important;
        }
    }
</style>

<body style="font-family:Arial, Helvetica, sans-serif;">

<table width="1000" border="0" style="font-size:16px;" cellspacing="0" cellpadding="0">
  <tr>
    <td width="40%" height="15" style="font-size:13px;">{{ mb_strtoupper($empresa->nm_empresa) }}</td>
    <td width="20%">&nbsp;</td>
    <td width="40%"><div align="right"></div></td>
  </tr>
  <tr>
    <td width="40%" height="15" style="font-size:13px;">RPCLINIC - MOVIMENTO DE AGENDAMENTO</td>
    <td width="20%">&nbsp;</td>
    <td width="40%"><div align="right">Emitido por: {{ Auth::user()->email }}</div></td>
  </tr>
  <tr style="border-bottom:2px solid #000000;">
    <td width="40%" height="15" style="font-size:13px; border-bottom:2px solid #000000;">Relatório Movimento de Agendamento</td>
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
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><strong>Celular</strong></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><strong>Profissional</strong></td>
          <td style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><div align=""><strong>Convênio </strong></div></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><div align="left"><strong>Tipo</strong></div></td>
          <td  style=" border-bottom:1px solid #000000; height:20px;font-size:13px;"><div align="left"><strong>Situação</strong></div></td>
        </tr>
        @foreach ($relatorio as $INDEX => $val )
          <tr >
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{ $val['cd_agendamento'] }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{  \Carbon\Carbon::parse($val['dt_agenda'])->format('d/m/Y') }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{  mb_strtoupper(substr($val->paciente?->nm_paciente,0,20)) }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
              {{  mb_strtoupper(substr($val->paciente?->celular,0,20)) }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{ mb_strtoupper( substr($val->profissional?->nm_profissional,0,20)) }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{ mb_strtoupper( substr($val->convenio?->nm_convenio,0,15) ) }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{ mb_strtoupper(  $val->tipo) }}
            </div></td>
            <td style="height:20px;font-size:12px; border-bottom:1px solid #000000;"><div align="left">
                {{ mb_strtoupper(  $val->situacao) }}
            </div></td>
          </tr>
        @endforeach
        <tr >
            <td colspan="8" style="height:20px;font-size:15px; font-weight: bold; border-bottom:1px solid #000000;"><div align="left">
               Total de Atendimentos : {{ (isset($INDEX)) ? $INDEX : 0 }}
            </div></td>
        </tr>
      </table>

    </div></td>
  </tr>
</table>

<BR/><BR/><BR/>
</body>
</html>
