@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h3>Daftar QR Sesi Absensi</h3>

            @if($sessions->isEmpty())
                <p>Tidak ada sesi absensi yang dibuat.</p>
            @else
                <div style="display:flex;flex-wrap:wrap;gap:20px;">
                    @foreach($sessions as $s)
                        @php
                            $payload = json_encode(['session_token' => $s->token]);
                            $svg = null;
                            $qrError = null;
                            try {
                                $svg = QrCode::size(200)->generate($payload);
                            } catch (\Exception $e) {
                                $qrError = $e->getMessage();
                            }
                        @endphp

                        <div style="width:260px;border:1px solid #ddd;padding:10px;border-radius:6px;background:#fff;">
                            <div style="text-align:center;">
                                @if($svg)
                                    {!! $svg !!}
                                @else
                                    <div style="height:200px;display:flex;align-items:center;justify-content:center;color:#888;">
                                        <small>QR tidak tersedia</small>
                                    </div>
                                @endif
                            </div>
                            <div style="margin-top:8px;font-size:13px;">
                                <strong>Kode:</strong> {{ $s->token }}<br>
                                <strong>Mata Kuliah:</strong> {{ $s->course_id ?? '-' }}<br>
                                <strong>Mulai:</strong> {{ $s->starts_at ?? '-' }}
                            </div>
                            <div style="margin-top:8px;display:flex;gap:8px;">
                                <a class="btn btn-sm btn-primary" href="{{ url('attendance/' . $s->id) }}">Lihat</a>
                                <a class="btn btn-sm btn-success" href="{{ url('absensi/' . $s->id . '/export') }}">Export XLSX</a>
                            </div>
                            @if($qrError)
                                <div style="margin-top:8px;color:#a00;font-size:12px;">Error QR: {{ $qrError }}</div>
                            @endif
                        </div>

                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
