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
                $pngData = QrCode::format('png')->size(300)->generate($payload);
                $pngBase64 = base64_encode($pngData);
                $src = 'data:image/png;base64,' . $pngBase64;
            } catch (\Throwable $e) {
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
            <div class="alert alert-warning">QR generator tidak tersedia. {{ $qrError }}</div>
        @endif
    </div>

    <div>
        <p>Payload QR:</p>
        <pre>{{ $payload }}</pre>
    </div>
</div>
