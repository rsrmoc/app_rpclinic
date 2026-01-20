@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Edição de Itens de Atendimento</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('exame.listar') }}">Relação de Itens de Atendimento</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    <form role="form" id="addUser" action="{{ route('exame.update', ['exame' => $exame]) }}" method="post" role="form">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fname">Descrição: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control required"
                                        value="{{ $exame->nm_exame }}"
                                        name="descricao"
                                        maxlength="100" aria-required="true" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Tipo <span class="red normal">*</span></label>
                                    <select class="form-control m-b-sm" style="width: 100%"  name="tp_item" required>
                                        <option value="">Selecione</option>
                                        <option value="CO" @if(old('tp_item',$exame->tp_item)=='CO') selected  @endif>Consulta</option>
                                        <option value="CI" @if(old('tp_item',$exame->tp_item)=='CI') selected  @endif>Cirurgia</option>
                                        <option value="EX" @if(old('tp_item',$exame->tp_item)=='EX') selected  @endif>Exame</option>
                                        <option value="PR" @if(old('tp_item',$exame->tp_item)=='PR') selected  @endif>Pre-Exame</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Local de Atendimento <span class="red normal">*</span></label>
                                    <select class="form-control m-b-sm" style="width: 100%"  name="cd_local" required>
                                        <option value="">Selecione</option>
                                        @foreach($Local as $key => $value)
                                            <option value="{{ $value->cd_local }}" @if(old('cd_local',$exame->cd_local)==$value->cd_local) selected  @endif>{{ $value->nm_local }}</option>
                                        @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fname" class="mat-label">Procedimento <span class="red normal"></span></label>
                                    <select class="form-control m-b-sm" style="width: 100%"
                                    id="exame-procedimento" name="cd_proc" required> 
                                        <option value="{{ $exame->cod_proc }}">{{ $exame->procedimento?->cod_proc . ' | ' . $exame->procedimento?->nm_proc }}</option> 
                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="line">
                            <div class="checkbox m-r-md" style="margin-top: 0; margin-bottom: 0">
                                <label style="padding: 0">
                                    <div class="checker">
                                        <span>
                                            <input type="checkbox" name="sn_ativo" value="S" @if($exame->sn_ativo == 'S') checked @endif/>
                                        </span>
                                    </div> Ativo
                                </label>
                            </div>
                        </div>
                        <br><br>
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Salvar" />
                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                        </div>
                    </form>
                </div>
            </div>
            
        </div>


        <script src=" {{ asset('assets/js/jquery.min.js') }} "></script>
        <script> 
            $(document).ready(function () { 
                $('#exame-procedimento').select2({
                    ajax: {
                        url: '/rpclinica/json/search-procedimento',
                        dataType: 'json',
                        processResults: (data) => {
                            let search = $('#exame-procedimento').data('select2').results.lastParams?.term; 
                            return {
                                results: data
                            };
                        }
                    }
                }); 
            });
            
            </script> 
    </div><!-- Main Wrapper -->
@endsection

@section('script')
@endsection
