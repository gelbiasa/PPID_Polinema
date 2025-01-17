@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <a href="{{ url('hasilPermohonan') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <h3 class="card-title"><strong> Hasil Permohonan Layanan </strong></h3>
    </div>
    <div class="card-body">
        @foreach($permohonan as $item)
            <div class="custom-container" data-id="{{ $item->id }}" data-status="{{ $item->status }}">
                <div class="info">
                    <p>
                        <strong>Nama:</strong> {{ $item->m_user->nama }} |
                        <strong>Nomor Handphone:</strong> {{ $item->m_user->no_hp }} |
                        <strong>Email:</strong> {{ $item->m_user->email }}
                    </p>
                    <p class="pemohon-info">
                        <strong>Status pemohon:</strong> {{ $item->status_pemohon }} <br>
                        <strong>Judul pemohon:</strong> {{ $item->judul_pemohon }}
                    </p>
                </div>
                <button class="btn btn-primary badge-button toggle-detail">Lihat Detail</button>
            </div>
            <!-- Container Detail -->
            <div class="detail-container" style="display: none;">
                <div class="detail-content">
                    <p><strong>Deskripsi:</strong> {{ $item->deskripsi }}</p>
                    <p><strong>Jawaban:</strong></p>
                    <a href="{{ asset('storage/' . $item->jawaban) }}" target="_blank" class="btn btn-info">Download
                        Dokumen</a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-detail').forEach(button => {
        button.addEventListener('click', function () {
            const detailContainer = this.closest('.custom-container').nextElementSibling;
            if (detailContainer.style.display === 'none') {
                detailContainer.style.display = 'block';
            } else {
                detailContainer.style.display = 'none';
            }
        });
    });
</script>

<style>
    .custom-container {
        background-color: lightgreen;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
        color: black;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .info {
        flex: 1;
        margin-right: 20px;
    }

    .pemohon-info {
        margin-bottom: 0;
    }

    .badge-button {
        background-color: blue;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 15px;
        font-size: 14px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
    }

    .badge-button:hover {
        background-color: darkblue;
    }

    .detail-container {
        background-color: white;
        border: 1px solid black;
        border-radius: 10px;
        margin-top: 15px;
        margin-bottom: 15px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .detail-content p {
        margin: 0;
        font-size: 14px;
        font-family: Arial, sans-serif;
    }
</style>

@endsection