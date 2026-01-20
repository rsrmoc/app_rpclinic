
@if($request['tipo']=='E')

    @if($request['situacao']=='bloqueado')

        <div class="hr-line-solid-no-margin"></div>
        <div style="font-size: 10px;">
            {!! ($request['icone']<>'undefined') ? $request['icone'] : '' !!}
            <b>Usuario:</b> {{ $request['user'] }}
            <br>
            <br>
            @if( (trim(($request['obs'])<>'null') ) && (trim(($request['obs'])<>'undefined') ))
            {{ $request['obs'] }}
            @endif
        </div>

    @else

        <div class="hr-line-solid-no-margin"></div>
        <div style="font-size: 10px;">
            {!! ($request['icone']<>'undefined') ? $request['icone'] : '' !!}
            {{ (($request['nm_paciente']=='null')||($request['nm_paciente']=='undefined')) ? '' :  $request['nm_paciente'] }}
            <br>
            @if( (trim(($request['dt_nasc'])<>'null') ) && (trim(($request['dt_nasc'])<>'undefined') ))
                {{ idadeAluno($request['dt_nasc']) }}
            @else
            --
            @endif

            <br>
            @if (($request['profissional']<>'null')||($request['profissional']))
                @if($request['profissional']<>'undefined')
                <i class="fa fa-user-md"></i>  {{ $request['profissional'] }}
                @endif
            @else
                --
            @endif
        </div>

    @endif

@endif





