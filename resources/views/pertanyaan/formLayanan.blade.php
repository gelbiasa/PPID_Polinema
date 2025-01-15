@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <a href="{{ url('pertanyaan') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <h3 class="card-title"><strong> Pertanyaan Layanan </strong></h3>
    </div>
    <div class="card-body">

        <form action="{{ url('pertanyaan/storeLayanan') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="kategori" value="{{ $kategori }}"> <!-- Hidden field untuk kategori -->
            <input type="hidden" name="status" value="Diproses"> <!-- Hidden status otomatis -->

            <div class="form-group">
                <label for="status_pemohon">Status Pemohon</label>
                <select class="form-control" id="status_pemohon" name="status_pemohon" required>
                    <option value="perorangan">Perorangan</option>
                    <option value="organisasi">Organisasi</option>
                </select>
            </div>

            <div class="form-group">
                <label for="jumlah_pertanyaan">Jumlah Pertanyaan</label>
                <input type="number" class="form-control" id="jumlah_pertanyaan" name="jumlah_pertanyaan" min="1" required>
            </div>

            <div id="pertanyaan-container">
                <!-- Input pertanyaan akan di-generate di sini -->
            </div>

            <button type="submit" class="btn btn-primary">Ajukan Pertanyaan</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('jumlah_pertanyaan').addEventListener('input', function () {
        const jumlah = parseInt(this.value) || 0;
        const container = document.getElementById('pertanyaan-container');

        container.innerHTML = ''; // Reset container

        for (let i = 1; i <= jumlah; i++) {
            const div = document.createElement('div');
            div.className = 'form-group';

            const label = document.createElement('label');
            label.innerText = `Pertanyaan ${i}`;

            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'form-control';
            input.name = `pertanyaan[]`;
            input.required = true;

            div.appendChild(label);
            div.appendChild(input);
            container.appendChild(div);
        }
    });
</script>
@endsection
