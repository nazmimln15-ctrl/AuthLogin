@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h3>Daftar QR Sesi Absensi</h3>
            <div id="sessions-container">Loading...</div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function renderSessions(list){
            const container = document.getElementById('sessions-container');
            if (!list || list.length === 0) {
                container.innerHTML = '<p>Tidak ada sesi absensi yang dibuat.</p>';
                return;
            }
            const wrap = document.createElement('div');
            wrap.style.display = 'flex'; wrap.style.flexWrap = 'wrap'; wrap.style.gap = '20px';
            list.forEach(s => {
                const card = document.createElement('div');
                card.style.width='260px'; card.style.border='1px solid #ddd'; card.style.padding='10px'; card.style.borderRadius='6px'; card.style.background='#fff';
                const cinner = document.createElement('div'); cinner.style.textAlign='center';
                const qrDiv = document.createElement('div'); qrDiv.style.height='200px';
                try {
                    new QRCode(qrDiv, { text: JSON.stringify({session_token: s.token}), width:200, height:200 });
                } catch(e) {
                    qrDiv.innerHTML = '<div style="height:200px;display:flex;align-items:center;justify-content:center;color:#888;"><small>QR tidak tersedia</small></div>';
                }
                cinner.appendChild(qrDiv);
                card.appendChild(cinner);
                const meta = document.createElement('div'); meta.style.marginTop='8px'; meta.style.fontSize='13px';
                meta.innerHTML = '<strong>Kode:</strong> ' + (s.token||'-') + '<br><strong>Mata Kuliah:</strong> ' + (s.course_id||'-') + '<br><strong>Mulai:</strong> ' + (s.starts_at||'-');
                card.appendChild(meta);
                const actions = document.createElement('div'); actions.style.marginTop='8px'; actions.style.display='flex'; actions.style.gap='8px';
                const a1 = document.createElement('a'); a1.className='btn btn-sm btn-primary'; a1.href = '/attendance/' + s.id; a1.textContent = 'Lihat';
                const a2 = document.createElement('a'); a2.className='btn btn-sm btn-success'; a2.href = '/absensi/' + s.id + '/export'; a2.textContent = 'Export XLSX';
                actions.appendChild(a1); actions.appendChild(a2);
                card.appendChild(actions);
                wrap.appendChild(card);
            });
            container.innerHTML = ''; container.appendChild(wrap);
        }

        ensureCsrf().then(() => axios.get('/api/admin/sessions')).then(res => {
            renderSessions(res.data?.data || []);
        }).catch(err => {
            document.getElementById('sessions-container').textContent = 'Gagal memuat sesi: ' + (err.message||'');
        });
    </script>
@endsection
