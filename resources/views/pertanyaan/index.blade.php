@extends('layouts.template')

@section('content')

<!-- Flash Message -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Prosedur Pengajuan Pertanyaan</h3>
    </div>
    <div class="card-body" style="margin-bottom: 0; padding-bottom: 0;">
        <p>Ikuti langkah-langkah berikut untuk mengajukan permohonan:</p>
        <ol style="margin-bottom: 0;">
            <li>Pilih Kategori Pertanyaan yang ingin ditanyakan</li>
            <li>Isi Form Pengajuan Pertanyaan</li>
            <li>Kirim Pengajuan Pertanyaan</li>
            <li>Tunggu Proses Pengajuan Pertanyaan</li>
            <li>Dapatkan Hasil Pengajuan Pertanyaan</li>
        </ol>
    </div>
    <div class="card-body" style="padding-top: 10px;">
        <hr class="thick-line">
        <h4><strong>Kategori Pertanyaan:</strong></h4>
        <hr class="thick-line">
        <div class="row text-center">
            <!-- Akademis -->
            <div class="col-md-4">
                <a href="{{ url('pertanyaan/formAkademik') }}" class="custom-button d-block p-3 mb-2">
                    <i class="fas fa-graduation-cap fa-2x"></i>
                    <h5>Akademis</h5>
                </a>
                <div class="custom-container p-3">
                    <p>Pengajuan terkait akademik, seperti tugas akhir, beasiswa, dan lainnya.</p>
                </div>
            </div>
            <!-- Layanan -->
            <div class="col-md-4">
                <a href="{{ url('pertanyaan/formLayanan') }}" class="custom-button d-block p-3 mb-2">
                    <i class="fas fa-concierge-bell fa-2x"></i>
                    <h5>Layanan</h5>
                </a>
                <div class="custom-container p-3">
                    <p>Pengajuan layanan administratif, perizinan, atau fasilitas umum.</p>
                </div>
            </div>
            <!-- Teknis -->
            <div class="col-md-4">
                <a href="{{ url('pertanyaan/formTeknis') }}" class="custom-button d-block p-3 mb-2">
                    <i class="fas fa-tools fa-2x"></i>
                    <h5>Teknis</h5>
                </a>
                <div class="custom-container p-3">
                    <p>Pengajuan bantuan teknis terkait sistem, jaringan, atau perangkat keras.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .thick-line {
        border: none;
        height: 1px; 
        background-color: black;
    }

    .custom-button {
        background-color: #a59c9c;
        border: 2px solid black;
        border-radius: 8px; 
        color: black; 
        text-decoration: none; 
        transition: background-color 0.3s, transform 0.3s; 
    }

    .custom-button:hover {
        background-color: #8e8585; 
        transform: scale(0.95);
        color: white; /* Warna ikon saat hover */
    }

    .custom-container {
        background-color: #ffffff; 
        border: 2px solid black;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }
</style>

@endsection
