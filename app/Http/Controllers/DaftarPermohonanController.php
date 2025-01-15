<?php

namespace App\Http\Controllers;

use App\Models\PermohonanModel;
use Illuminate\Http\Request;

class DaftarPermohonanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dftar Permohonan',
            'list' => ['Home', 'Daftar Permohonan']
        ];

        $page = (object) [
            'title' => 'Daftar Permohonan'
        ];

        $activeMenu = 'daftar_permohonan'; // Set the active menu

        return view('daftarPermohonan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function daftarAkademik()
{
    $breadcrumb = (object) [
        'title' => 'Daftar Permohonan',
        'list' => ['Home', 'Daftar Permohonan']
    ];

    $page = (object) [
        'title' => 'Daftar Permohonan'
    ];

    $activeMenu = 'daftar_permohonan'; // Set the active menu

    // Ambil data permohonan dengan kategori Akademik
    $permohonan = PermohonanModel::where('kategori', 'Akademik')
        ->with('m_user') // Pastikan relasi ke User telah didefinisikan
        ->get();

    return view('daftarPermohonan.daftarAkademik', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'activeMenu' => $activeMenu,
        'permohonan' => $permohonan // Kirim data ke view
    ]);
}
}
