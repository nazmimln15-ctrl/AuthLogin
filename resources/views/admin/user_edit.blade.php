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

            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nama</label>
                    <input name="name" class="form-control" value="{{ old('name', $user->name) }}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input name="email" class="form-control" value="{{ old('email', $user->email) }}">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="dosen" {{ $user->role=='dosen'? 'selected':'' }}>Dosen</option>
                        <option value="mahasiswa" {{ $user->role=='mahasiswa'? 'selected':'' }}>Mahasiswa</option>
                        <option value="admin" {{ $user->role=='admin'? 'selected':'' }}>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>No Induk</label>
                    <input name="no_induk" class="form-control" value="{{ old('no_induk', $user->no_induk) }}">
                </div>
                <div class="form-group">
                    <label>Password (kosongkan jika tidak diubah)</label>
                    <input name="password" type="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input name="password_confirmation" type="password" class="form-control">
                </div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
@endsection
