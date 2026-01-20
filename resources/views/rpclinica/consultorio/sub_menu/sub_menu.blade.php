
<div id="basicTree">
    <ul> 
 
        <li data-jstree='{"opened":true}'>Pré-Exames
            <ul> 
                @foreach ($formularios as $formulario)
                    @if($formulario->tipo === 'PRE_EXAME')
                        <li data-jstree='{"type":"file"}' x-on:click="forms('{{ $formulario->cd_formulario }}')"> {{ $formulario->nm_formulario }}  </li>  
                    @endif 
                @endforeach 
            </ul>
        </li>
        <li data-jstree='{"opened":true}'>Consulta Oftalmológica
            <ul>
                @foreach ($formularios as $formulario)
                    @if($formulario->tipo === 'CONSULTA')
                        <li data-jstree='{"type":"file"}' x-on:click="forms('{{ $formulario->cd_formulario }}')"> {{ $formulario->nm_formulario }}  </li>  
                    @endif 
                @endforeach 
            </ul>
        </li>
       
       
    </ul>
</div>