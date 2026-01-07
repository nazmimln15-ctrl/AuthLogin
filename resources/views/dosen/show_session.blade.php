@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="container text-center">
                    <h3 id="session-title">Sesi Absensi</h3>
                    <p>Token: <span id="session-token">-</span></p>

                    <div class="mb-3 text">
                        <p>QR Code (scan oleh mahasiswa):</p>
                        <div class="d-flex justify-content-center">
                            <div class="align-self-center p-3 bg-light">
                                <div id="qr-output"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p>Payload QR:</p>
                        <pre id="payload-out"></pre>
                    </div>

                </div>
            </div>
    </div>
    </section>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        (function() {
            // extract session id from URL (last segment)
            const parts = window.location.pathname.split('/').filter(Boolean);
            const id = parts[parts.length - 1];
            if (!id) return document.getElementById('payload-out').textContent = 'Invalid session id';
            ensureCsrf().then(() => axios.get('/api/admin/sessions/' + id)).then(res => {
                const s = res.data?.data;
                if (!s) throw new Error('No session data');
                document.getElementById('session-title').textContent = 'Sesi Absensi: ' + (s.course_id || '-');
                document.getElementById('session-token').textContent = s.token || '-';
                const payload = JSON.stringify({
                    session_token: s.token,
                    course_id: s.course_id
                });
                document.getElementById('payload-out').textContent = payload;
                document.getElementById('qr-output').innerHTML = '';
                new QRCode(document.getElementById('qr-output'), {
                    text: payload,
                    width: 300,
                    height: 300
                });
            }).catch(err => {
                document.getElementById('payload-out').textContent = err.response?.data?.message || err.message;
            });
        })();
    </script>
@endsection
