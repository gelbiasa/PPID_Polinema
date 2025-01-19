<?php

namespace App\Http\Controllers;

use App\Models\PermohonanLanjutModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilPermohonanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Hasil Permohonan',
            'list' => ['Home', 'HasilPermohonan']
        ];

        $page = (object) [
            'title' => 'Hasil Permohonan'
        ];

        $activeMenu = 'hasil_permohonan'; // Set the active menu

        return view('hasilPermohonan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function daftarAkademik()
    {
        $breadcrumb = (object) [
            'title' => 'Hasil Permohonan',
            'list' => ['Home', 'Hasil Permohonan']
        ];

        $page = (object) [
            'title' => 'Hasil Permohonan'
        ];

        $activeMenu = 'hasil_permohonan';

        // Get only approved requests for the logged-in user
        $permohonan = PermohonanLanjutModel::where('kategori', 'Akademik')
            ->where('status', 'Disetujui')
            ->where('user_id', Auth::id()) // Add this line to filter by logged-in user
            ->with('m_user')
            ->get();

        return view('hasilPermohonan.daftarAkademik', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'permohonan' => $permohonan
        ]);
    }

    public function daftarLayanan()
    {
        $breadcrumb = (object) [
            'title' => 'Hasil Permohonan',
            'list' => ['Home', 'Hasil Permohonan']
        ];

        $page = (object) [
            'title' => 'Hasil Permohonan'
        ];

        $activeMenu = 'hasil_permohonan';

        // Get only approved requests for the logged-in user
        $permohonan = PermohonanLanjutModel::where('kategori', 'Layanan')
            ->where('status', 'Disetujui')
            ->where('user_id', Auth::id()) // Add this line to filter by logged-in user
            ->with('m_user')
            ->get();

        return view('hasilPermohonan.daftarLayanan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'permohonan' => $permohonan
        ]);
    }

    public function daftarTeknis()
    {
        $breadcrumb = (object) [
            'title' => 'Hasil Permohonan',
            'list' => ['Home', 'Hasil Permohonan']
        ];

        $page = (object) [
            'title' => 'Hasil Permohonan'
        ];

        $activeMenu = 'hasil_permohonan';

        // Get only approved requests for the logged-in user
        $permohonan = PermohonanLanjutModel::where('kategori', 'Teknis')
            ->where('status', 'Disetujui')
            ->where('user_id', Auth::id()) // Add this line to filter by logged-in user
            ->with('m_user')
            ->get();

        return view('hasilPermohonan.daftarTeknis', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'permohonan' => $permohonan
        ]);
    }

    public function tandaiDibaca($id)
    {
        $notifikasi = PermohonanLanjutModel::findOrFail($id);
        $notifikasi->sudah_dibaca = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil ditandai telah dibaca']);
    }
}
