@extends('rpclinica.layout.layout')

@section('content')
    <div class="page-title">
        <h3>Edição de Perfil</h3>
        <div class="page-breadcrumb">
            <ol class="breadcrumb">
                <li><a href="{{ route('perfil.listar') }}">Relação de Perfis de Acesso</a></li>
            </ol>
        </div>
    </div>
    <div id="main-wrapper">
        <div class="col-md-12 ">
            <div class="panel panel-white">
                <div class="panel-body">
                    @error('error')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form role="form" action="{{ route('perfil.update', ['perfil' => $perfil->cd_perfil]) }}" method="post" role="form">
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fname">Nome: <span class="red normal">*</span></label>
                                    <input type="text" class="form-control" required id="NM_SETOR"
                                        value="{{ $perfil->nm_perfil }}"
                                        name="nome" maxlength="100" aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-2"> 
                                <div class="form-group">
                                    <label for="fname" class="mat-label">
                                        Tipo de Agendamento<span class="red normal"></span>
                                    </label>
                                    <select class="form-control"  style="width: 100%" name="tp_agenda"> 
                                        <option value="lista" @if($perfil->tp_agenda == 'lista') selected @endif>Lista</option>
                                        <option value="calendario" @if($perfil->tp_agenda == 'calendario') selected @endif>Calendário</option> 
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2"> 
                                <div class="form-group">
                                    <label for="fname" class="mat-label">
                                        Dashboard Inicial<span class="red normal"></span>
                                    </label>
                                    <select class="form-control" style="width: 100%" name="dashboard_inicial"> 
                                        <option value="consultorio" @if($perfil->dashboard_inicial == 'consultorio') selected @endif>Consultorio</option>
                                        <option value="exame" @if($perfil->dashboard_inicial == 'exame') selected @endif>Central de laudos</option> 
                                        <option value="logo" @if($perfil->dashboard_inicial == 'logo') selected @endif>Logo</option> 
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3"> 
                                <div class="form-group">
                                    <label for="fname" class="mat-label">
                                        Permite editar horario no Agendamento<span class="red normal"></span>
                                    </label>
                                    <select class="form-control"  style="width: 100%" name="ag_editar_horario"> 
                                        <option value="sim" @if($perfil->ag_editar_horario == 'sim') selected @endif>SIM - Permite edição de Horário</option>
                                        <option value="nao" @if($perfil->ag_editar_horario == 'nao') selected @endif>NÃO - Não Permite edição de Horário</option> 
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h4 style="margin-bottom: 6px; padding: 0 12px">Paginas:</h4>
                                <p class="text-muted" style="margin-bottom: 12px; padding: 0 12px">Marque no minimo 1 opção.</p>
                            </div>

                            <br><br>
                            <div class="col-12">
                                <h4 style="margin-bottom: 6px; margin-top: 15px; ">{{ $P_acesso }}</h4> 
                            </div>

                            @foreach ($acessos as $indice => $rota)
                                @if($P_acesso<>$rota->menu) 
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <h4 style="margin-bottom: 6px; margin-top: 15px; ">{{ $rota->menu }}</h4> 
                                        </div>
                                    </div>
                                @endif
                             
                                @php $P_acesso=$rota->menu; $S_acesso=$rota->sub_menu; @endphp

                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <div class="form-group" style="margin-bottom: 3px;">
                                        <label>
                                            <input type="checkbox" name="paginas[]" value="{{ $rota->grupo }}" 
                                            @if ( in_array($rota->grupo, $itensPerfil) ) checked @endif  class="flat-red">
                                            <span style="text-transform: capitalize">{{ $rota->nm_rota }}</span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
 
                            
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h4 style="margin-bottom: 6px; margin-top: 15px; ">Relatorios Personalizados</h4> 
                                </div> 
                            </div>
                            @foreach ($relatorios as $indice => $rota)

                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <div class="form-group" style="margin-bottom: 3px;">
                                    <label>
                                        <input type="checkbox" name="relatorios[]" 
                                        @if ( in_array($rota->id, $relatoriosPerfil) ) checked @endif
                                        value="{{ $rota->id }}" class="flat-red">
                                        <span style="text-transform: capitalize">{{ $rota->titulo }}</span>
                                    </label>
                                </div>
                            </div>

                        @endforeach
                            <br><br><br><br>
                        </div>

                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Salvar" />
                            <input type="reset" class="btn btn-default" value="Limpar" onclick="Limpar()" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div><!-- Main Wrapper -->
@endsection
