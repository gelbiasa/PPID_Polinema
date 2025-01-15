@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pengajuan Permohonan</h3>
    </div>   
    <div class="card-body" style="padding-top: 10px;">
    <div class="row text-center">
        <!-- Akademis -->
        <div class="col-md-4">
            <a href="{{ url('daftarPermohonan/daftarAkademik') }}" class="custom-button d-block p-3 mb-2">
                <i class="fas fa-graduation-cap fa-2x"></i>
                <h5>Akademis</h5>
            </a>
        </div>
        <!-- Layanan -->
        <div class="col-md-4">
            <a href="{{ url('daftarPermohonan/formLayanan') }}" class="custom-button d-block p-3 mb-2">
                <i class="fas fa-concierge-bell fa-2x"></i>
                <h5>Layanan</h5>
            </a>
        </div>
        <!-- Teknis -->
        <div class="col-md-4">
            <a href="{{ url('daftarPermohonan/formTeknis') }}" class="custom-button d-block p-3 mb-2">
                <i class="fas fa-tools fa-2x"></i>
                <h5>Teknis</h5>
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
</style>

@endsection