@extends('rpclinica.layout.layout')

@section('content')

<style>
.report {
    box-sizing: border-box;
    position: relative;
    display: inline-block;
    padding: 6px;
    border: 1px solid #ced4dc;
    background-color: #e9edf2;
    background-repeat: no-repeat;
    color: #444;
    text-decoration: none;
    width: 100%;
    margin: 0 9px 9px 0;
    cursor: pointer;
    text-align: left;
    user-select: none;
    transition: background-color .1s ease-in;
}
.report .subtitle {
    opacity: .8;
}
.report .title {
    font-size: 1.2em;
    font-weight: 400;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.report .report-icon {
    color: #009b88;
    line-height: 36px;
    vertical-align: middle;
    font-size: 1.5em;
    margin-right: 12px;
    float: left;
    height: 100%;
}

.divInput {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.divInput input {
    width: 40%;
}

.divParam {
    min-height: 100px;
}
</style>

    <div class="page-title">
        <h3>{{ $relatorio->titulo }}</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('relatorios') }}">Relação de Relatórios</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    <form id="p_form" role="form" action="{{ route('relatorios.imprimir') }}" target="_blank" method="get" role="form">
                        <input type="hidden" name="relatorio_id" value="{{ $relatorio_id }}" />
                        @csrf

                        <div class="row">
                            @foreach ($parametros as $parametro)
                                <div class="col-sm-6 form-group divParam">
                                    <label class="form-label">{{ $parametro->nome_coluna }} ({{ $parametro->operador }}) <span class="text-danger">{{ $parametro->obrigatorio == 'S' ? '*' : '' }}</span> </label>
                                    @if($parametro->operador == 'between')
                                        <div class="divInput">
                                            <input type="date"  class="form-control inputParametro inicio {{ $parametro->obrigatorio == 'S' ? 'obrigatorio' : '' }}">
                                            Até
                                            <input type="date" class="form-control inputParametro fim {{ $parametro->obrigatorio == 'S' ? 'obrigatorio' : '' }}">
                                        </div>
                                    @else
                                        @if($parametro->cd_param_padrao=='cd_profissional') 
                                            <select class="form-control inputParametro"  {{ $parametro->obrigatorio == 'S' ? 'obrigatorio' : '' }}" style="width: 100%">
                                                <option value="">Selecione</option> 
                                                @foreach($profissional as $key => $value)
                                                <option value="{{$value->cd_profissional}}">{{$value->nm_profissional}}</option> 
                                                @endforeach
                                            </select> 
                                        @else 
                                            @if($parametro->cd_param_padrao=='cd_exame') 
                                                <select class="form-control inputParametro"  {{ $parametro->obrigatorio == 'S' ? 'obrigatorio' : '' }}" style="width: 100%">
                                                    <option value="">Selecione</option> 
                                                    @foreach($exame as $key => $value)
                                                    <option value="{{$value->cd_exame}}">{{$value->nm_exame}}</option> 
                                                    @endforeach
                                                </select> 
                                            @else 
                                                <input type="text" class="form-control inputParametro {{ $parametro->obrigatorio == 'S' ? 'obrigatorio' : '' }}">
                                            @endif
                                            
                                        @endif
                                        
                                    @endif
                                    <input hidden type="text" name="{{ $parametro->id }}" class="inputFinal">
                                    <div class="messageError text-danger" id="messageError_{{ $parametro->id }}"></div>
                                </div>
                            @endforeach
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success"> Enviar </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('p_form').addEventListener('submit', function(e) {
                let formValido = true;

                // Não precisa prevenir o comportamento padrão do formulário para permitir o envio normal após ajustes
                document.querySelectorAll('.form-group').forEach(function(group) {
                    var inputsObrigatorios = group.querySelectorAll('.obrigatorio');
                    inputsObrigatorios.forEach(function(input){
                        var idParametro = input.closest('.form-group').querySelector('.inputFinal').name;
                        var messageError = document.getElementById('messageError_' + idParametro);

                        messageError.textContent = '';

                        if(input.value.trim() === ''){
                            messageError.textContent = 'Campo obrigatório';
                            formValido = false;
                        }
                    })

                    const operador = group.querySelector('label').textContent.includes('between');
                    const inputHidden = group.querySelector('.inputFinal');
                    if (operador) {
                        const inputs = group.querySelectorAll('.inputParametro');
                        const inicio = inputs[0].value;
                        const fim = inputs[1].value;

                        if(inicio != '' && fim != ''){
                            inputHidden.value = `${inicio}, ${fim}`;
                        }
                    } else {
                        const inputParametro = group.querySelector('.inputParametro').value;
                        inputHidden.value = inputParametro;
                    }
                });

                if(!formValido){
                    e.preventDefault();
                }
            });
        });

    </script>
@endsection

