
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <title>Display | JL Token</title>
        <link rel="icon" href="{{ asset('painel_chamada/favicon.ico') }}">
        <link href="{{ asset('painel_chamada/css/materialize.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="{{ asset('painel_chamada/js/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="{{ asset('painel_chamada/css/style.min.css') }}" type="text/css" rel="stylesheet" media="screen,projection">
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
                            <li><h1 class="logo-wrapper"><a href="http://painel.chamada/dashboard" class="brand-logo darken-1">
                                <img src="{{ asset('painel_chamada/img/logo_rp.svg') }}" alt="materialize logo"></a><span class="logo-text">JL Token</span></h1></li>
                        </ul>
                        <ul class="right hide-on-med-and-down">
                            <li><a href="javascript:void(0);" class="waves-effect waves-block waves-light toggle-fullscreen"><i class="mdi-action-settings-overscan"></i></a></li>
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
                 
        <div id="callarea" class="row" style="line-height:1.23">
            <div class="col m4">
                <div class="card-panel center-align" style="margin-bottom:0">
                    <div style="border-bottom:1px solid #ddd">
                        <span id="num1" style="font-size:85px;font-weight:bold;line-height:1.45">UNI-22</span><br>
                        <small id="cou1" style="font-size:35px">GUICHE 02</small>
                    </div>
                    <div style="border-bottom:1px solid #ddd">
                        <span id="num2" style="font-size:85px; font-weight:bold;line-height:1.45">UNI-21</span><br>
                        <small id="cou2" style="font-size:35px">GUICHE 02</small>
                    </div>
                    <div style="border-bottom:1px solid #ddd">
                        <span id="num3" style="font-size:85px;font-weight:bold;line-height:1.45">UNI-20</span><br>
                        <small id="cou3" style="font-size:35px">GUICHE 01</small>
                    </div>
                </div>
            </div>
            <div class="col m8">
                <div class="card-panel center-align" style="margin-bottom:0">
                    <span style="font-size:45px">Senha de Chamada</span><br>
                    <span id="num0" style="font-size:185px;color:red;font-weight:bold;line-height:1.5">UNI-23</span><br>
                    <span style="font-size:40px">Encaminhar para </span><br>
                    <span id="cou0" style="font-size:80px; color:red;line-height:1.5">GUICHÊ 02</span>
                </div>
            </div>
        </div>

                </section>
            </div>
        </div>

        <footer class="page-footer" style="padding:0;margin-top:0">
            <div class="footer-copyright">
                <div class="container">
                    <span> <a class="grey-text text-lighten-3" href="http://www.justlabtech.com" target="_blank">RPclinic</a></span>
                    <span class="right"> <span class="grey-text text-lighten-3">Versão</span> 1.0</span>
                </div>
            </div>
        </footer>
                <script type="text/javascript" src="{{ asset('painel_chamada/js/plugins/jquery-1.11.2.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('painel_chamada/js/materialize.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('painel_chamada/js/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('painel_chamada/js/plugins.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('painel_chamada/js/voice.min.js') }}"></script>
        <script>
            $(function() {
                $('#main').css({'min-height': $(window).height()-114+'px'});
            });
            $(window).resize(function() {
                $('#main').css({'min-height': $(window).height()-114+'px'});
            });

            (function($){
                $.extend({
                    playSound: function(){
                    return $("<embed src='"+arguments[0]+".mp3' hidden='true' autostart='true' loop='false' class='playSound'>" + "<audio autoplay='autoplay' style='display:none;' controls='controls'><source src='"+arguments[0]+".mp3' /><source src='"+arguments[0]+".ogg' /></audio>").appendTo('body');
                    }
                });
            })(jQuery);
        </script>
            
            </body>
</html>
