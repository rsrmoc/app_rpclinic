<div class="navbar">

    <script type="text/javascript">

        @if($mensagem = session('mensagem'))
            @if ($mensagem['icon'] == 'success')
                toastr.success("{{ $mensagem['text'] ?? '' }}","{{ $mensagem['title'] ?? '' }}");
            @endif

            @if ($mensagem['icon'] == 'error')
                toastr.error("{{ $mensagem['text'] ?? '' }}","{{ $mensagem['title'] ?? $mensagem }}");
            @endif

            @if ($mensagem['icon'] == 'warning')
                toastr.warning("{{ $mensagem['text'] ?? '' }}","{{ $mensagem['title'] ?? $mensagem }}");
            @endif

            @if ($mensagem['icon'] == 'info')
                toastr.info("{{ $mensagem['text'] ?? '' }}","{{ $mensagem['title'] ?? $mensagem }}");
            @endif
        @endif

        function RPclinicMenu(){
            axios.get('/rpclinica/menu');
        }

    </script>

    <div class="navbar-inner">
        <div class="sidebar-pusher">
            <a href="javascript:void(0);" class="waves-effect waves-button waves-classic push-sidebar">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <div class="logo-box">
            <a href="#" class="logo-text"><img src="{{ asset('assets\images\logo_menu.svg') }}" style="width: 155px;  "></a>
        </div><!-- Logo Box -->



        <div class="topmenu-outer">
            <div class="top-menu">
                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <a href="javascript:void(0);" onclick="RPclinicMenu();" class="waves-effect waves-button waves-classic sidebar-toggle"><i class="fa fa-bars"></i></a>
                    </li>

                    @yield('filterHeader') 
                    
                    <li class="resumo-paciente">
                        <a href="javascript:void(0);" style= " padding: 5px; padding-left: 0px;">
                            @yield('resumoPaciente') 
                        </a>
                    </li>
 

                </ul>

                <ul class="nav navbar-nav navbar-right">
 
                    <li class="dropdown">
                        <a href="{{ route('tutorial') }}" class="dropdown-toggle waves-effect waves-button waves-classic"  >
                            <span class="user-name"><span class="glyphicon glyphicon-facetime-video" aria-hidden="true" style="margin-right: 4px;"></span> Tutoriais</span>
                        </a>
                    </li>

                    <li class="dropdown">
                        <a href="{{ route('noticias') }}" class="dropdown-toggle waves-effect waves-button waves-classic"  >
                            <span class="user-name"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true" style="margin-right: 4px;"></span> Notícias</span>
                        </a>
                    </li>
             
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle waves-effect waves-button waves-classic" data-toggle="dropdown">
                            @php
                                $User= explode(' ',request()->user()->nm_usuario);
                            @endphp
                            <span class="user-name">{{ ucfirst(strtolower($User[0])) }} <i class="fa fa-angle-down"></i></span>

                            <img class="img-circle avatar" src="{{ asset('assets/images/user.png') }}" width="40" height="40" alt="">


                        </a>
                        <ul class="dropdown-menu dropdown-list" role="menu">

                            @if (in_array('usuario.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                <li role="presentation"><a href="{{ route('usuario.listar') }}"><i class="fa fa-user"></i>Criação de Usuários</a></li>
                            @endif

                            @if (in_array('usuario.listar', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                            <li role="presentation"><a href="{{ route('perfil.listar') }}"><i class="fa fa-book"></i>Perfil de Acesso</a></li>
                            @endif
                            
                            @if (in_array('relatorios.list', Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                <li role="presentation"><a href="{{ route('relatorios.list') }}"><i class="fa fa-th-list"></i>Report Designer</a></li>
                            @endif
                            @if (in_array(['usuario.listar','relatorios.list','usuario.listar'], Session::get('perfil')) || auth()->guard('rpclinica')->user()->admin == 'S')
                                <li role="presentation" class="divider"></li>
                            @endif
                            <li role="presentation"><a href="{{ route('rpclinica.usuario.alterar') }}"><i class="fa fa-unlock-alt"></i>Alteração de Senha</a></li>
                               
                            <li role="presentation"><a href="{{ route('rpclinica.logout') }}"><i class="fa fa-sign-out m-r-xs"></i>Sair</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('rpclinica.logout') }}" class="log-out waves-effect waves-button waves-classic">
                            <span><i class="fa fa-sign-out m-r-xs"></i>Sair</span>
                        </a>
                    </li>

                </ul><!-- Nav -->
            </div><!-- Top Menu -->
        </div>
    </div>
</div><!-- Navbar -->
<style>
    /* Navbar Vertical Alignment */
    .topmenu-outer {
        height: 60px !important;
        display: flex !important;
        align-items: center !important;
    }

    .top-menu {
        width: 100% !important;
    }

    .nav.navbar-nav {
        display: flex !important;
        align-items: center !important;
        height: 60px !important;
    }

    /* Logo Box Fix */
    .logo-box {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    /* Remove legacy active borders */
    ul>.active {
        border-top: none !important;
    }

    .navbar .nav > li > a {
        padding: 10px 15px !important;
        display: flex !important;
        align-items: center !important;
    }

    .navbar .nav > li > a i {
        margin-right: 8px !important;
        font-size: 16px !important;
    }

    /* Avatar size fix */
    .img-circle.avatar {
        width: 32px !important;
        height: 32px !important;
        margin-left: 10px !important;
    }

    /* User name contrast */
    .user-name {
        color: #e2e8f0 !important;
        font-weight: 600 !important;
    }

    /* Search form fix if used */
    .search-form {
        background: rgba(15, 23, 42, 0.8) !important;
        backdrop-filter: blur(20px) !important;
    }
</style>
