@extends('layouts.template')

@section('content')

<?php
use App\Models\PertanyaanModel;

$jumlahDaftarAkademik = PertanyaanModel::where('kategori', 'Akademik')
    ->where('status', 'Diproses')
    ->count();

$jumlahDaftarLayanan = PertanyaanModel::where('kategori', 'Layanan')
    ->where('status', 'Diproses')
    ->count();

$jumlahDaftarTeknis = PertanyaanModel::where('kategori', 'Teknis')
    ->where('status', 'Diproses')
    ->count();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengajuan Pertanyaaan</h3>
    </div>   
    <div class="card-body" style="padding-top: 10px;">
    <div class="row text-center">
        <!-- Akademis -->
        <div class="col-md-4">
            <a href="{{ url('daftarPertanyaan/daftarAkademik') }}" class="custom-button d-block p-3 mb-2">
                <i class="fas fa-graduation-cap fa-2x"></i>
                <h5>Akademis</h5>
                @if($jumlahDaftarAkademik > 0)
                    <span class="badge badge-danger notification-badge-menu">{{ $jumlahDaftarAkademik }}</span>
                @endif
            </a>
        </div>
        <!-- Layanan -->
        <div class="col-md-4">
            <a href="{{ url('daftarPertanyaan/daftarLayanan') }}" class="custom-button d-block p-3 mb-2">
                <i class="fas fa-concierge-bell fa-2x"></i>
                <h5>Layanan</h5>
                @if($jumlahDaftarLayanan > 0)
                    <span class="badge badge-danger notification-badge-menu">{{ $jumlahDaftarLayanan }}</span>
                @endif
            </a>
        </div>
        <!-- Teknis -->
        <div class="col-md-4">
            <a href="{{ url('daftarPertanyaan/daftarTeknis') }}" class="custom-button d-block p-3 mb-2">
                <i class="fas fa-tools fa-2x"></i>
                <h5>Teknis</h5>
                @if($jumlahDaftarTeknis > 0)
                    <span class="badge badge-danger notification-badge-menu">{{ $jumlahDaftarTeknis }}</span>
                @endif
            </a>
        </div>
    </div>
</div>

<style>
    .custom-button {
        background-color: lightblue;
        border: 2px solid black;
        border-radius: 8px; 
        color: black; 
        text-decoration: none; 
        transition: background-color 0.3s, transform 0.3s; 
        position: relative; /* Untuk badge absolut dalam elemen ini */
    }

    .custom-button:hover {
        background-color: blue; 
        transform: scale(0.95);
        color: white; /* Warna ikon saat hover */
    }

    .notification-badge-menu {
        position: absolute;
        top: 5px; /* Atur posisi vertikal */
        right: 5px; /* Atur posisi horizontal */
        background-color: #dc3545; /* Warna merah */
        color: white; /* Warna teks */
        padding: 3px 8px; /* Spasi dalam */
        border-radius: 50%; /* Membulatkan badge */
        font-size: 20px; /* Ukuran font */
        font-weight: bold; /* Tebal */
    }
</style>

@endsection