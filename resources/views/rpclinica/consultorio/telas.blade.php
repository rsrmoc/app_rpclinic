
<div x-show="telaAtiva==null">
    <div class="col-md-9 col-sm-9 col-lg-7 col-xs-12  " style="text-align: center;">
        <img class="img-fluid" style="  margin-top: 20px;" src="{{ asset('assets\images\oftalmo.png') }}">
    </div>
</div>

<div x-show="telaAtiva=='AUTO_REFRACAO'">
    @include('rpclinica.consultorio.formularios.oftalmologia.auto-refracao.formulario', ['agendamento' => $agendamento]) 
</div>
 
<div  x-show="telaAtiva=='CERATOMETRIA'">
    @include('rpclinica.consultorio.formularios.oftalmologia.ceratometria.formulario', ['agendamento' => $agendamento]) 
</div>

<div  x-show="telaAtiva=='CERATOSCOPIA_COMP'">
    @include('rpclinica.consultorio.formularios.oftalmologia.ceratometria_comp.formulario', ['agendamento' => $agendamento]) 
</div>

<div  x-show="telaAtiva=='ANAMNESE'">
    @include('rpclinica.consultorio.formularios.oftalmologia.anamnese.formulario', ['agendamento' => $agendamento]) 
</div>

<div  x-show="telaAtiva=='FUNDOSCOPIA'">
    @include('rpclinica.consultorio.formularios.oftalmologia.fundoscopia.formulario', ['agendamento' => $agendamento]) 
</div>

<div  x-show="telaAtiva=='TONOMETRIA_APLANACAO'">
    @include('rpclinica.consultorio.formularios.oftalmologia.tonometria_aplanacao.formulario', ['agendamento' => $agendamento,'tabelas' => $tabelas]) 
</div>

<div  x-show="telaAtiva=='REFRACAO'">
    @include('rpclinica.consultorio.formularios.oftalmologia.refracao.formulario', ['agendamento' => $agendamento,'tabelas' => $tabelas]) 
</div>

<div  x-show="telaAtiva=='RECEITA_OCULOS'">
    @include('rpclinica.consultorio.formularios.oftalmologia.receita_oculos.formulario', ['agendamento' => $agendamento,'tabelas' => $tabelas]) 
</div>

<div  x-show="telaAtiva=='RECEITAS'">
    @include('rpclinica.consultorio.formularios.oftalmologia.receita.formulario', ['agendamento' => $agendamento,'tabelas' => $tabelas]) 
</div>

<div  x-show="telaAtiva=='ATESTADOS'">
    @include('rpclinica.consultorio.formularios.oftalmologia.atestado.formulario', ['agendamento' => $agendamento,'tabelas' => $tabelas]) 
</div>

<div  x-show="telaAtiva=='RESERVA_CIRURGIA'">
    @include('rpclinica.consultorio.formularios.oftalmologia.reserva_cirurgia.formulario', ['agendamento' => $agendamento,'tabelas' => $tabelas]) 
</div>

<div  x-show="telaAtiva=='EXAME'">
    @include('rpclinica.consultorio.formularios.oftalmologia.exames.dados-exame', ['agendamento' => $agendamento,'tabelas' => $tabelas]) 
</div>


<div class="modal fade modalHistFormularios" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myLargeModalLabel" style="font-size: 30px;font-weight: 300;font-style: italic;" > {{ $agendamento->paciente->nm_paciente }}</h4>
            </div>
            <div class="modal-body" x-html="modalConteudo">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button> 
            </div>
        </div>
    </div>
</div>