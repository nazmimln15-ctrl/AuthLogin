@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h3>Daftar Sesi Absensi</h3>
            <ul>
                @forelse($sessions as $s)
                    <li>
                        <a href="{{ url('absensi/' . $s->id) }}">{{ $s->course_id }} - token: {{ $s->token }}</a>
                    </li>
                    
                @empty
                    <li>Tidak ada sesi absensi</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
