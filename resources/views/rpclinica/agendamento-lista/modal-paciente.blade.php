<form role="form"  x-on:submit.prevent="atualizarPaciente" method="post" id="form-pac" enctype="multipart/form-data">
@csrf
<input type="hidden" name="tipoRest" value="json">
<input type="hidden" name="paciente" x-model="modalPaciente.cd_paciente" >
<input type="hidden" name="cd_agendamento" x-model="modalPaciente.cd_agendamento" >


<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label><strong>Nome: </strong><span class="red normal">*</span>
                <template x-if="modalPaciente.cd_paciente">
                <span class="label label-success" x-html="'&nbsp;&nbsp;Prontuário : &nbsp;'+modalPaciente.cd_paciente+'&nbsp;&nbsp;'"
                style="background: #30daca; border: #009688 1px solid;"> </span>
                </template>
            </label>
            <input type="text" class="form-control required" required value="{{ old('nome') }}"
                name="nome" maxlength="100" x-model="modalPaciente.nm_paciente" aria-required="true">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Data de nascimento: </strong><span class="red normal">*</span></label>
            <input type="date" class="form-control" value="{{ old('data_de_nascimento') }}"
                name="data_de_nascimento" x-model="modalPaciente.dt_nasc"  required maxlength="100" aria-required="true">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Sexo: </strong><span class="red normal"></span></label>
            <select name="sexo" class="form-control" id="pac-sexo" style="width: 100%;">
                <option value="">SELECIONE</option>
                <option value="H" >Masculino</option>
                <option value="M" >Feminino</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Estado civil: </strong><span class="red normal"></span></label>
            <select class="form-control" name="estado_civil" id="pac-estado_civil" style="width: 100%;"  >
                <option value="">SELECIONE</option>
                <option value="S" @if(old('estado_civil')=='S') selected @endif>Solteiro</option>
                <option value="C" @if(old('estado_civil')=='C') selected @endif>Casado</option>
                <option value="D" @if(old('estado_civil')=='D') selected @endif>Divorciado</option>
                <option value="V" @if(old('estado_civil')=='V') selected @endif>Viúvo</option>
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label><strong>RG: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control" x-model="modalPaciente.rg" value="{{ old('rg') }}" name="rg"
                maxlength="100" aria-required="true" >
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-2">
        <div class="form-group">
            <label><strong>CPF: </strong><span class="red normal"></span></label>
            <input x-mask="999.999.999-99" class="form-control" x-model="modalPaciente.cpf" value="{{ old('cpf') }}" name="cpf"
                maxlength="100" aria-required="true" type="text"  >
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Cartão SUS: </strong><span class="red normal"></span></label>
            <input type="text" x-model="modalPaciente.cartao_sus" class="form-control" value="{{ old('cartao_sus') }}" name="cartao_sus"  maxlength="100" aria-required="true" >
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label><strong>Nome Social: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control  " x-model="modalPaciente.nome_social" value="{{ old('nome_social') }}"
                name="nome_social" maxlength="100" aria-required="true">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label><strong>Nome do Responsável: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control  " x-model="modalPaciente.nm_responsavel" value="" name="nm_responsavel" maxlength="100" aria-required="true">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label><strong>CPF do Responsável: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control  " x-model="modalPaciente.cpf_responsavel" value="" name="cpf_responsavel" maxlength="100" aria-required="true">
        </div>
    </div>


</div>

<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label><strong>Nome da mâe: </strong><span class="red normal"> </span></label>
            <input type="text" x-model="modalPaciente.nm_mae" class="form-control" value="{{ old('nome_da_mae') }}" name="nome_da_mae"
                maxlength="100" aria-required="true"   >
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label><strong>Nome do pai: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control" value="{{ old('nome_do_pai') }}"
                name="nome_do_pai" maxlength="100" x-model="modalPaciente.nm_pai"  aria-required="true">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label class="mat-label"><strong>Convênio</strong> <span
                    class="red normal">*</span></label>
            <select class="form-control"   name="convenio" id="pac-convenio"  style="width: 100%;">
                <option value="">SELECIONE</option>
                @foreach($convenios as $convenio)
                    <option value="{{ $convenio->cd_convenio }}" @if(old('convenio')==$convenio->cd_convenio) selected @endif >{{ $convenio->nm_convenio }}</option>
                @endforeach
            </select>
        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label><strong>Cartão: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control" value="{{ old('cartao') }}"
                name="cartao" maxlength="100" x-model="modalPaciente.cartao" aria-required="true">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Data de Validade: </strong><span class="red normal"></span></label>
            <input type="date" class="form-control" value="{{ old('validade') }}"
                name="validade" maxlength="100" x-model="modalPaciente.dt_validade"  aria-required="true">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Telefone: </strong><!--<span class="red normal">*</span>--></label>
            <input type="text" class="form-control" value="{{ old('telefone') }}"
                name="telefone" maxlength="100" x-model="modalPaciente.fone" aria-required="true">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Celular: </strong><!--<span class="red normal">*</span>--></label>
            <input  type="text" class="form-control" value="{{ old('celular') }}"
                name="celular" maxlength="100" x-model="modalPaciente.celular" aria-required="true">
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label><strong>Profissão: </strong></label>
            <input  type="text" class="form-control" value="{{ old('profissao') }}"
                name="profissao" maxlength="100" x-model="modalPaciente.profissao" aria-required="true">
        </div>
    </div>

</div>
<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label><strong>Email: </strong><span class="red normal"></span></label>
            <input type="email" class="form-control" value="{{ old('logradouro') }}"
                name="logradouro" x-model="modalPaciente.email"  maxlength="100" aria-required="true">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Cep: </strong><span class="red normal"></span></label>

            <div class="input-group m-b-sm">
                 <input type="text" x-mask="99999-999" class="form-control" value="{{ old('cep') }}"
                 name="cep" maxlength="100"  x-model="modalPaciente.cep" aria-required="true">
                 <span class="input-group-addon" style="cursor: pointer;" x-on:click="buscarCep">
                    <i class="fa fa-thumb-tack" style="margin-right: 0px;"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label><strong>Rua: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control" value="{{ old('logradouro') }}"
                name="logradouro" maxlength="100" x-model="modalPaciente.logradouro"  aria-required="true">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Número: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control" value="{{ old('numero') }}"
                name="numero" maxlength="100" x-model="modalPaciente.numero"  aria-required="true">
        </div>
    </div>

</div>
<div class="row">

    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Complemento: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control" value="{{ old('complemento') }}"
                name="complemento" maxlength="100" x-model="modalPaciente.complemento"  aria-required="true">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label><strong>Bairro: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control" value="{{ old('bairro') }}"
                name="bairro" maxlength="100" x-model="modalPaciente.nm_bairro"  aria-required="true">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label><strong>Cidade: </strong><span class="red normal"></span></label>
            <input type="text" class="form-control" value="{{ old('cidade') }}"
                name="cidade" maxlength="100" x-model="modalPaciente.cidade" aria-required="true">
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label><strong>Estado: </strong><span class="red normal"></span></label>
            <select name="uf"   class="form-control" id="pac-uf" style="width: 100%;">
                <option value="">SELECIONE</option>
                @foreach (ESTADOS as $estado)
                    <option value="{{ $estado["sigla"] }}" @if(old('uf')==$estado["sigla"]) selected @endif>{{ $estado["nome"] }}</option>
                @endforeach
            </select>
        </div>
    </div>

</div>

<div class="box-footer">
    <div class="row">
        <div class="col-md-6">
            <div class="m-b-sm form-group">

                <label class="m-r-sm">
                    <div class="checker">
                        <span id="check-pac-vip">
                            <input type="checkbox" name="vip"  id="pac-vip"  value="S" />
                        </span>
                    </div> Paciente VIP
                </label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group"
                style="text-align: right; margin-top: 10px;">
                <button type="submit" class="btn btn-success"><i
                        class="fa fa-check"></i> Salvar Paciente</button>
            </div>
        </div>
    </div>
</div>

</form>
