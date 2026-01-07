@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h3>Buat Akun Baru</h3>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="create-user-form">
                <div class="form-group">
                    <label>Nama</label>
                    <input id="name" name="name" class="form-control" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input id="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select id="role" name="role" class="form-control">
                        <option value="dosen">Dosen</option>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>No Induk</label>
                    <input id="no_induk" name="no_induk" class="form-control" value="{{ old('no_induk') }}">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input id="password" name="password" type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
                </div>
                <button class="btn btn-primary">Buat</button>
            </form>
            <div id="create-user-msg" class="mt-2"></div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('create-user-form').addEventListener('submit', function(e){
            e.preventDefault();
            const btn = this.querySelector('button');
            btn.disabled = true;
            const payload = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                role: document.getElementById('role').value,
                no_induk: document.getElementById('no_induk').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('password_confirmation').value,
            };
            ensureCsrf().then(()=> axios.post('/api/admin/users', payload)).then(res => {
                window.location = '/admin/users';
            }).catch(err => {
                document.getElementById('create-user-msg').textContent = err.response?.data?.message || err.message;
            }).finally(()=> btn.disabled = false);
        });
    </script>
@endsection
