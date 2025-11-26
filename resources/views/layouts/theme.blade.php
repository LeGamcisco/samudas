<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset("assets/dist/css/bootstrap.min.css") }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset("assets/dist/vendor/toastr/toastr.min.css") }}">
    <title>SAMUDAS - @yield("title")</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg bg-info text-white">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img src="{{ asset('assets/dist/img/logo-samu.png') }}" height="66" alt="SAMUDAS" class="img img-brand">
            <span class="ms-2 text-white h3" style="letter-spacing: 10px;">DAS</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item text-white">
              <a class="nav-link text-white {{ (request()->is("/") || request()->is("monitoring/*")) ? "active" : "" }}" aria-current="page" href="{{ url("/") }}">Monitoring</a>
            </li>
            <li class="nav-item text-white">
              <a class="nav-link text-white {{ request()->is("das-logs*") ? "active" : "" }}" aria-current="page" href="{{ url("/das-logs") }}">Logs Data</a>
            </li>
            <li class="nav-item text-white">
              <a class="nav-link text-white {{ request()->is("rca-logs*") ? "active" : "" }}" aria-current="page" href="{{ url("/rca-logs") }}">RCA Logs</a>
            </li>
            <li class="nav-item text-white">
              <a class="nav-link text-white {{ request()->is("measurements*") ? "active" : "" }}" aria-current="page" href="{{ url("/measurements") }}">1 Hour Avg.</a>
            </li>
            @if(Auth::check())
            <li class="nav-item text-white dropdown">
              <a class="nav-link text-white {{ request()->is("master*") ? "active" : "" }} dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Master Data
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route("master.stack.index") }}">Stacks</a></li>
                <li><a class="dropdown-item" href="{{ route("master.sensor.index") }}">Sensors</a></li>
                <li><a class="dropdown-item" href="{{ route("master.reference.index") }}">Refereneces</a></li>
                <li><a class="dropdown-item" href="{{ route("master.unit.index") }}">Units</a></li>
                <li><a class="dropdown-item" href="{{ route("master.user.index") }}">Administrator</a></li>
              </ul>
            </li>
            <li class="nav-item text-white">
              <a class="nav-link text-white {{ request()->is("configuration*") ? "active" : "" }}" href="{{ route("configuration.index") }}">Configuration</a>
            </li>
            <li class="nav-item text-white">
              <a class="nav-link text-white text-danger" href="{{ route("login.logout") }}">Logout</a>
            </li>
            @else
            <li class="nav-item text-white">
              <a class="nav-link text-white {{ request()->is("auth/*") ? "active" : "" }}" href="{{ url("auth/login") }}">Settings</a>
            </li>
            @endif
          </ul>
        </div>
      </div>
    </nav>
    @yield("content")
    <div class="bg-info text-white">
        <div class="container py-3 w-100 d-flex justify-content-between align-items-center">
          <p class="py-0">&copy; Copyright <a href="" class="text-decoration-none" target="_blank">PT. Samu Sinergi Mandiri</a> | <strong>2025</strong> <small class="text-white">v1 | {{ app()->version() }}</small></p>
          <small id="localtime" class="text-white">{{ now()->format("d/m/Y H:i:s") }}</small>
        </div>
    </div>
    @yield("modal")
    <!-- Bootstrap 5.x -->
    <script src="{{ asset("assets/dist/js/bootstrap.bundle.min.js") }}"></script>
    <!-- jQuery 3.7.1 -->
    <script src="{{ asset("assets/dist/js/jquery-3.7.1.min.js") }}"></script>
    <!-- Toastr -->
    <script src="{{ asset("assets/dist/vendor/toastr/toastr.min.js") }}"></script>
    <!-- MomentJS -->
    <script src="{{ asset("assets/dist/vendor/momentjs/moment.min.js") }}"></script>
    <script src="{{ asset("assets/dist/vendor/momentjs/moment-with-locales.min.js") }}"></script>
    @if (session()->has("success"))
      <script>
        toastr.success("{{ session()->get("success") }}");
      </script>
    @endif
    @if (session()->has("error"))
      <script>
        toastr.error("{{ session()->get("error") }}");
      </script>
    @endif
    <script>
      $(document).ready(function() {
        function clock(){
           const now = moment().locale("id");
           $("#localtime").text(now.format("dddd, DD MMMM YYYY HH:mm:ss"));
           setTimeout(clock, 1000);
        }
        clock()
      })
    </script>
    @yield("js")
  </body>
</html>
