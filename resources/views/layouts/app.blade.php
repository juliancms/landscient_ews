<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
</head>
<body>
<div class="page-wrapper chiller-theme toggled">
  <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
  </a>
  <nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
      <div class="sidebar-brand">
        <a href="{{ url()->to('/') }}">Landscient EWS</a>
        <div id="close-sidebar">
          <i class="fas fa-times"></i>
        </div>
      </div>
      <div class="sidebar-menu">
        <ul>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-thermometer-half"></i>
              <span>Raingauges</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="{{ route('raingauges.index') }}">List</a>
                </li>
                <li>
                  <a href="{{ route('raingauges.create') }}">Create</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="sidebar-dropdown">
            <a href="#">
              <i class="fa fa-table"></i>
              <span>Rainfall Data</span>
            </a>
            <div class="sidebar-submenu">
              <ul>
                <li>
                  <a href="{{ route('rainfalldatas.index') }}">Demo DBs</a>
                </li>
                <li>
                  <a href="#">Simulations</a>
                </li>
                <li>
                  <a href="{{ route('rainfalldatas.import') }}">Import Demo DB</a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
      <!-- sidebar-menu  -->
    </div>
    <!-- sidebar-content  -->
    <div class="sidebar-footer">
      <a target="_blank" href="https://landscient.com/">
        <i class="fa fa-globe"></i>
      </a>
      <a target="_blank" href="https://landscient.com/#contact">
        <i class="fa fa-envelope"></i>
      </a>
      <a href="#">
        <i class="fa fa-exclamation-circle"></i>
      </a>
    </div>
  </nav>
  <!-- sidebar-wrapper  -->
  <main class="page-content">
    <div class="container-fluid">
      @yield('content')
    </div>
  </main>
  <!-- page-content" -->
</div>
<!-- page-wrapper -->
</body>
</html>
