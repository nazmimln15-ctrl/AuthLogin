@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h3>Daftar Sesi Absensi</h3>
            <div id="sessions-list">Loading...</div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function renderSessions(list){
            const container = document.getElementById('sessions-list');
            if (!list || list.length === 0) return container.innerHTML = '<p>Tidak ada sesi absensi</p>';
            const ul = document.createElement('ul');
            list.forEach(s => {
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.href = '/absensi/' + s.id;
                a.textContent = (s.course_id || '-') + ' - token: ' + (s.token || '-');
                    li.appendChild(a);
                    // delete link
                    const del = document.createElement('a');
                    del.href = '#';
                    del.className = 'btn btn-sm btn-danger ml-2';
                    del.style.marginLeft = '8px';
                    del.textContent = 'Hapus';
                    del.onclick = function(e){
                        e.preventDefault();
                        if (!confirm('Hapus sesi ini?')) return;
                        ensureCsrf().then(()=> axios.delete('/api/admin/sessions/' + s.id)).then(()=>{
                            // refresh list
                            loadSessions();
                        }).catch(err=>{
                            alert('Gagal hapus: ' + (err.response?.data?.message || err.message));
                        });
                    };
                    li.appendChild(del);
                ul.appendChild(li);
            });
            container.innerHTML = '';
            container.appendChild(ul);
        }

        function loadSessions(){
            document.getElementById('sessions-list').textContent = 'Loading...';
            ensureCsrf().then(()=> axios.get('/api/admin/sessions')).then(res => {
                renderSessions(res.data?.data || []);
            }).catch(err => { document.getElementById('sessions-list').textContent = 'Gagal memuat sesi.'; });
        }

        loadSessions();
    </script>
        </div>
    </div>
@endsection
