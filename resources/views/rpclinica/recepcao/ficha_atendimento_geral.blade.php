<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}' rel="stylesheet" type="text/css" />
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0px;
            margin-bottom: 0px;
            padding: 0px;
            padding-top: 3px; 
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
    <div style="border: 2px solid black; margin: 10px; height: 480px; margin-top: 20px; margin-left: 20px; margin-right: 20px; "> 

        <table width="100%" border="0" cellpadding="3" cellspacing="3" width="100%" style="font-size: 14px; ">
            <tr>
                <td valign="top" width="20%" style="text-align: left; ">
                    <img src = "data:{{$empresa->type_logo}};base64,{{$empresa->logo}}" style="height: 60px"> 
                </td> 
                <td valign="top" width="65%" style="font-size: 16px; text-align: center; font-weight:bold; line-height: 18px;   "> 
                    <span style="font-size: 17px;">{{formatPrimeiraLetraMaiuscula($empresa->nm_empresa)}}</span> <br>
                    <span style="font-size: 16px; font-style: italic;">Boletim de Atendimento Ambulatorial</span>
                </td>   
                <td valign="top" width="15%" style="text-align: center; ">
                    <img src="{{ asset('assets/barcode/barcode.php?codetype=Code39&size=30&text='.$dados->cd_agendamento.'&print=true') }}" />
                </td> 
            </tr> 
           
        </table>
        <hr style="border-top: 1px solid black; margin-bottom: 6px; margin-top: 0px; padding-bottom: 0px;">

        <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
               style="font-size: 14px; padding: 0px; padding-left: 4px;">
            <tr>
                <td width="82%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Paciente: </b> <span style="font-weight: normal; ">  {{ formatPrimeiraLetraMaiuscula($dados->paciente?->nm_paciente) }} ( {{$dados->paciente?->cd_paciente}} )</span> 
                </td>  
                <td width="18%" bgcolor="#cacaca" style="text-align: center; font-size: 13px; line-height: 17px;">
                     <span style="font-weight: bold; ">{{formatDate($dados->dt_agenda)}}</span> 
                </td>  
            </tr> 
        </table>

        <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
               style="font-size: 14px; padding: 0px; padding-left: 4px;">
            <tr>
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Mãe: </b> <span style="font-weight: normal; ">{{($dados->paciente?->nm_mae) ? $dados->paciente?->nm_mae : ' -- '}} </span> 
                </td>  
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Pai: </b> <span style="font-weight: normal; ">{{ ($dados->paciente?->nm_pai) ? $dados->paciente?->nm_pai : ' -- ' }} </span> 
                </td> 
            </tr> 
        </table>
 
        <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
               style="font-size: 14px; padding: 0px; padding-left: 4px;">
            <tr>
                <td width="70%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Data Nasc.: </b> <span style="font-weight: normal; ">{{ formatDate($dados->paciente?->dt_nasc) }} ({{ idadeAluno($dados->paciente?->dt_nasc) }})  @if($dados->paciente?->sexo=='H') - ( Masculino )  @else @if($dados->paciente?->sexo=='M') - ( Feminino ) @endif  @endif </span> 
                </td>  
                <td width="30%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>RG: </b><span style="font-weight: normal; "> {{ $dados->paciente?->rg }} </span> 
                </td> 
            </tr> 
        </table>

        <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
               style="font-size: 14px; padding: 0px; padding-left: 4px;">
            <tr>
                <td width="30%" style="text-align: left; 13px; line-height: 17px;">
                    <b>CPF.: </b> <span style="font-weight: normal; "> {{ $dados->paciente?->cpf }} </span> 
                </td>  
                <td width="45%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Email: </b><span style="font-weight: normal; "> {{ $dados->paciente?->email }} </span> 
                </td> 
                <td width="25%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>CEP: </b><span style="font-weight: normal; "> {{ $dados->paciente?->cep }} </span> 
                </td> 
            </tr> 
        </table>

         <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
               style="font-size: 14px; padding: 0px; padding-left: 4px;">
            <tr>
                <td width="100%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Endereço: </b> <span style="font-weight: normal; ">{{ $dados->paciente?->logradouro.'   '.$dados->paciente?->numero.'   '.$dados->paciente?->bairro.'   '.$dados->paciente?->cidade }} </span> 
                </td>   
            </tr> 
        </table>

        <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
               style="font-size: 14px; padding: 0px; padding-left: 4px;">
            <tr>
                <td width="40%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Telefone: </b> <span style="font-weight: normal; ">{{ $dados->paciente?->fone }} </span> 
                </td>  
                <td width="60%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Profissisão: </b> <span style="font-weight: normal; ">{{ $dados->paciente?->profissao }}</span> 
                </td> 
            </tr> 
        </table>

        <table width="200" border="0" cellpadding="3" cellspacing="3" width="100%" 
        style="font-size: 14px; padding: 0px; padding-left: 4px;">
            <tr>
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Prof.Solicitante: </b> <span style="font-weight: normal; ">{{ ($dados->profissional_externo?->nm_profissional_externo) ? formatPrimeiraLetraMaiuscula($dados->profissional_externo?->nm_profissional_externo) : ' -- ' }} </span> 
                </td>  
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Prof.Executante: </b> <span style="font-weight: normal; ">{{ formatPrimeiraLetraMaiuscula($dados->profissional?->nm_profissional) }}</span> 
                </td> 
            </tr> 
            <tr>
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Local de Atend.: </b> <span style="font-weight: normal; ">{{ ($dados->local?->nm_local) ? formatPrimeiraLetraMaiuscula($dados->local?->nm_local) : ' -- ' }} </span> 
                </td>  
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Tipo de Atend.: </b> <span style="font-weight: normal; ">{{ ($dados->tipo_atend?->nm_tipo_atendimento) ? formatPrimeiraLetraMaiuscula($dados->tipo_atend?->nm_tipo_atendimento) : ' -- ' }}</span> 
                </td> 
            </tr> 
            <tr>
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Especialidade: </b> <span style="font-weight: normal; ">{{ ($dados->especialidade?->nm_especialidade) ? formatPrimeiraLetraMaiuscula($dados->especialidade?->nm_especialidade) : ' -- ' }} </span> 
                </td>  
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Caráter Atend.: </b> <span style="font-weight: normal; ">{{ ($dados->carater) ? $dados->carater : ' -- ' }}</span> 
                </td> 
            </tr> 
            <tr>
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Prof.Solicitante: </b> <span style="font-weight: normal; ">{{ ($dados->profissional_externo?->nm_profissional_externo) ? formatPrimeiraLetraMaiuscula($dados->profissional_externo?->nm_profissional_externo) : ' -- ' }} </span> 
                </td>  
                <td width="50%" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Prof.Executante: </b> <span style="font-weight: normal; ">{{ formatPrimeiraLetraMaiuscula($dados->profissional?->nm_profissional) }}</span> 
                </td> 
            </tr> 
            <tr>
                <td width="100%" colspan="2" style="text-align: left; font-size: 13px; line-height: 17px;">
                    <b>Convênio: </b> <span style="font-weight: normal; "> {{ formatPrimeiraLetraMaiuscula($dados->convenio?->nm_convenio) }} </span> 
                </td>  
               
            </tr> 
            @if($empresa->recibo_atendimento_portal=='S')
                <tr>
                    <td width="100%" colspan="2" style="text-align: left; font-size: 14px; line-height: 17px;">
                        <b style="background: #cacaca; padding-bottom: 1px; padding-left: 2px;">Link do Portal: </b> <span style="font-weight: normal; ">{!! (isset($empresa->link_portal)) ? $empresa->link_portal : ' <i> Não Informado</i> ' !!} </span> 
                    </td>   
                </tr> 
                <tr  >
                    <td width="50%" style="text-align: left; font-size: 14px; line-height: 17px;">
                        <b style="background: #cacaca; padding-bottom: 1px; padding-left: 2px; padding-top: 1px;">Usuario: </b> <span style="font-weight: normal; "> {!! (isset($dados->usuario_portal->cd_usuario )) ? $dados->usuario_portal->cd_usuario : ' <i> Não Informado</i> ' !!} </span> 
                    </td>  
                    <td width="50%" style="text-align: left; font-size: 14px; line-height: 17px;">
                        <b style="background: #cacaca; padding-bottom: 1px; padding-left: 2px;">Senha: </b> <span style="font-weight: normal; "> {!! (isset($dados->usuario_portal->senha_pura)) ? $dados->usuario_portal->senha_pura : ' <i> Não Informado</i> ' !!}</span> 
                    </td> 
                </tr> 
            @endif 
        </table>
         
     

        <table width="200" border="0" cellpadding="0" cellspacing="0" width="100%" 
                style="border-top: 1px solid black; min-height: 150px;" >
            <tr>
                <td  style="text-align: left; font-size: 11px; line-height: 14px; background: #cacaca">
                    <b>Proced. </b> 
                </td>  
                <td  style="text-align: left; font-size: 11px; line-height: 14px;background: #cacaca">
                    <b>Descrição </b> 
                </td>   
                <td  style="text-align: left; font-size: 11px; line-height: 14px;background: #cacaca">
                    <b>Olho </b> 
                </td>  
                <td  style="text-align: left; font-size: 11px; line-height: 14px;background: #cacaca">
                    <b>Qtde. </b> 
                </td>   
                <td  style="text-align: left; font-size: 11px; line-height: 14px;background: #cacaca">
                    <b>Valor </b> 
                </td>
            </tr> 
            @foreach($dados->itens_pedente as $key => $item)
                
                <tr> 
                    <td  style="text-align: left; font-size: 11px; line-height: 13px; ">
                        {{   '( '.$item->exame?->cd_exame.' ) '  }} {{ $item->exame?->cod_proc }}
                    </td>  
                    <td  style="text-align: left; font-size: 11px; line-height: 12px; ">
                        {{ formatPrimeiraLetraMaiuscula($item->exame?->nm_exame) }}
                    </td>     
                    <td  style="text-align: left; font-size: 11px; line-height: 12px; ">
                        --
                    </td>  
                    <td  style="text-align: left; font-size: 11px; line-height: 12px; ">
                        1
                    </td>   
                    <td  style="text-align: left; font-size: 11px; line-height: 12px; ">
                        @if($empresa->recibo_atendimento_valor=='S')  {{ $item->vl_item }} @else -- @endif
                    </td>
                </tr> 
           
            @endforeach
        </table>
        
        <table  cellpadding="3" cellspacing="3" width="100%" 
        style="font-size: 14px; padding: 0px; padding-left: 4px;">
            <tr>
                <td width="100%" style="text-align: left; font-size: 12px; text-align: center; font-style: italic; line-height: 15px;">
                    _____________________________________________________<br>Assinatura do Paciente/Responsável   
                </td>  

 
               
            </tr> 
        </table>
         
       
        
        <br>
         
    </div>



</body>
</html>


