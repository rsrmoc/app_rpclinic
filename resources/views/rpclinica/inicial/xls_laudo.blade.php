<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>XLS</title>
</head>

<body>
<?php
 
 header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
 header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
 header ("Cache-Control: no-cache, must-revalidate");
 header ("Pragma: no-cache");
 header ("Content-type: application/x-msexcel");
 header ("Content-Disposition: attachment; filename=\"XLS_Laudos".date('d_m_Y_H_i').".xls\"" );
 header ("Content-Description: RPclinic " );
  
?>
 <style type="text/css">
    .blue {
        color: #0072c6 !important;
    }
    table{
        text-transform: uppercase;
    }
    .center{ text-align: center; }
    .right{ text-align: right;}
    .left{ text-align: left;}
	.red {
		color: red;
	}
</style> 
 <?php 
				 echo '
					 <table cellpadding="0" style="font-family: verdana,arial; font-size: 11px; text-align: left;" cellspacing="2" border="1">
					 <thead> 
					 <tr>
						  <th height="30" bgcolor="#E2E2E2">ATENDIMENTO</th>
						  <th height="30" bgcolor="#E2E2E2">DATA DO ATENDIMENTO</th>
						  <th height="30" bgcolor="#E2E2E2">PACIENTE</th>
						  <th height="30" bgcolor="#E2E2E2">CPF DO PACIENTE</th>
						  <th height="30" bgcolor="#E2E2E2">NASCIMENTO DO PACIENTE</th>
						  <th height="30" bgcolor="#E2E2E2">PROFISSIONAL</th>
						  <th height="30" bgcolor="#E2E2E2">CODIGO DO ITEM</th>
						  <th height="30" bgcolor="#E2E2E2">EXAME</th>
						  <th height="30" bgcolor="#E2E2E2">DATA DO LAUDO </th>   
					 </tr> 
					 </thead>
					 <tbody>
				 ';
		  
				 foreach ($query as $val) { 
					echo ' 
					<tr> 
					   <td>'.mb_strtoupper($val->cd_agendamento).'</td>
					   <td>'.mb_strtoupper($val->dt_atendimento).'</td>
					   <td>'.mb_strtoupper($val->paciente?->nm_paciente).' </td> 
					   <td>'.mb_strtoupper($val->paciente?->cpf).'</td>  
					   <td>'.mb_strtoupper($val->paciente?->dt_nasc).'</td> 
					   <td>'.mb_strtoupper($val->profissional?->nm_profissional).'</td> 
					   <td>'.mb_strtoupper($val->cd_agendamento_item).'</td> 
					   <td>'.mb_strtoupper($val->nm_exame).'</td> 
					   <td>'.mb_strtoupper($val->data_laudo).'</td>  
					</tr>  
					'; 
 



				 }
				 
	
 ?>
</body>
</html>
