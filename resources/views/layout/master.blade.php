<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi Universitas ZZZ</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Navbar -->
    @include('layout._navbar')
    <!-- /.navbar -->
    <div class="wrapper">
        @yield('content')
        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
  </div> -->



        <!-- Main Sidebar Container -->
        @include('layout._sidebar')

        <!-- Content Wrapper. Contains page content -->
        
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Universitas ZZZ.</strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="{{ asset('adminlte/dist/js/demo.js') }}"></script> -->
    <!-- Axios (global for views that call API endpoints) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.withCredentials = true;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const _csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (_csrf) axios.defaults.headers.common['X-CSRF-TOKEN'] = _csrf;
        function ensureCsrf(){
            return axios.get('/sanctum/csrf-cookie');
        }
        // If an API token is stored (from login), use it for Authorization header
        try {
            const _token = localStorage.getItem('api_token');
            if (_token) axios.defaults.headers.common['Authorization'] = 'Bearer ' + _token;
        } catch(e) {}

        // Attempt to fetch CSRF cookie once at page load (non-blocking)
        (function(){
            ensureCsrf().then(() => {
                console.debug('CSRF cookie obtained');
            }).catch((e) => {
                console.warn('Failed to obtain CSRF cookie', e);
            });

            // Redirect to login on 401 so users can re-authenticate
            axios.interceptors.response.use(function(resp){ return resp; }, function(err){
                if (err.response && err.response.status === 401) {
                    window.location = '/login';
                }
                return Promise.reject(err);
            });
        })();
    </script>
    @if(session('api_token'))
    <script>
        try { 
            const t = "{{ session('api_token') }}";
            localStorage.setItem('api_token', t);
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + t;
        } catch(e) {}
    </script>
    @endif
    @yield('js')
</body>

</html>
