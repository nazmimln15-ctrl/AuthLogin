@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h3>Manajemen Pengguna</h3>

            <p><a href="{{ route('admin.users.create') }}" class="btn btn-primary">Buat Akun Baru</a></p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <h4>Dosen</h4>
            @if($dosen->isEmpty())
                <p>Tidak ada akun dosen.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Nama</th><th>Email</th><th>NIDN</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($dosen as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->no_induk }}</td>
                                <td style="white-space:nowrap;">
                                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.users.edit', $u->id) }}">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <h4>Mahasiswa</h4>
            @if($mahasiswa->isEmpty())
                <p>Tidak ada akun mahasiswa.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr><th>Nama</th><th>Email</th><th>NIM</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswa as $u)
                            <tr>
                                <td>{{ $u->name }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->no_induk }}</td>
                                <td style="white-space:nowrap;">
                                    <a class="btn btn-sm btn-secondary" href="{{ route('admin.users.edit', $u->id) }}">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Hapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
