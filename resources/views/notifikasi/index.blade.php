@extends('layouts.template')

@section('content')

<?php
use App\Models\NotifikasiVerifikatorModel;

// Hitung jumlah notifikasi untuk kategori 'permohonan'
$jumlahNotifikasiPermohonan = NotifikasiVerifikatorModel::where('kategori', 'permohonan')
    ->whereNull('sudah_dibaca')
    ->whereNull('deleted_at')
    ->count();

// Hitung jumlah notifikasi untuk kategori 'pertanyaan'
$jumlahNotifikasiPertanyaan = NotifikasiVerifikatorModel::where('kategori', 'pertanyaan')
    ->whereNull('sudah_dibaca')
    ->whereNull('deleted_at')
    ->count();
?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Notifikasi Pengajuan Permohonan dan Pertanyaan</h3>
    </div>
    <div class="card-body" style="padding-top: 10px;">
        <div class="row text-center">
            <!-- Permohonan -->
            <div class="col-md-6">
                <a href="{{ url('notifikasi/notif_permohonan') }}" class="custom-button d-block p-3 mb-2 position-relative">
                    <i class="fas fa-file-alt fa-2x"></i>
                    <h5>Pengajuan Permohonan Informasi</h5>
                    @if($jumlahNotifikasiPermohonan > 0)
                        <span class="badge badge-danger notification-badge-menu">{{ $jumlahNotifikasiPermohonan }}</span>
                    @endif
                </a>
            </div>            
            <!-- Pertanyaan -->
            <div class="col-md-6">
                <a href="{{ url('notifikasi/notif_pertanyaan') }}" class="custom-button d-block p-3 mb-2 position-relative">
                    <i class="fas fa-comments fa-2x"></i>
                    <h5>Pengajuan Pertanyaan</h5>
                    @if($jumlahNotifikasiPertanyaan > 0)
                        <span class="badge badge-danger notification-badge-menu">{{ $jumlahNotifikasiPertanyaan }}</span>
                    @endif
                </a>
            </div>
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