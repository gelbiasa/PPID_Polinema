@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Form Permohonan Layanan</h3>
    </div>
    <div class="card-body">

        <form action="{{ url('permohonan/storeLayanan') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="kategori" value="{{ $kategori }}"> <!-- Hidden field untuk kategori -->

            <div class="form-group">
                <label for="status_pemohon">Status Pemohon</label>
                <select class="form-control" id="status_pemohon" name="status_pemohon" required>
                    <option value="perorangan">Perorangan</option>
                    <option value="organisasi">Organisasi</option>
                </select>
            </div>

            <div class="form-group">
                <label for="judul_pemohon">Judul Permohonan</label>
                <input type="text" class="form-control" id="judul_pemohon" name="judul_pemohon" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="dokumen_pendukung">Dokumen Pendukung</label>
                <input type="file" class="form-control" id="dokumen_pendukung" name="dokumen_pendukung" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
            </div>

            <!-- Status otomatis 'Diproses' (disembunyikan) -->
            <input type="hidden" name="status" value="Diproses">

            <button type="submit" class="btn btn-primary">Ajukan Permohonan</button>
        </form>
    </div>
</div>
@endsection
