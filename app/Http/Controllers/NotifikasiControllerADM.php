<?php

namespace App\Http\Controllers;

use App\Models\NotifikasiAdminModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class NotifikasiControllerADM extends Controller
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

        return view('notifAdmin.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function notifikasiPermohonan()
    {
        $notifikasi = NotifikasiAdminModel::with('t_permohonan')
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

        return view('notifAdmin.notif_permohonan', [
            'notifikasi' => $notifikasi,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function notifikasiPertanyaan()
    {
        $notifikasi = NotifikasiAdminModel::with('t_pertanyaan')
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

        return view('notifAdmin.notif_pertanyaan', [
            'notifikasi' => $notifikasi,
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function tandaiDibaca($id)
    {
        $notifikasi = NotifikasiAdminModel::findOrFail($id);
        $notifikasi->sudah_dibaca = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil ditandai telah dibaca']);
    }

    public function hapusNotifikasi($id)
    {
        $notifikasi = NotifikasiAdminModel::findOrFail($id);
        $notifikasi->deleted_at = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Notifikasi berhasil dihapus']);
    }
}
