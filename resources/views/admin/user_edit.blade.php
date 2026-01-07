@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h3>Edit Pengguna</h3>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="edit-user-form">
                <div class="form-group">
                    <label>Nama</label>
                    <input id="name" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input id="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select id="role" name="role" class="form-control">
                        <option value="dosen" {{ ($user->role ?? '')=='dosen'? 'selected':'' }}>Dosen</option>
                        <option value="mahasiswa" {{ ($user->role ?? '')=='mahasiswa'? 'selected':'' }}>Mahasiswa</option>
                        <option value="admin" {{ ($user->role ?? '')=='admin'? 'selected':'' }}>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>No Induk</label>
                    <input id="no_induk" name="no_induk" class="form-control" value="{{ old('no_induk', $user->no_induk ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Password (kosongkan jika tidak diubah)</label>
                    <input id="password" name="password" type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">
                </div>
                <button class="btn btn-primary">Simpan</button>
            </form>
            <div id="edit-user-msg" class="mt-2"></div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('edit-user-form').addEventListener('submit', function(e){
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
            ensureCsrf().then(()=> axios.put('/api/admin/users/{{ $user->id }}', payload)).then(res => {
                window.location = '/admin/users';
            }).catch(err => {
                document.getElementById('edit-user-msg').textContent = err.response?.data?.message || err.message;
            }).finally(()=> btn.disabled = false);
        });
    </script>
@endsection
