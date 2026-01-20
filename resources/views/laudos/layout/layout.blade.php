<!DOCTYPE html>
<html>

<head>
  <title>RPclinic</title>

  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta charset="UTF-8">
  <meta http-equiv="Pragma" content="no-cache">
  <meta name="Cache-Control" content="no-cache">
  <meta name="Cache-Control" content="max-age=0,must-revalidate">
  <meta http-equiv="Expires" content="-1">
  
  <meta name="description" content="RPclinic" />
  <meta name="keywords" content="RPclinic" />
  <meta name="author" content="RPsys" />
  <link rel="icon" href='{{ asset('assets/images/favicon.svg') }}' type="image/x-icon" />
  <!-- Styles -->
  <link href='{{ asset('assets/fonts/css.css?family=Open+Sans:400,300,600') }}' rel='stylesheet' type='text/css'>

  <link href='{{asset('assets/plugins/pace-master/themes/blue/pace-theme-flash.css')}}' rel="stylesheet" />
  <link href='{{ asset('assets/plugins/uniform/css/uniform.default.min.css') }}' rel="stylesheet" />
  <link href='{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/fontawesome/css/font-awesome.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/line-icons/simple-line-icons.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/offcanvasmenueffects/css/menu_cornerbox.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/waves/waves.min.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/switchery/switchery.min.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/3d-bold-navigation/css/style.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/summernote-master/summernote.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker3.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/bootstrap-colorpicker/css/colorpicker.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}' rel="stylesheet" type="text/css" />

  <!-- Theme Styles -->
  <link href='{{ asset('assets/css/modern.min.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/css/themes/green.css') }}' class="theme-color" rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/css/custom.css') }}' rel="stylesheet" type="text/css" />

  
  <script src='{{ asset('assets/plugins/3d-bold-navigation/js/modernizr.js') }}'></script>
  <script src='{{ asset('assets/plugins/offcanvasmenueffects/js/snap.svg-min.js') }}'></script>

  <link href='{{ asset('assets/plugins/slidepushmenus/css/component.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/select2/css/select2.min.css') }}' rel="stylesheet" type="text/css" />

  <!-- Jquery -->
  <script src='{{ asset('assets/plugins/jquery/jquery-2.1.4.min.js') }}'></script>
  <script src='{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}'></script>
  <script src='{{ asset('assets/plugins/3d-bold-navigation/js/modernizr.js') }}'></script>
  <script src='{{ asset('assets/plugins/offcanvasmenueffects/js/snap.svg-min.js') }}'></script>

  <script src='{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}'></script>
  <script src='{{ asset('assets/plugins/toastr/toastr.min.js') }}'></script>
  <link rel="stylesheet" type="text/css" href='{{ asset('assets/plugins/toastr/toastr.min.css') }}'>

  <script src='{{ asset('assets/plugins/dropzone/dropzone.min.js') }}'></script>
  <link rel="stylesheet" type="text/css" href='{{ asset('assets/plugins/dropzone/dropzone.min.css') }}'>

  <script src='{{ asset('assets/plugins/ckeditor/ckeditor.js') }}'></script>

  <link href='{{ asset('assets/plugins/jstree/themes/default/style.min.css') }}' rel="stylesheet" type="text/css" />

  <link rel="stylesheet" href='{{ asset('css/app.css') }}'>

  <!--<link rel="stylesheet" href='{{ asset('css/simple-calendar.css') }}'> -->
 
  <link href='{{ asset('assets/plugins/fullcalendar/fullcalendar.min.css') }}' rel="stylesheet" type="text/css" />
  <link href='{{ asset('assets/plugins/fullcalendar/fullcalendar.print.css') }}' rel='stylesheet' media='print' />
 
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  @yield('scriptsHeader')

</head>

<!-- <body class="page-header-fixed @if(Session::has('menuCompact')) {{ Session::get('menuCompact') }}  @endif"> -->
<body class="page-header-fixed page-header-fixed small-sidebar "> 
  <div id="rpclinica-loader">
    <img src='{{ asset('assets\images\logo_loader.svg') }}' style="width: 130px;  ">
    <div id="rpclinica-loader-loader"></div>
  </div>


  <form class="search-form" action="#" method="GET">
 
      <div class="input-group"> 
          <div class="row"> 
            <div class="col-lg-2  col-md-3 col-xs-5">
              <input type="date" name="search" class="form-control search-input" placeholder="Search...">
            </div>  
             
          </div>

 
          <span class="input-group-btn">
            <button class="btn btn-default close-search waves-effect waves-button waves-classic" type="button">
              <i class="fa fa-check"></i>
            </button>
          </span>
      
      
      </div><!-- Input Group -->
  
  </form><!-- Search Form -->


  <div class="overlay"></div>
  <main class="page-content content-wrap">
 
    <!-- header-->
    @include('laudos.layout.header')
    <!-- header-->
 

    <div class="page-inner">

      <!-- conteudo da pagina -->
      @yield('content')
      <!-- conteudo da pagina -->

      <div class="page-footer">
        <p class="no-s">2022 &copy; RPclinic</p>
      </div>

    </div>

  </main>

  <!-- footer-->
  @include('laudos.layout.footer')
  <!-- footer-->

  <script src='{{ asset('js/app.js') }}'></script>
  <script language="javascript">
    function moeda(a, e, r, t) {
      let n = ""
        , h = j = 0
        , u = tamanho2 = 0
        , l = ajd2 = ""
        , o = window.Event ? t.which : t.keyCode;
      if (13 == o || 8 == o)
        return !0;
      if (n = String.fromCharCode(o)
        , -1 == "0123456789".indexOf(n))
        return !1;
      for (u = a.value.length
        , h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
      ;
      for (l = ""; h < u; h++)
        -
        1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
      if (l += n
        , 0 == (u = l.length) && (a.value = "")
        , 1 == u && (a.value = "0" + r + "0" + l)
        , 2 == u && (a.value = "0" + r + l)
        , u > 2) {
        for (ajd2 = ""
          , j = 0
          , h = u - 3; h >= 0; h--)
          3 == j && (ajd2 += e
            , j = 0)
          , ajd2 += l.charAt(h)
          , j++;
        for (a.value = ""
          , tamanho2 = ajd2.length
          , h = tamanho2 - 1; h >= 0; h--)
          a.value += ajd2.charAt(h);
        a.value += r + l.substr(u - 2, u)
      }
      return !1
    }

  </script>

  @yield('scripts')

  @if (session()->has('success'))
  <script>
    toastr["success"]('{{ session('success') }}');

  </script>
  @endif
  @if (session()->has('error'))
  <script>
    toastr["error"]('{{ session('error') }}');

  </script>
  @endif
</body>

</html>
