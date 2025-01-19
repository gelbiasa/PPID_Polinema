<?php

namespace App\Http\Controllers;

use App\Models\PertanyaanLanjutModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasilPertanyaanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Hasil Pertanyaan',
            'list' => ['Home', 'Hasil Pertanyaan']
        ];

        $page = (object) [
            'title' => 'Hasil Pertanyaan'
        ];

        $activeMenu = 'hasil_pertanyaan'; // Set the active menu

        return view('hasilPertanyaan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function daftarAkademik()
    {
        $breadcrumb = (object) [
            'title' => 'Hasil Pertanyaan',
            'list' => ['Home', 'Hasil Pertanyaan']
        ];

        $page = (object) [
            'title' => 'Hasil Pertanyaan'
        ];

        $activeMenu = 'hasil_pertanyaan';

        // Ambil data pertanyaan beserta detailnya
        $pertanyaan = PertanyaanLanjutModel::where('kategori', 'Akademik')
            ->where('status', 'Disetujui')
            ->where('user_id', Auth::id())
            ->with(['m_user', 't_pertanyaan_detail_lanjut']) // Relasi detail pertanyaan
            ->get();

        return view('hasilPertanyaan.daftarAkademik', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'pertanyaan' => $pertanyaan,
        ]);
    }

    public function daftarlayanan()
    {
        $breadcrumb = (object) [
            'title' => 'Hasil Pertanyaan',
            'list' => ['Home', 'Hasil Pertanyaan']
        ];

        $page = (object) [
            'title' => 'Hasil Pertanyaan'
        ];

        $activeMenu = 'hasil_pertanyaan';

        // Ambil data pertanyaan beserta detailnya
        $pertanyaan = PertanyaanLanjutModel::where('kategori', 'Layanan')
            ->where('status', 'Disetujui')
            ->where('user_id', Auth::id())
            ->with(['m_user', 't_pertanyaan_detail_lanjut']) // Relasi detail pertanyaan
            ->get();

        return view('hasilPertanyaan.daftarLayanan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'pertanyaan' => $pertanyaan,
        ]);
    }

    public function daftarTeknis()
    {
        $breadcrumb = (object) [
            'title' => 'Hasil Pertanyaan',
            'list' => ['Home', 'Hasil Pertanyaan']
        ];

        $page = (object) [
            'title' => 'Hasil Pertanyaan'
        ];

        $activeMenu = 'hasil_pertanyaan';

        // Ambil data pertanyaan beserta detailnya
        $pertanyaan = PertanyaanLanjutModel::where('kategori', 'Teknis')
            ->where('status', 'Disetujui')
            ->where('user_id', Auth::id())
            ->with(['m_user', 't_pertanyaan_detail_lanjut']) // Relasi detail pertanyaan
            ->get();

        return view('hasilPertanyaan.daftarteknis', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'pertanyaan' => $pertanyaan,
        ]);
    }

    public function tandaiDibaca($id)
    {
        $notifikasi = PertanyaanLanjutModel::findOrFail($id);
        $notifikasi->sudah_dibaca = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil ditandai telah dibaca']);
    }
}
