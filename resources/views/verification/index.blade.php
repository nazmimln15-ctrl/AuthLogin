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
            <b>Verification</b>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">

                @if (session('failed'))
                    <div class="alert alert-danger">{{ session('failed') }}</div>
                @endif

                <p class="login-box-msg" style="item-align:center">Please verify your account!</p>
                <form id="send-otp-form">
                    <input type="hidden" value="register" name="type">
                    <button type="submit" class="btn btn-sm btn-primary">Send OTP to your email</button>
                </form>
                <div id="otp-msg" class="mt-2"></div>
            </div>
            <!-- /.login-card-body -->
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

            document.getElementById('send-otp-form').addEventListener('submit', function(e){
                e.preventDefault();
                const btn = this.querySelector('button');
                btn.disabled = true;
                ensureCsrf().then(() => {
                    return axios.post('/api/admin/verification', { type: 'register' });
                }).then(res => {
                    const unique = res.data?.data?.unique_id;
                    if (unique) {
                        window.location = '/verify/' + unique;
                        return;
                    }
                    document.getElementById('otp-msg').textContent = 'Verification sent';
                }).catch(err => {
                    document.getElementById('otp-msg').textContent = err.response?.data?.message || err.message;
                }).finally(()=> btn.disabled=false);
            });
        </script>

</body>

</html>
