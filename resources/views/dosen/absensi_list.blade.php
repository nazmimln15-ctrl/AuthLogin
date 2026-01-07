@extends('layout.master')

@section('content')

    <div class="content-wrapper">
        <div class="container">
            <h3 id="absensi-title">Absensi</h3>

            <p><a id="download-xlsx" class="btn btn-success" href="#">Download XLSX</a></p>

            <div id="absensi-table">Loading...</div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        (function(){
            const parts = window.location.pathname.split('/').filter(Boolean);
            const id = parts[parts.length-1];
            if (!id) return document.getElementById('absensi-table').textContent = 'Invalid session id';

            function render(rows, session){
                document.getElementById('absensi-title').textContent = 'Absensi: ' + (session?.course_id || '-') + ' (token: ' + (session?.token || '-') + ')';
                document.getElementById('download-xlsx').href = '/absensi/' + id + '/export';
                if (!rows || rows.length === 0) return document.getElementById('absensi-table').innerHTML = '<p>Tidak ada data absensi.</p>';
                const table = document.createElement('table'); table.className = 'table table-striped';
                const thead = document.createElement('thead'); thead.innerHTML = '<tr><th>NIM</th><th>Nama</th><th>Waktu</th></tr>';
                table.appendChild(thead);
                const tbody = document.createElement('tbody');
                rows.forEach(r => {
                    const tr = document.createElement('tr');
                    // Attendance rows include a `user` relation. Fallback to direct props if present.
                    const nimVal = (r.user && (r.user.no_induk || r.user.nim)) || r.nim || '-';
                    const nameVal = (r.user && (r.user.name || r.user.full_name)) || r.name || '-';
                    const whenVal = r.attended_at || r.created_at || (r.createdAt || '-') ;
                    const nim = document.createElement('td'); nim.textContent = nimVal;
                    const name = document.createElement('td'); name.textContent = nameVal;
                    const when = document.createElement('td'); when.textContent = whenVal;
                    tr.appendChild(nim); tr.appendChild(name); tr.appendChild(when);
                    tbody.appendChild(tr);
                });
                table.appendChild(tbody);
                document.getElementById('absensi-table').innerHTML = ''; document.getElementById('absensi-table').appendChild(table);
            }

            ensureCsrf().then(()=> axios.get('/api/admin/sessions/' + id + '/attendances')).then(res => {
                const data = res.data?.data || {};
                render(data.rows || [], data.session || {});
            }).catch(err => {
                document.getElementById('absensi-table').textContent = 'Gagal memuat data: ' + (err.message||'');
            });
        })();
    </script>
@endsection
