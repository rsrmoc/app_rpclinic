<!DOCTYPE html>
<html>

<!-- Mirrored from phantom-themes.com/modern/Source/admin1/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 15 Oct 2021 13:55:05 GMT -->

<head>

    <!-- Title -->
    <title>RPclinic | Login </title>

    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta charset="UTF-8">
    <meta name="description" content="RPclinic" />
    <meta name="keywords" content="RPclinic" />
    <meta name="author" content="RPsys" />

    <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon" />
    <!-- Styles -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>
    <link href="{{ asset('assets/plugins/pace-master/themes/blue/pace-theme-flash.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/uniform/css/uniform.default.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/fontawesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/line-icons/simple-line-icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/plugins/waves/waves.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/3d-bold-navigation/css/style.css') }}" rel="stylesheet" type="text/css" />

    <!-- Theme Styles -->
    <link href="{{ asset('assets/css/modern.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/themes/green.css') }}" class="theme-color" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('assets/plugins/3d-bold-navigation/js/modernizr.js') }}"></script>
    <script src="{{ asset('assets/plugins/offcanvasmenueffects/js/snap.svg-min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/login.css') }}">
</head>

<body class="page-login">

    <script type="text/javascript">
        @if ($mensagem = session('mensagem'))
            @if ($mensagem['icon'] == 'success')
                toastr.success("{{ $mensagem['text'] ?? '' }}", "{{ $mensagem['title'] ?? '' }}");
            @endif

            @if ($mensagem['icon'] == 'error')
                toastr.error("{{ $mensagem['text'] ?? '' }}", "{{ $mensagem['title'] ?? $mensagem }}");
            @endif

            @if ($mensagem['icon'] == 'warning')
                toastr.warning("{{ $mensagem['text'] ?? '' }}", "{{ $mensagem['title'] ?? $mensagem }}");
            @endif

            @if ($mensagem['icon'] == 'info')
                toastr.info("{{ $mensagem['text'] ?? '' }}", "{{ $mensagem['title'] ?? $mensagem }}");
            @endif
        @endif
    </script>


    <style>
        .login-page {
            width: 360px;
            padding: 8% 0 0;
            margin: auto;
        }

        .form {
            position: relative;
            z-index: 1;
            background: #FFFFFF;
            max-width: 360px;
            margin: 0 auto 100px;
            padding: 45px;
            padding-top: 20px;
            padding-bottom: 20px;
            text-align: center;
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
        }
 
        .form .register-form {
            display: none;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 300px;
            margin: 0 auto;
        }

        .container:before,
        .container:after {
            content: "";
            display: block;
            clear: both;
        }

        .container .info {
            margin: 50px auto;
            text-align: center;
        }

        .container .info h1 {
            margin: 0 0 15px;
            padding: 0;
            font-size: 36px;
            font-weight: 300;
            color: #1a1a1a;
        }

        .container .info span {
            color: #4d4d4d;
            font-size: 12px;
        }

        .container .info span a {
            color: #000000;
            text-decoration: none;
        }

        .container .info span .fa {
            color: #EF3B3A;
        }

        body {
            flex: 1;
            background-color: #ecf2f1;
            background-image: url({{ asset("assets/images/medico-pc.jpg") }});
            background-blend-mode: multiply;
            background-repeat: no-repeat;
            background-size: cover;
        }

    </style>

    <div class="login-page">
        <div class="form">
            <form class="m-t-md" action="{{ route('rpclinica.valida') }}" style="margin: 0px;"
            enctype="multipart/form-data" method="post">
            @csrf
                    <img width="90%" style="margin-left: 5%; margin-right: 5%;" src="{{ asset('assets/images/logo_horizontal.svg') }}" style="margin-bottom:20px;">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h4 class="text-left">Houve alguns erros:</h4>

                            <ul>
                                {!! implode('', $errors->all('<li class="text-left">:message</li>')) !!}
                            </ul>
                        </div>
                    @endif

                    <div class="form-group"> 
                        <input type="text" class="form-control" name="email"  placeholder='Usuario' required >
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="Senha" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block btn-lg" style="font-size: 15px; "> 
                        <span class="glyphicon glyphicon-log-out" style="margin-right: 5px" aria-hidden="true"></span> Acessar Sistema  </button>
                    <!--
                    <p class="message"><a href="{{ route('rpclinica.esqueci') }}"
                            class="display-block text-center m-t-md text-sm">Esqueci a senha</a></p>
                    -->
                    <br><br>        
            </form>

        </div>

    </div>

        


        <!-- Javascripts -->
        <script src="{{ asset('assets/plugins/jquery/jquery-2.1.4.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/pace-master/pace.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-blockui/jquery.blockui.js') }}"></script>
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/switchery/switchery.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/uniform/jquery.uniform.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/offcanvasmenueffects/js/classie.js') }}"></script>
        <script src="{{ asset('assets/plugins/waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/js/modern.min.js') }}"></script>

</body>

</html>
