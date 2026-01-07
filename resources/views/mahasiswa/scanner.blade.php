@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="container">
                    <h3>Scanner Absensi</h3>

                    <div id="reader" style="width:500px"></div>

                    <div class="mt-3">
                        <p>Hasil scan:</p>
                        <pre id="result">Belum ada hasil</pre>
                    </div>

                    <script src="https://unpkg.com/html5-qrcode@2.3.7"></script>
                    <script>
                        const resultEl = document.getElementById('result');
                        const readerId = 'reader';

                        function onScanSuccess(decodedText, decodedResult) {
                            // stop kamera setelah scan
                            if (html5QrCode) {
                                html5QrCode.stop().then(() => {}).catch(() => {});
                            }
                            resultEl.textContent = decodedText;

                            // kirim hasil ke API langsung menggunakan axios
                            ensureCsrf().then(() => {
                                return axios.post('/api/admin/scan', { qr_data: decodedText });
                            }).then(response => {
                                alert(response.data.message || 'Terkirim');
                            }).catch(err => {
                                alert('Gagal kirim: ' + (err.response?.data?.message || err.message));
                            });
                        }

                        function onScanFailure(error) {
                            // optional: show errors to console
                            console.debug('QR scan failure', error);
                        }

                        let html5QrCode = null;

                        function showRetryButton() {
                            if (document.getElementById('camera-perm-btn')) return;
                            const btn = document.createElement('button');
                            btn.id = 'camera-perm-btn';
                            btn.className = 'btn btn-primary mt-2';
                            btn.textContent = 'Minta Izin Kamera';
                            btn.onclick = function() {
                                // explicitly request permission
                                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                                    alert('Browser Anda tidak mendukung getUserMedia');
                                    return;
                                }
                                navigator.mediaDevices.getUserMedia({
                                    video: true
                                }).then(stream => {
                                    // stop temporary stream, then start scanner
                                    stream.getTracks().forEach(t => t.stop());
                                    startScanner();
                                }).catch(err => {
                                    alert('Izin kamera ditolak: ' + err.message);
                                });
                            };
                            resultEl.parentNode.appendChild(btn);
                        }

                        function startScanner() {
                            if (!window.Html5Qrcode) {
                                resultEl.textContent = 'Library QR tidak tersedia.';
                                return;
                            }

                            if (html5QrCode) {
                                // already created
                            } else {
                                html5QrCode = new Html5Qrcode(readerId);
                            }

                            const config = {
                                fps: 10,
                                qrbox: 250
                            };

                            // prefer environment (back) camera; fallback to user (front)
                            html5QrCode.start({
                                    facingMode: 'environment'
                                }, config, onScanSuccess, onScanFailure)
                                .catch(err => {
                                    console.debug('start with environment failed', err);
                                    // try user-facing
                                    html5QrCode.start({
                                            facingMode: 'user'
                                        }, config, onScanSuccess, onScanFailure)
                                        .catch(err2 => {
                                            console.debug('start with user failed', err2);
                                            resultEl.textContent = 'Gagal mengakses kamera: ' + (err2.message || err2);
                                            showRetryButton();
                                        });
                                });
                        }

                        // Initial attempt: try to start scanner which will prompt for permission
                        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                            startScanner();
                        } else {
                            resultEl.textContent = 'Perangkat tidak mendukung akses kamera.';
                        }
                    </script>

                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
   

   
@endsection
