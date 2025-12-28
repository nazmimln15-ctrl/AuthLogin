@extends('layout.master')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="content-wrapper">
                <div class="container text-center">
                    <h3>Sesi Absensi: {{ $session->course_id }}</h3>
                    <p>Token: {{ $session->token }}</p>

                    <div class="mb-3">
                        <p>QR Code (scan oleh mahasiswa):</p>
                        @php
                            $src = null;
                            $svg = null;
                            $qrError = null;

                            try {
                                // Try PNG first (may require imagick). If imagick missing, this will throw.
                                $pngData = QrCode::format('png')->size(300)->generate($payload);
                                $pngBase64 = base64_encode($pngData);
                                $src = 'data:image/png;base64,' . $pngBase64;
                            } catch (\Throwable $e) {
                                // Fallback to SVG which does not require imagick
                                try {
                                    $svg = QrCode::size(300)->generate($payload);
                                } catch (\Throwable $e2) {
                                    $qrError = $e2->getMessage();
                                }
                            }
                        @endphp

                        @if ($src)
                            <img src="{{ $src }}" alt="QR Code" />
                        @elseif($svg)
                            {!! $svg !!}
                        @else
                            <div class="alert alert-warning">QR generator tidak tersedia. Pastikan package simple-qrcode
                                sudah
                                di-install
                                atau periksa error.</div>
                            @if ($qrError)
                                <div class="alert alert-danger mt-2"><strong>Exception:</strong> {{ $qrError }}</div>
                            @endif
                        @endif

                    </div>

                    <div>
                        <p>Payload QR:</p>
                        <pre>{{ $payload }}</pre>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
