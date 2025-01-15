<?php

namespace App\Http\Controllers;

use App\Models\NotifikasiVerifikatorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotifikasiControllerVFR extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Notifikasi',
            'list' => ['Home', 'Notifikasi']
        ];

        $page = (object) [
            'title' => 'Notifikasi Pengajuan Permohonan dan Pertanyaan'
        ];

        $activeMenu = 'notifikasi'; // Set the active menu

        return view('notifikasi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function notifikasiPermohonan()
    {
        $notifikasi = NotifikasiVerifikatorModel::with('t_permohonan')
            ->where('kategori', 'permohonan')
            ->whereNull('deleted_at')
            ->get();

        $breadcrumb = (object) [
            'title' => 'Notifikasi',
            'list' => ['Home', 'Notifikasi']
        ];

        $page = (object) [
            'title' => 'Notifikasi Pengajuan Permohonan'
        ];

        $activeMenu = 'notifikasi'; // Set the active menu

        return view('notifikasi.notif_permohonan', [
            'notifikasi' => $notifikasi,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function notifikasiPertanyaan()
    {
        $notifikasi = NotifikasiVerifikatorModel::with('t_pertanyaan')
            ->where('kategori', 'pertanyaan')
            ->whereNull('deleted_at')
            ->get();

        $breadcrumb = (object) [
            'title' => 'Notifikasi',
            'list' => ['Home', 'Notifikasi']
        ];

        $page = (object) [
            'title' => 'Notifikasi Pertanyaan'
        ];

        $activeMenu = 'notifikasi'; // Set the active menu

        return view('notifikasi.notif_pertanyaan', [
            'notifikasi' => $notifikasi,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function tandaiDibaca($id)
    {
        $notifikasi = NotifikasiVerifikatorModel::findOrFail($id);
        $notifikasi->sudah_dibaca = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil ditandai telah dibaca']);
    }

    public function hapusNotifikasi($id)
    {
        $notifikasi = NotifikasiVerifikatorModel::findOrFail($id);
        $notifikasi->deleted_at = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil dihapus']);
    }
}
