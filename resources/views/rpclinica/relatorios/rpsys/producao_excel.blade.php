 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            font-family: "Open Sans", sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }

   
        th, td { 
            text-align: left;
        }
    
        th {
            font-size: 13px;
        }
        td, p {
            font-size: 12px; 
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body> 
    <?php
 
    header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
    header ("Cache-Control: no-cache, must-revalidate");
    header ("Pragma: no-cache");
    header ("Content-type: application/x-msexcel");
    header ("Content-Disposition: attachment; filename=\"XLS_Producao".date('d_m_Y_H_i').".xls\"" );
    header ("Content-Description: RPclinic " );
     
   ?>
    <table border="1" >
        <thead>
            <tr class="active">
                <th> Atendimento</th>
                <th>  Data</th>
                <th>Paciente</th>
                <th>Convênio</th> 
                <th>Executante</th>
                <th>Exame</th> 
                <th>Situação</th> 
                <th>Data do Laudo</th> 
            </tr>
        </thead>
    
        <tbody>
           @foreach($dados['query'] as $key => $value)
            
                    <tr >
                        <td>{{ $value->cd_agendamento }}</td>
                        <td>{{ $value->data_atendimento }}</td>
                        <td>{{ $value->nm_paciente }}</td>
                        <td>{{ $value->nm_convenio }}</td>
                        <td>{{ $value->nm_profissional }}</td>
                        <td>{{ $value->exame?->nm_exame }}</td>
                        <td>{{ $value->nm_situacao_itens }}</td>
                        <td>{{ $value->data_laudo }}</td>
                    </tr> 
            @endforeach
    
        </tbody>
    </table>
     
</body>
</html>