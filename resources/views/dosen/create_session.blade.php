@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">

                <h3>Buat Sesi Absensi</h3>
                <form id="create-session-form">
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah</label>
                        <input id="course_id" name="course_id" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mulai (opsional)</label>
                        <input id="starts_at" type="datetime-local" name="starts_at" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Selesai (opsional)</label>
                        <input id="ends_at" type="datetime-local" name="ends_at" class="form-control" />
                    </div>
                    <button class="btn btn-primary">Buat Sesi & Tampilkan QR</button>
                </form>

                <div id="created-session" class="mt-4" style="display:none;">
                    <h4>Session Created</h4>
                    <p><strong>Course:</strong> <span id="out-course"></span></p>
                    <p><strong>Token:</strong> <span id="out-token"></span></p>
                    <div id="qr-output"></div>
                    <pre id="out-payload" style="white-space:pre-wrap;background:#f8f9fa;padding:8px;border-radius:4px;"></pre>
                </div>
            </div>
    </div>
    </section>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.getElementById('create-session-form').addEventListener('submit', function(e){
            e.preventDefault();
            const btn = this.querySelector('button');
            btn.disabled = true;
            const data = {
                course_id: document.getElementById('course_id').value,
                starts_at: document.getElementById('starts_at').value || null,
                ends_at: document.getElementById('ends_at').value || null,
            };
            ensureCsrf().then(() => axios.post('/api/admin/sessions', data))
            .then(res => {
                const s = res.data?.data;
                if (!s) throw new Error('API did not return session');
                const payload = JSON.stringify({ session_token: s.token, course_id: s.course_id });
                document.getElementById('out-course').textContent = s.course_id || '-';
                document.getElementById('out-token').textContent = s.token || '-';
                document.getElementById('out-payload').textContent = payload;
                document.getElementById('created-session').style.display = 'block';
                document.getElementById('qr-output').innerHTML = '';
                new QRCode(document.getElementById('qr-output'), { text: payload, width: 300, height: 300 });
            }).catch(err => {
                alert('Gagal membuat sesi: ' + (err.response?.data?.message || err.message));
            }).finally(()=> btn.disabled = false);
        });
    </script>
@endsection
