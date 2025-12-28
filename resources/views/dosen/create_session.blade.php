@extends('layout.master')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="content-wrapper">
                <h3>Buat Sesi Absensi</h3>
                <form method="POST" action="{{ route('attendance.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah</label>
                        <input name="course_id" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mulai (opsional)</label>
                        <input type="datetime-local" name="starts_at" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Selesai (opsional)</label>
                        <input type="datetime-local" name="ends_at" class="form-control" />
                    </div>
                    <button class="btn btn-primary">Buat Sesi & Tampilkan QR</button>
                </form>
            </div>
        </div>
    </section>
@endsection
