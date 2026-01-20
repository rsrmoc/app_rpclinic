@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">
        <h3>Relação de Profissionais</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('profissional.listar') }}">Relação de Profissionais</a></li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper">
        <div class="panel panel-default">

          <form role="form"
          action="{{ route('profissional.store') }}"
          method="post"
          role="form"
          id="form-prof">
          @csrf
            <div class="panel-body">
                @error('error')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div role="tabpanel">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                   

                    
                    </ul>
                    <!-- Tab panes -->
 
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="tabCadastro">
    

                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Cadastro</h3>
                                        </div>

                                        <div class="panel-body">

                                            <div class="row" style="margin-bottom: 10px; margin-top: 15px;">
                                                <div class="col-md-4 col-sm-8 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Nome Completo <span class="red normal">*</span></label>
                                                        <input type="text" class="form-control required" id="nome"
                                                            value="{{ old('nome') }}" name="nome" maxlength="100" aria-required="true"
                                                            placeholder="Nome"  >
                                                        
                                                            
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Nome de Guerra <span class="red normal">*</span></label>
                                                        <input type="text" class="form-control  "  
                                                            value="{{ old('nm_guerra') }}" name="nm_guerra" maxlength="100" aria-required="true"
                                                            placeholder="Nome guerra"  >
                                                    
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label>Nascimento <span class="red normal">*</span></label>
                                                        <input type="date" class="form-control"  value="{{ old('nascimento') }}"
                                                            name="nascimento" maxlength="100" aria-required="true" >
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="mat-label">Sexo <span class="red normal">*</span></label>
                                                        <select name="sexo"  class="js-states form-control select2-hidden-accessible"  
                                                            style="display: none; width: 100%" aria-hidden="true" required  >
                                                            <option value="">Selecione</option>
                                                            <option value="M" @if(old('sexo') == 'M' ) selected @endif >Masculino</option>
                                                            <option value="F" @if(old('sexo') == 'F' ) selected @endif>Feminino</option> 
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <div class="form-group">
                                                        <label class="mat-label">CPF </label>
                                                        <input type="text" class="form-control" id="doc" value="{{ old('cpf') }}"
                                                            name="cpf" maxlength="100"  aria-required="true" placeholder="CPF">
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label class="mat-label">Identidade </label>
                                                        <input type="text" class="form-control" id="doc" value="{{ old('rg') }}"
                                                            name="rg" maxlength="100"  aria-required="true" placeholder="RG">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="mat-label">Conselho <span class="red normal">*</span></label>
                                                        <select name="conselho"
                                                            class="js-states form-control select2-hidden-accessible" tabindex="-1"
                                                            style="display: none; width: 100%" aria-hidden="true"  >
                                                            <option value="">Selecione</option>
                                                            @foreach ($conselhos as $conselho)
                                                                <option value="{{ $conselho->conselho }}">
                                                                    {{ $conselho->nm_conselho }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label>Nr. Conselho <span class="red normal">*</span></label>
                                                        <input type="text" class="form-control" id="nr_conselho" value="{{ old('nr_conselho') }}"
                                                            name="nr_conselho" maxlength="100" aria-required="true" placeholder="Nr. Conselho">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="mat-label">Tipo de Profissional <span class="red normal">*</span></label>
                                                        <select  name="tp_profissional"
                                                            class="js-states form-control select2-hidden-accessible" tabindex="-1"
                                                            style="display: none; width: 100%" aria-hidden="true"  >
                                                            <option value="">Selecione</option>
                                                            @foreach ($tipos as $tipo)
                                                                <option value="{{ $tipo->cd_tipo }}">
                                                                    {{ $tipo->nm_tipo }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Contato </label>
                                                        <input type="text" class="form-control" id="contato" value="{{ old('contato') }}"
                                                            name="contato" maxlength="100" aria-required="true" placeholder="Contato">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>WhatsApp </label>
                                                        <input class="form-control" id="whatsapp" value="{{ old('whatsapp') }}"
                                                            name="whatsapp" maxlength="100" aria-required="true"
                                                            placeholder="WhatsApp"
                                                            x-mask="(99) 99999-9999">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Email </label>
                                                        <input type="email" class="form-control" id="email" value="{{ old('email') }}"
                                                            name="email" maxlength="100" aria-required="true" placeholder="Email">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-3 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Endereço </label>
                                                            <input type="text" class="form-control" id="end" value="{{ old('endereco') }}"
                                                                name="endereco" maxlength="100" aria-required="true"
                                                                placeholder="Endereço">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <label>Número </label>
                                                        <input type="number" class="form-control" id="numero" value="{{ old('numero') }}"
                                                            name="numero" maxlength="100" aria-required="true"
                                                            placeholder="Número">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Complemento </label>
                                                            <input type="text" class="form-control" id="complemento"
                                                                value="{{ old('complemento') }}" name="complemento" maxlength="100"
                                                                aria-required="true" placeholder="Complemento">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2 col-sm-6 col-xs-6">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Bairro </label>
                                                            <input type="text" class="form-control" id="bairro" value="{{ old('bairro') }}"
                                                                name="bairro" maxlength="100" aria-required="true"
                                                                placeholder="Bairro">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Cidade </label>
                                                            <input type="text" class="form-control" id="cidade" value="{{ old('cidade') }}"
                                                                name="cidade" maxlength="100" aria-required="true"
                                                                placeholder="Cidade">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group">
                                                        <div class="mat-div">
                                                            <label>Observação Escala Médica </label>
                                                            <textarea class="form-control" style="height: 100px;" value="{{ old('cidade') }}"
                                                                name="obs_escala"  > </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="line">
                                                        <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                                            <label style="padding: 0">
                                                                <div class="checker">
                                                                    <span>
                                                                        <input type="checkbox" name="escala" value="S"  />
                                                                    </span>
                                                                </div> Escala Médica 
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>



                                

                                    <div id="inputs-especialidades"></div>

                                    <div id="inputs-procedimentos"></div>

                                    <input type="submit"
                                        id="submit-form-prof"
                                        style="display: none" />
                            
                            </div>
    
                        </div>
                        
                </div>
            </div>

            <div class="panel-footer">
                <button id="btn-submit-form-prof" type="submit"
                    class="btn btn-success btn-lg"
                    style="margin-right: 10px">
                    Salvar
                </button>

                <button class="btn btn-default btn-lg">Limpar</button>
            </div>

        </form>

        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/rpclinica/profissional.js') }}"></script>
@endsection
