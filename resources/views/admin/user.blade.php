@extends('layout.master')

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <h3>Manajemen Pengguna</h3>

            <p><a href="{{ route('admin.users.create') }}" class="btn btn-primary">Buat Akun Baru</a></p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <h4>Dosen</h4>
            <div id="dosen-container">Loading...</div>

            <h4>Mahasiswa</h4>
            <div id="mahasiswa-container">Loading...</div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function renderUserTable(containerId, list, roleLabel){
            const container = document.getElementById(containerId);
            if (!list || list.length === 0) {
                container.innerHTML = '<p>Tidak ada akun ' + roleLabel + '.</p>';
                return;
            }
            const table = document.createElement('table'); table.className = 'table table-bordered';
            const thead = document.createElement('thead'); thead.innerHTML = '<tr><th>Nama</th><th>Email</th><th>No Induk</th><th>Aksi</th></tr>';
            table.appendChild(thead);
            const tbody = document.createElement('tbody');
            list.forEach(u => {
                const tr = document.createElement('tr');
                tr.innerHTML = '<td>' + (u.name||'-') + '</td><td>' + (u.email||'-') + '</td><td>' + (u.no_induk||'-') + '</td>';
                const td = document.createElement('td'); td.style.whiteSpace='nowrap';
                const edit = document.createElement('a'); edit.className='btn btn-sm btn-secondary'; edit.href = '/admin/users/' + u.id + '/edit'; edit.textContent = 'Edit';
                const del = document.createElement('button'); del.className='btn btn-sm btn-danger'; del.textContent = 'Delete'; del.style.marginLeft='8px';
                del.onclick = function(){
                    if (!confirm('Hapus pengguna ini?')) return;
                    ensureCsrf().then(()=> axios.delete('/api/admin/users/' + u.id)).then(()=> loadUsers()).catch(err=> alert(err.response?.data?.message || err.message));
                };
                td.appendChild(edit); td.appendChild(del);
                tr.appendChild(td);
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);
            container.innerHTML = ''; container.appendChild(table);
        }

        function loadUsers(){
            ensureCsrf().then(()=> Promise.all([
                axios.get('/api/admin/users?role=dosen'),
                axios.get('/api/admin/users?role=mahasiswa')
            ])).then(([dRes, mRes])=>{
                renderUserTable('dosen-container', dRes.data?.data || [], 'dosen');
                renderUserTable('mahasiswa-container', mRes.data?.data || [], 'mahasiswa');
            }).catch(err=>{
                document.getElementById('dosen-container').textContent = 'Gagal memuat: ' + (err.message||'');
                document.getElementById('mahasiswa-container').textContent = 'Gagal memuat: ' + (err.message||'');
            });
        }

        loadUsers();
    </script>
@endsection
