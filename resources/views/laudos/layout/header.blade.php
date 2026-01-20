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
      
        <div class="logo-box">
            <a href="#" class="logo-text"><img src="{{ asset('assets\images\logo_menu.svg') }}" style="width: 155px;  "></a>
        </div><!-- Logo Box -->



        <div class="topmenu-outer">
            <div class="top-menu">
              

                <ul class="nav navbar-nav navbar-right">
 
           
              
                    <li>
                        <a href="{{ route('laudos.logout') }}" class="log-out waves-effect waves-button waves-classic">
                            <span><i class="fa fa-sign-out m-r-xs"></i>Sair</span>
                        </a>
                    </li>

                </ul><!-- Nav -->
            </div><!-- Top Menu -->
        </div>
    </div>
</div><!-- Navbar -->
<style>
    input::file-selector-button {
        font-weight: normal; 
        color: #fff;
        background-color: #22BAA0;
        border-color: transparent;
     }
    .center{ text-align: center; }
    .right{ text-align: right; }
    .left{ text-align: left; }
    .red{ color: red; }
    .error{ color: red; }
    .label-aguardando {
        background: #FF9800;
    }
    ul>.active{
          border-top: 3px solid #8CDDCD;
    }
    ul>.active>a>i{
        color: #8CDDCD;
        margin-right: 10px;
        font-size: 15px;
    }
    ul>.active>a>span{
        color: #8CDDCD;
        margin-right: 10px;
        font-size: 15px;
    }
    .fa{
        margin-right: 7px;
    }
    .bold { font-weight: bold;}

    .upper{ text-transform: uppercase; }

    .btn .fa{
        margin-right: 0px;
    }
    .m-b-xs{
        margin-bottom: 0px;
    }

    /* Calendario EVENTO*/


    .event-bg {
            color: #7A6600;
            background-color: #F9F1C8;
            border-color: #E6A21A;
        }

        .event-am {
            color: #7A6600;
            background-color: #FFFCEB;
            border-color: #FFD500;
        }

        .event-ac {
            color: #00627A;
            background-color: #EFF8FB;
            border-color: #00CCFF;
        }

        .event-ae {
            color: #00417A;
            background-color: #C2E2FF;
            border-color: #008CFF;
        }

        .event-lc {
            color: #803C00;
            background-color: #FFF1E5;
            border-color: #FF7700;
        }

        .event-le {
            color: #7A2100;
            background-color: #FFD2C2;
            border-color: #FF4400;

        }

        .event-ll {
            color: #41007A;
            background-color: #F5EBFF;
            border-color: #8800FF;
        }

        .event-rx {
            color: #00107A;
            background-color: #C2CAFF;
            border-color: #0022FF;
        }

        .event-vc {
            color: #226218;
            background-color: #F0FBEF;
            border-color: #40BF4A;
        }

        .event-ve {
            color: #226218;
            background-color: #CAFFC2;
            border-color: #00800B;
}
        }

        .event-rs {
            color: #7A0062;
            background-color: #FFEBFB;
            border-color: #FF00CC;
        }

        .event-vm {
            color: #7A0018;
            background-color: #FFC2CE;
            border-color: #FF0033;
        }
        .event-livre {
            color: #353333;
            background-color: #d6e1df;
            border-color: #8f8c8c;
        }
        .swal2-popup{
            border-radius: 0px;
        }
        .swal-button{
            margin: 2px;
        }

        .swal2-styled.swal2-confirm{
                border-radius: 0;
                font-size: 1.1em;
        }
        .swal2-styled.swal2-cancel{
                border-radius: 0;
                font-size: 1.1em;
        }
        .label-pre-exame {
            background: #F19958;
            color: #fff;
        }
        .label-recepcao {
            background: #627AAC;
            color: #fff;
        }

        ul>.active2 {
            border-bottom: 2px solid #8CDDCD;
            border-top: 2px solid #8CDDCD;
             
        }

        table tr td div.btn-group button.btn, table tr td div.btn-group a.btn, table tr th div.btn-group button.btn, table tr th div.btn-group a.btn {
            font-size: 1.3rem !important;
            padding: 2px 6px !important;
        }

        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: #009688;
            font-weight: bold;
        }

</style>

