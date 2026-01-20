@extends('rpclinica.layout.layout')


@section('content')
    <div class="page-title">
        <h3>Edição de Profissionais</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('profissional.listar') }}">Relação de Profissionais</a></li>
            </ol>
        </div>
    </div>

    <div id="main-wrapper" x-data="app">
        <div class="panel panel-default">
            <div class="panel-body">
                @error('error')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <form role="form"
                action="{{ route('profissional.update', ['profissional' => $profissional->cd_profissional]) }}"
                method="post"
                role="form"
                enctype="multipart/form-data"
                id="form-prof">
                @csrf
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
                                                    value="{{ old('nome',$profissional->nm_profissional) }}" name="nome" maxlength="100" aria-required="true"
                                                    placeholder="Nome"  > 
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Nome de Guerra <span class="red normal">*</span></label>
                                                <input type="text" class="form-control  "  
                                                    value="{{ old('nm_guerra',$profissional->nm_guerra) }}" name="nm_guerra" maxlength="100" aria-required="true"
                                                    placeholder="Nome guerra"  >
                                               
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Nascimento <span class="red normal">*</span></label>
                                                <input type="date" class="form-control"  value="{{ old('nascimento',$profissional->nasc) }}"
                                                    name="nascimento" maxlength="100" aria-required="true" >
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="mat-label">Sexo <span class="red normal">*</span></label>
                                                <select name="sexo"  class="js-states form-control select2-hidden-accessible"  
                                                    style="display: none; width: 100%" aria-hidden="true" required  >
                                                    <option value="">Selecione</option>
                                                    <option value="M" @if(old('sexo',$profissional->sexo) == 'M' ) selected @endif >Masculino</option>
                                                    <option value="F" @if(old('sexo',$profissional->sexo) == 'F' ) selected @endif>Feminino</option> 
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 col-xs-6">
                                            <div class="form-group">
                                                <label class="mat-label">CPF </label>
                                                <input type="text" class="form-control" id="doc" value="{{ old('cpf',$profissional->doc) }}"
                                                    name="cpf" maxlength="100"  aria-required="true" placeholder="CPF">
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label class="mat-label">Identidade </label>
                                                <input type="text" class="form-control" id="doc" value="{{ old('rg',$profissional->rg) }}"
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
                                                    @foreach ($conselhos as $item)
                                                        <option value="{{ $item->conselho }}" @if(old('conselho',$profissional->conselho) == $item->conselho) selected   @endif >
                                                            {{ $item->nm_conselho }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Nr. Conselho <span class="red normal">*</span></label>
                                                <input type="text" class="form-control" id="nr_conselho" value="{{ old('nr_conselho',$profissional->crm) }}"
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
                                                        <option value="{{ $tipo->cd_tipo }}" @if(old('tp_profissional',$profissional->tp_profissional)==$tipo->cd_tipo) selected   @endif>
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
                                                <input type="text" class="form-control" id="contato" value="{{ old('contato',$profissional->contato) }}"
                                                    name="contato" maxlength="100" aria-required="true" placeholder="Contato">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>WhatsApp </label>
                                                <input class="form-control" id="whatsapp" value="{{ old('whatsapp',$profissional->whatsapp) }}"
                                                    name="whatsapp" maxlength="100" aria-required="true"
                                                    placeholder="WhatsApp"
                                                    x-mask="(99) 99999-9999">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Email </label>
                                                <input type="email" class="form-control" id="email" value="{{ old('email',$profissional->email) }}"
                                                    name="email" maxlength="100" aria-required="true" placeholder="Email">
                                            </div>
                                        </div>
                                    </div>
        
                                    <div class="row">
                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Endereço </label>
                                                    <input type="text" class="form-control" id="end" value="{{ old('endereco',$profissional->endereco) }}"
                                                        name="endereco" maxlength="100" aria-required="true"
                                                        placeholder="Endereço">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <label>Número </label>
                                                <input type="number" class="form-control" id="numero" value="{{ old('numero',$profissional->numero) }}"
                                                    name="numero" maxlength="100" aria-required="true"
                                                    placeholder="Número">
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Complemento </label>
                                                    <input type="text" class="form-control" id="complemento"
                                                        value="{{ old('complemento',$profissional->complemento) }}" name="complemento" maxlength="100"
                                                        aria-required="true" placeholder="Complemento">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-6 col-xs-6">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Bairro </label>
                                                    <input type="text" class="form-control" id="bairro" value="{{ old('bairro',$profissional->bairro) }}"
                                                        name="bairro" maxlength="100" aria-required="true"
                                                        placeholder="Bairro">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <div class="mat-div">
                                                    <label>Cidade </label>
                                                    <input type="text" class="form-control" id="cidade" value="{{ old('cidade',$profissional->cidade) }}"
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
                                                        name="obs_escala"  >{{ old('obs_escala',$profissional->obs_escala_medica) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="line">
                                                <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                                    <label style="padding: 0">
                                                        <div class="checker">
                                                            <span>
                                                                <input type="checkbox" name="escala" @if($profissional->sn_escala_medica=='S') checked @endif value="S"  />
                                                            </span>
                                                        </div> Escala Médica 
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
 
                            @if(isset($profissional->usuario)) 

                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Impresso</h3>
                                    </div>
                                    <div class="panel-body">

                                        <div class="row" style="margin-bottom: 10px; margin-top: 15px;">

                                            <div class="col-md-4 col-sm-8 col-xs-12 ">
                                                <div class="form-group  ">
                                                    <label>Nome do Profissional: <span class="red normal"></span></label>
                                                    <input type="text" class="form-control " value="{{ old('nm_header_doc',$profissional->usuario->nm_header_doc  )}}" name="nm_header_doc" maxlength="200" aria-required="true">
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-8 col-xs-12 ">
                                                <div class="form-group  ">
                                                    <label>Especialidade(s) do Profissional: <span class="red normal"></span></label>
                                                    <input type="text" class="form-control " value="{{ old('espec_header_doc',$profissional->usuario->espec_header_doc  )}}" name="espec_header_doc" maxlength="200" aria-required="true">
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-8 col-xs-12 ">
                                                <div class="form-group  ">
                                                    <label>Conselho do Profissional: <span class="red normal"></span></label>
                                                    <input type="text" class="form-control " value="{{ old('conselho_header_doc',$profissional->usuario->conselho_header_doc )}}" name="conselho_header_doc" maxlength="200" aria-required="true">
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-8 col-xs-12  ">
                                                <div class="form-group">
                                                    <label>
                                                        <div class="checker"><span><input type="checkbox" name="sn_logo_header_doc" value="S" @if(old('sn_logo_header_doc',$profissional->usuario->sn_logo_header_doc)=='S')  checked   @endif class="flat-red"></span>
                                                        </div>Ocultar Logo

                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-8 col-xs-12  ">
                                                <div class="form-group">
                                                    <label>
                                                        <div class="checker"><span><input type="checkbox" name="sn_header_doc" value="S" @if(old('sn_header_doc',$profissional->usuario->sn_header_doc )=='S')  checked   @endif class="flat-red"></span>
                                                        </div>Ocultar Dados do Paciente

                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-8 col-xs-12  ">
                                                <div class="form-group">
                                                    <label>
                                                        <div class="checker"><span><input type="checkbox" name="sn_footer_header_doc" value="S" @if(old('sn_footer_header_doc',$profissional->usuario->sn_footer_header_doc)=='S')  checked   @endif class="flat-red"></span>
                                                        </div>Ocultar Rodapé do Documento

                                                    </label>
                                                </div>
                                            </div>

                                        </div>


                                    </div>
                                </div>


                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Assinatura</h3>
                                    </div>
                                    <div class="panel-body">

                                        <div class="row" style="margin-bottom: 10px; margin-top: 15px;">

                                            <div class="col-md-6 col-sm-12 col-xs-12 ">
                                                <div class="form-group  ">
                                                    <label>Assinatura Digitalizada: <span class="red normal"></span></label>
                                                    <input type="file" class="form-control " name="assinatura">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-bottom: 10px; margin-top: 15px;">

                                            <div class="row" style="margin-bottom: 10px; margin-top: 15px;">

                                                <div class="col-md-6 col-sm-12 col-xs-12 ">
                                                   @if ($profissional->assinatura )

                                                        <div class="modal-header">
                                                            <button type="button" class="close"  x-on:click="deleteAssinatura()"><span aria-hidden="true">×</span></button>
                                                            <h4 class="modal-title" id="myModalLabel">Assinatura Digitalizada</h4>
                                                        </div>
                                                        <img src = "data:{{$profissional->tp_assinatura}};base64,{{$profissional->assinatura}}"  style="max-width: 200px;" id="img_assinatura"   />
                                                    
                                                    @endif
                                                </div> 

                                            </div>

                                        </div>


                                    </div>
                                </div>

                            @endif
                            
                        </div>
 
                    </div> 
                </div>
            </div>

            <div class="panel-footer">
                <button id="btn-submit-form-prof"
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
    <script> 
        const cdProfissional = @js($profissional->cd_profissional);
    </script>
    <script src="{{ asset('js/rpclinica/profissional.js') }}"></script>
 
@endsection
