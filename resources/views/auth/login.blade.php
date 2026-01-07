<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{config('app.name')}} | Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('adminlte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('adminlte/dist/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="{{asset('adminlte/index2.html')}}"><b>Login In</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">

    @if (session('failed'))
        <div class="alert alert-danger">{{ session('failed') }}</div>
    @endif
    
      <p class="login-box-msg">Sign in to start your session</p>

      <form id="login-form">
        @csrf
        @error('email')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <div class="input-group mb-3 ">
          <input type="email" name="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        @error('password')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        <div class="input-group mb-3 ">
          <input type="password" name="password" class="form-control" placeholder="Password" id="password">
          <div class="input-group-append show-password">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" name="remember" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <div id="login-msg" class="mt-2"></div>

<!--      <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div> -->
      <!-- /.social-auth-links -->

      <p class="mb-1">
        Don't have an account yet?
      </p>
      <p class="mb-0">
        <a href="/register" class="text-center">Register a new account here!</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('adminlte/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('adminlte/dist/js/adminlte.min.js')}}"></script>

<script>
    $('.show-password').on('click', function() {
       if($('#password').attr('type') === 'password'){
           $('#password').attr('type','text');
           $('#password-lock').attr('class','fa-unlock');
        }else{
           $('#password').attr('type','password');
           $('#password-lock').attr('class','fa-lock');
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  // axios defaults and CSRF helper for this standalone view
  axios.defaults.withCredentials = true;
  axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  const _csrf_meta = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  if (_csrf_meta) axios.defaults.headers.common['X-CSRF-TOKEN'] = _csrf_meta;
  function ensureCsrf(){ return axios.get('/sanctum/csrf-cookie'); }
  try { const _t = localStorage.getItem('api_token'); if (_t) axios.defaults.headers.common['Authorization'] = 'Bearer ' + _t; } catch(e){}

  document.getElementById('login-form').addEventListener('submit', function(e){
    e.preventDefault();
    const btn = this.querySelector('button');
    btn.disabled = true;
    const form = new FormData(this);
    const payload = { email: form.get('email'), password: form.get('password') };
    // Login flow: 1) POST /login to create server session, 2) request API token, 3) GET /api/me and redirect
    ensureCsrf()
    .then(() => {
      console.debug('CSRF ensured, submitting /login');
      return axios.post('/login', payload, { withCredentials: true });
    })
    .then(loginRes => {
      console.debug('/login response', loginRes);
      // Try to obtain API token (optional). If it fails, continue using session auth.
      return axios.post('/api/token', payload).then(tokenRes => ({ tokenRes, loginRes })).catch(err => ({ tokenRes: null, loginRes }));
    })
    .then(({ tokenRes, loginRes }) => {
      if (tokenRes && tokenRes.data?.token) {
        const token = tokenRes.data.token;
        localStorage.setItem('api_token', token);
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        console.debug('Stored api token');
      }
      // Now fetch current user via session or token
      return axios.get('/api/me');
    })
    .then(res => {
      console.debug('/api/me', res);
      const user = res.data?.data || {};
      if (user.role === 'mahasiswa') window.location = '/mahasiswa';
      else window.location = '/dashboard';
    })
    .catch(err => {
      console.error('login flow error', err);
      const msg = err.response?.data?.message || err.response?.data || err.message || 'Login failed';
      document.getElementById('login-msg').textContent = typeof msg === 'string' ? msg : JSON.stringify(msg);
    })
    .finally(()=> btn.disabled = false);
  });
</script>
</body>
</html>
