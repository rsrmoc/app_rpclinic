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

        table, th, td {
            border: 1px solid black;
        }

        th, td { 
            text-align: left;
        }
        h1 {
            font-size: 22px;
            text-align: center;
            margin-bottom: 5px;
            padding-bottom: 0px;
        }
        h4 {
            font-size: 10px;
            text-align: center;
            font-weight: 400;
            margin-top: 5px;
            padding-top: 0px;
        }
        th {
            font-size: 11px;
        }
        td, p {
            font-size: 11px;
            line-height: 8px;
            padding-left: 3px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Relatório De Produção</h1>
    <h4>{{ $parametros }}</h4>

    
    <table >
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
    
    <p style="margin-top: 10px;">
        Gerado em: {{ date('d/m/Y H:i') }}
    </p>
</body>
</html>