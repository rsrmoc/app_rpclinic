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

  <!-- Glassmorphism Styles -->
  <style>
    body.page-header-fixed {
      background: radial-gradient(circle at top right, #0f172a, #111827, #010b13) !important;
      background-attachment: fixed !important;
      color: #cbd5e1 !important;
      font-family: 'Inter', -apple-system, sans-serif !important;
    }

    .page-content {
      background: transparent !important;
    }

    .page-inner {
      background: transparent !important;
      border: none !important;
    }

    /* Glass Effect Utility */
    .glass-panel {
      background: rgba(255, 255, 255, 0.05) !important;
      backdrop-filter: blur(16px) saturate(180%) !important;
      -webkit-backdrop-filter: blur(16px) saturate(180%) !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      border-radius: 16px !important;
      box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3) !important;
    }

    .glass-panel-dark {
      background: rgba(15, 23, 42, 0.6) !important;
      backdrop-filter: blur(20px) !important;
      -webkit-backdrop-filter: blur(20px) !important;
      border: 1px solid rgba(255, 255, 255, 0.08) !important;
    }

    /* Sidebar Styling */
    .page-sidebar {
      background: #0f172a !important;
      backdrop-filter: blur(20px) !important;
      -webkit-backdrop-filter: blur(20px) !important;
      border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
      z-index: 9999 !important;
      position: fixed !important;
      overflow: hidden !important;
    }

    .page-sidebar-inner {
      overflow: hidden !important;
    }

    .slimScrollDiv {
      overflow: hidden !important;
    }

    .menu.accordion-menu,
    .menu.accordion-menu li,
    .menu.accordion-menu li a {
      overflow: hidden !important;
    }

    .menu.accordion-menu li.active {
      overflow: hidden !important;
    }

    .page-sidebar .sidebar-menu li a {
      color: #94a3b8 !important;
      background: transparent !important;
      padding: 12px 20px !important;
      transition: all 0.3s ease !important;
      display: flex !important;
      align-items: center !important;
      border: none !important;
    }

    .page-sidebar .sidebar-menu li a:hover, 
    .page-sidebar .sidebar-menu li.active a {
      color: #2dd4bf !important;
      background: rgba(45, 212, 191, 0.08) !important;
      border-left: 3px solid #2dd4bf !important;
    }

    .page-sidebar .sidebar-menu li.active a i,
    .page-sidebar .sidebar-menu li.active a span,
    .page-sidebar .sidebar-menu li.active a p {
      color: #2dd4bf !important;
      margin-bottom: 0 !important;
    }

    .sidebar-menu li.droplink.open > a {
      background: rgba(255, 255, 255, 0.03) !important;
    }

    .sub-menu {
      background: rgba(0, 0, 0, 0.2) !important;
      border: none !important;
    }

    /* Navbar Styling */
    .navbar {
      background: rgba(15, 23, 42, 0.95) !important;
      backdrop-filter: blur(15px) !important;
      -webkit-backdrop-filter: blur(15px) !important;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
      height: 60px !important;
      z-index: 101 !important;
    }

    .navbar-inner {
      background: transparent !important;
      height: 60px !important;
      display: flex !important;
      align-items: center !important;
    }

    .navbar .logo-box {
      background: transparent !important;
      border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
      height: 60px !important;
      display: flex !important;
      align-items: center !important;
      padding: 0 20px !important;
    }

    .navbar .top-menu .navbar-nav {
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        height: 60px !important;
    }

    .navbar .top-menu .navbar-nav li {
        height: 60px !important;
        display: flex !important;
        align-items: center !important;
    }

    .navbar .top-menu .navbar-nav li a {
      color: #e2e8f0 !important;
      background: transparent !important;
      padding: 0 15px !important;
      height: 40px !important;
      display: flex !important;
      align-items: center !important;
      border-radius: 8px !important;
      transition: all 0.2s ease;
      font-weight: 500 !important;
    }

    .navbar .top-menu .navbar-nav li a:hover {
      color: #2dd4bf !important;
      background: rgba(45, 212, 191, 0.1) !important;
    }

    .navbar .top-menu .navbar-nav li a i,
    .navbar .top-menu .navbar-nav li a span {
      margin-right: 8px !important;
    }

    .img-circle.avatar {
      width: 32px !important;
      height: 32px !important;
      border: 2px solid rgba(45, 212, 191, 0.3) !important;
      margin-left: 10px !important;
    }

    /* Page Header & Breadcrumb */
    .page-title {
        background: transparent !important;
        margin-bottom: 20px !important;
        padding: 0 !important;
    }

    .page-title h3 {
        color: white !important;
        font-weight: 700 !important;
        margin: 0 !important;
    }

    .breadcrumb {
        background: transparent !important;
        padding: 0 !important;
        margin: 5px 0 0 0 !important;
    }

    .breadcrumb li a, .breadcrumb li {
        color: #94a3b8 !important;
    }

    .breadcrumb li.active {
        color: #2dd4bf !important;
    }

    /* Content Area Fixes */
    .page-inner {
      padding: 20px 30px !important;
      position: relative !important;
      z-index: 1 !important;
    }

    .page-content {
      padding-top: 0 !important;
      margin-top: 0 !important;
      position: relative !important;
      z-index: 1 !important;
    }

    .page-content.content-wrap {
      background: transparent !important;
    }

    #main-wrapper {
        margin-top: 20px !important;
        position: relative !important;
        z-index: 1 !important;
    }

    /* Cards Row Spacing */
    .row {
        margin-bottom: 20px !important;
    }

    /* Loader Styling */
    #rpclinica-loader {
      background: #0f172a !important;
    }

    #rpclinica-loader-loader {
      border-top-color: #2dd4bf !important;
    }

    /* Global Panel/Card Overrides */
    .panel-white {
      background: rgba(255, 255, 255, 0.03) !important;
      backdrop-filter: blur(10px) !important;
      border: 1px solid rgba(255, 255, 255, 0.05) !important;
      border-radius: 20px !important;
      color: white !important;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2) !important;
    }

    .panel-heading {
      border-bottom: none !important;
      border: none !important;
      background: transparent !important;
    }

    .panel-white .panel-heading {
      border-bottom: none !important;
      border: none !important;
    }

    .panel > .panel-heading {
      border-bottom: none !important;
      border: none !important;
    }

    .panel-title {
      color: #2dd4bf !important;
      font-weight: 600 !important;
      text-transform: uppercase !important;
      letter-spacing: 0.5px !important;
    }

    h3, h4, h5, .info-box-title {
      color: #e2e8f0 !important;
    }

    .counter {
      color: white !important;
      font-weight: 700 !important;
    }

    .info-box-stats {
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
    }

    /* Info Box Fixes */
    .info-box {
        min-height: 120px !important;
        display: flex !important;
        align-items: center !important;
    }

    .info-box-icon {
        top: 50% !important;
        transform: translateY(-50%) !important;
        right: 25px !important;
    }

    /* Chart/Graph Styling */
    #flot1, #flot2, #flot3, #flot4, #flot5 {
        background: transparent !important;
    }

    .flot-base, .flot-overlay {
        background: transparent !important;
    }

    /* Flot Chart Grid Lines */
    .flot-tick-label {
        color: #64748b !important;
    }

    /* Chart Canvas Background */
    .panel-body canvas {
        background: transparent !important;
    }

    /* Fix for chart container */
    .panel-body > div[style*="height"] {
        background: transparent !important;
    }

    /* Modal Styling */
    .modal-content {
      background: rgba(15, 23, 42, 0.9) !important;
      backdrop-filter: blur(25px) !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      border-radius: 24px !important;
      color: white !important;
    }

    .modal-header {
      border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    .modal-footer {
      border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    .form-control {
      background: rgba(255, 255, 255, 0.05) !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      color: white !important;
      border-radius: 10px !important;
    }

    .form-control:focus {
      border-color: #2dd4bf !important;
      box-shadow: 0 0 0 2px rgba(45, 212, 191, 0.2) !important;
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    ::-webkit-scrollbar-track {
      background: rgba(15, 23, 42, 0.5);
    }

    ::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    /* Helper Utilities */
    .flex { display: flex !important; }
    .items-center { align-items: center !important; }
    .justify-between { justify-content: space-between !important; }
    .justify-center { justify-content: center !important; }
    .flex-col { flex-direction: column !important; }
    .gap-2 { gap: 0.5rem !important; }
    .mr-2 { margin-right: 0.5rem !important; }
    .mr-3 { margin-right: 0.75rem !important; }
    .mt-4 { margin-top: 1rem !important; }
    .mb-0 { margin-bottom: 0 !important; }
    .px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
    .py-3 { padding-top: 0.75rem !important; padding-bottom: 0.75rem !important; }
    .py-10 { padding-top: 2.5rem !important; padding-bottom: 2.5rem !important; }
    .py-20 { padding-top: 5rem !important; padding-bottom: 5rem !important; }
    .overflow-hidden { overflow: hidden !important; }
    .text-white { color: white !important; }
    .text-teal-400 { color: #2dd4bf !important; }
    .text-rose-400 { color: #fb7185 !important; }
    .text-slate-300 { color: #cbd5e1 !important; }
    .text-slate-500 { color: #64748b !important; }
    .text-slate-600 { color: #475569 !important; }
    .font-bold { font-weight: 700 !important; }
    .transition-colors { transition-property: color, background-color, border-color !important; transition-duration: 200ms !important; }
    .hover\:text-teal-300:hover { color: #5eead4 !important; }
    /* --- ADDED GLOBAL OVERRIDES --- */

    /* Fix Header Overlap Globally */
    .page-title {
        padding-top: 50px !important;
        background: transparent !important;
        margin-bottom: 20px !important;
    }

    /* Global Tabs Styling */
    .nav-tabs {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .nav-tabs > li > a {
        color: #94a3b8 !important;
        background-color: transparent !important;
        border: 1px solid transparent !important;
        transition: all 0.3s ease !important;
    }
    .nav-tabs > li > a:hover {
        background-color: #22BAA0 !important; /* Green Hover */
        color: #fff !important;
        border-color: #22BAA0 !important;
    }
    .nav-tabs > li.active > a, 
    .nav-tabs > li.active > a:hover, 
    .nav-tabs > li.active > a:focus {
        background-color: rgba(45, 212, 191, 0.1) !important; /* Teal Transparent */
        color: #2dd4bf !important; /* Teal 400 */
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-bottom-color: transparent !important;
    }

    /* Global Table Styling */
    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        border-top: 1px solid rgba(255,255,255,0.1) !important;
        color: #cbd5e1 !important;
    }
    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: rgba(255,255,255,0.02) !important;
    }
    .table-hover > tbody > tr:hover {
        background-color: rgba(255,255,255,0.05) !important;
    }

    /* Global Select2 Dark Mode */
    .select2-container--default .select2-selection--single {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 8px !important;
        height: 36px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #f1f5f9 !important;
        line-height: 34px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 34px !important;
    }
    .select2-dropdown {
        background-color: #0f172a !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(10px) !important;
    }
    .select2-search__field {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border: none !important;
    }
    .select2-results__option {
        color: #cbd5e1 !important;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: #22BAA0 !important;
        color: white !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: rgba(45, 212, 191, 0.2) !important;
    }

    /* Select2 Multiple Fix */
    .select2-container--default .select2-selection--multiple {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 8px !important;
        min-height: 38px !important;
        color: white !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: rgba(45, 212, 191, 0.2) !important;
        border: 1px solid rgba(45, 212, 191, 0.3) !important;
        color: #2dd4bf !important;
        border-radius: 4px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fb7185 !important;
        margin-right: 5px !important;
    }
    .select2-container--default .select2-search--inline .select2-search__field {
        background: transparent !important;
        color: white !important;
        font-family: inherit !important;
    }
    
    /* Fix Search Box in Dropdown */
    .select2-search--dropdown .select2-search__field {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 4px !important;
    }

    /* Global Form Inputs */
    input[type="text"], input[type="password"], input[type="email"], input[type="number"], input[type="date"], select, textarea {
        background-color: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }
    
    /* Panel Content Fix */
    .panel-body {
        background: transparent !important;
    }
    .modal-body {
        background: transparent !important;
    }
    .page-footer {
        background: transparent !important;
        color: #94a3b8 !important;
        border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
        padding: 15px 30px !important;
    }

    /* Fix White Background on Active Table Rows (like in Confirmation tab) */
    .table > thead > tr.active > th,
    .table > tbody > tr.active > td,
    .table > tfoot > tr.active > td,
    .table > thead > tr.active > td,
    .table > tbody > tr.active > th,
    .table > tfoot > tr.active > th {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #fff !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Table Contextual Classes - Dark Theme Overrides */
    .table > tbody > tr.info > td, .table > tbody > tr.info > th,
    .table > tbody > tr > td.info, .table > tbody > tr > th.info,
    .table > tfoot > tr > td.info, .table > tfoot > tr > th.info,
    .table > thead > tr > td.info, .table > thead > tr > th.info {
        background-color: rgba(56, 189, 248, 0.15) !important; /* Sky blue tint */
        color: #bae6fd !important;
        border-color: rgba(56, 189, 248, 0.2) !important;
    }

    .table > tbody > tr.success > td, .table > tbody > tr.success > th,
    .table > tbody > tr > td.success, .table > tbody > tr > th.success,
    .table > tfoot > tr > td.success, .table > tfoot > tr > th.success,
    .table > thead > tr > td.success, .table > thead > tr > th.success {
        background-color: rgba(45, 212, 191, 0.15) !important; /* Teal tint */
        color: #99f6e4 !important;
        border-color: rgba(45, 212, 191, 0.2) !important;
    }

    .table > tbody > tr.danger > td, .table > tbody > tr.danger > th,
    .table > tbody > tr > td.danger, .table > tbody > tr > th.danger,
    .table > tfoot > tr > td.danger, .table > tfoot > tr > th.danger,
    .table > thead > tr > td.danger, .table > thead > tr > th.danger {
        background-color: rgba(251, 113, 133, 0.15) !important; /* Rose tint */
        color: #fecdd3 !important;
        border-color: rgba(251, 113, 133, 0.2) !important;
    }

    .table > tbody > tr.warning > td, .table > tbody > tr.warning > th,
    .table > tbody > tr > td.warning, .table > tbody > tr > th.warning,
    .table > tfoot > tr > td.warning, .table > tfoot > tr > th.warning,
    .table > thead > tr > td.warning, .table > thead > tr > th.warning {
        background-color: rgba(251, 146, 60, 0.15) !important; /* Orange tint */
        color: #fed7aa !important;
        border-color: rgba(251, 146, 60, 0.2) !important;
    }

  </style>

  
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
    @include('rpclinica.layout.header')
    <!-- header-->
 
    <!-- sidebar-->
    @include('rpclinica.layout.menu')
    <!-- sidebar-->
    
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
  @include('rpclinica.layout.footer')
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
