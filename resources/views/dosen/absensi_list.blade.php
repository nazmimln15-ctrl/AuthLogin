@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h3>Absensi: {{ $session->course_id }} (token: {{ $session->token }})</h3>

            <p><a href="{{ url('dosen/absensi/' . $session->id . '/export') }}" class="btn btn-success">Download XLSX</a></p>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $r)
                        <tr>
                            <td>{{ $r->nim ?? '-' }}</td>
                            <td>{{ $r->name ?? '-' }}</td>
                            <td>{{ $r->created_at ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
