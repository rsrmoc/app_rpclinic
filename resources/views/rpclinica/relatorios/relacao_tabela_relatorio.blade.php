@php

    function formatarItem($tipo, $item){
        if($tipo == 'dt'){
            return date('d/m/Y', strtotime($item));
        }

        if($tipo == 'hr'){
            return date('H:i', strtotime($item));
        }

        if($tipo == 'dthr'){
            return date('d/m/Y H:i', strtotime($item));
        }

        if($tipo == 'mo'){
            return 'R$ ' . number_format($item, 2, ',', '.');
        }

        return $item;
    }

@endphp 
 



<table class="table table-striped table-bordered"  >
    <thead>
        <tr>
            @foreach($colunas as $key => $value)
                <th style="text-align: center;  " >
                    {{ $key }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($dados_view as $value)
            <tr>
                @foreach($value as $key => $value)
                    <td style="text-align: {{ $colunas[$key]['alinhamento'] }};">
                        {{ formatarItem($colunas[$key]['mascara'], $value) }}
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
