<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <title>Issue Token | JL Token</title>
    <link rel="icon" href="{{ asset('painel_chamada/favicon.ico') }}">
    <link href="{{ asset('painel_chamada/css/materialize.min.css') }}" type="text/css" rel="stylesheet"
        media="screen,projection">
    <link href="{{ asset('painel_chamada/js/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" type="text/css"
        rel="stylesheet" media="screen,projection">
    <style>
        .btn-queue {
            padding: 25px;
            font-size: 47px;
            line-height: 36px;
            height: auto;
            margin: 10px;
            letter-spacing: 0;
            text-transform: none;
            border-radius: 0.3em;
        }
    </style>
    <link href="{{ asset('painel_chamada/css/style.min.css') }}" type="text/css" rel="stylesheet"
        media="screen,projection">
</head>

<body>
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <header id="header" class="page-topbar">
        <div class="navbar-fixed">
            <nav class="navbar-color">
                <div class="nav-wrapper">
                    <ul class="left">
                        <li>
                            <h1 class="logo-wrapper">
                                <a href="http://painel.chamada/dashboard" class="brand-logo darken-1">
                                    <img src="{{ asset('painel_chamada/img/logo_rp.svg') }}" alt="materialize logo">
                                </a>
                                <span class="logo-text">JL Token</span></h1>
                        </li>
                    </ul>
                    <ul class="right hide-on-med-and-down">
                        <li><a href="javascript:void(0);"
                                class="waves-effect waves-block waves-light toggle-fullscreen"><i
                                    class="mdi-action-settings-overscan"></i></a></li>
                    </ul>
                    <ul class="right">
                        <span class="truncate" style="margin-right:20px;font-size:20px">Unimed Norte de Minas</span>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <div id="main" style="padding:15px;padding-bottom:0">
        <div class="wrapper">
            <section id="content">
                <div class="row">
                    <div class="col s12">
                        <div class="card" style="background:#f9f9f9;box-shadow:none">
                            <span class="card-title" style="line-height:0;font-size:22px">Clique nas opções abaixo para
                                emitir token</span>
                            <div class="divider" style="margin:10px 0 10px 0"></div>
                            <span class="btn btn-large btn-queue waves-effect waves-light"
                                onclick="queue_dept(1)">Normal</span>
                            <span class="btn btn-large btn-queue waves-effect waves-light"
                                onclick="queue_dept(2)">Prioridade</span>
                        </div>
                    </div>
                </div>

                 <!-- alteração -->
                <input type="text" id="senhaInput" placeholder="Digite a senha">
                <button id="chamarSenhaBtn">Chamar Senha</button>
                 <!-- alteração -->
                  
            </section>
        </div>
    </div>

    <footer class="page-footer" style="padding:0;margin-top:0">
        <div class="footer-copyright">
            <div class="container">
                <span> <a class="grey-text text-lighten-3" href="http://www.justlabtech.com"
                        target="_blank">RPclinic</a> </span>
                <span class="right"> <span class="grey-text text-lighten-3">Versão</span> 1.0</span>
            </div>
        </div>
    </footer>

    <script type="text/javascript" src="{{ asset('painel_chamada/js/plugins/jquery-1.11.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('painel_chamada/js/materialize.min.js') }}"></script>
    <script type="text/javascript"
        src="{{ asset('painel_chamada/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('painel_chamada/js/plugins.min.js') }}"></script>

    <!-- alteração -->
    <script type="text/javascript" src="{{ asset('painel_chamada/js/script.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('#main').css({
                'min-height': $(window).height() - 134 + 'px'
            });
        });
        $(window).resize(function() {
            $('#main').css({
                'min-height': $(window).height() - 134 + 'px'
            });
        });
    </script>
</body>

</html>