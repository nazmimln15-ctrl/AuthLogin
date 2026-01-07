<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | Login</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ asset('adminlte/index2.html') }}"><b>Login In</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">

                @if (session('failed'))
                    <div class="alert alert-danger">{{ session('failed') }}</div>
                @endif

                <form id="verify-form">
                    <div class="input-group mb-3 ">
                        <input type="number" id="otp-input" name="otp" class="form-control" placeholder="Enter OTP">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8"></div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </div>
                </form>
                <a id="resend-link" href="/verify">Resend OTP</a>
                <div id="verify-msg" class="mt-2"></div>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.withCredentials = true;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const _csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (_csrf) axios.defaults.headers.common['X-CSRF-TOKEN'] = _csrf;
        function ensureCsrf(){ return axios.get('/sanctum/csrf-cookie'); }

        document.getElementById('verify-form').addEventListener('submit', function(e){
            e.preventDefault();
            const btn = this.querySelector('button');
            btn.disabled = true;
            const otp = document.getElementById('otp-input').value;
            ensureCsrf().then(() => axios.put('/api/admin/verification/{{ $unique_id }}', { otp: otp }))
            .then(res => {
                window.location = '/mahasiswa';
            }).catch(err => {
                document.getElementById('verify-msg').textContent = err.response?.data?.message || 'OTP invalid';
            }).finally(()=> btn.disabled = false);
        });
    </script>

</body>

</html>
