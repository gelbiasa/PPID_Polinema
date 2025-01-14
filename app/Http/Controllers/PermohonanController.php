<?php

namespace App\Http\Controllers;

use App\Models\PermohonanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermohonanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Permohonan',
            'list' => ['Home', 'Permohonan']
        ];

        $page = (object) [
            'title' => 'Pengajuan Permohonan'
        ];

        $activeMenu = 'permohonan'; // Set the active menu

        return view('permohonan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function formAkademik()
    {
        $breadcrumb = (object) [
            'title' => 'Permohonan Akademik',
            'list' => ['Home', 'Permohonan Akademik', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Pengajuan Permohonan Akademik'
        ];

        $activeMenu = 'permohonan';

        return view('permohonan.formAkademik', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => 'Akademik' // Menyediakan kategori default
        ]);
    }

    public function storeFormAkademik(Request $request)
    {
        $request->validate([
            'status_pemohon' => 'required|string',
            'judul_pemohon' => 'required|string',
            'deskripsi' => 'required|string',
            'dokumen_pendukung' => 'required|file|mimes:pdf,doc,docx,xlsx',
        ]);
        // Simpan file dokumen pendukung
        $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen_pendukung', 'public');

        // Simpan data permohonan ke database
        PermohonanModel::create([
            'user_id' => auth()->id(),
            'kategori' => $request->kategori,
            'status_pemohon' => $request->status_pemohon,
            'judul_pemohon' => $request->judul_pemohon,
            'deskripsi' => $request->deskripsi,
            'dokumen_pendukung' => $dokumenPath,
            'status' => 'Diproses',
            'alasan_penolakan' => null,
            'deleted_at' => null,
        ]);  

        return redirect('/permohonan')->with('success', 'Permohonan Akademik berhasil diajukan.');
    }



    // Form Layanan
    public function formLayanan()
    {
        $breadcrumb = (object) [
            'title' => 'Permohonan Layanan',
            'list' => ['Home', 'Permohonan Layanan', 'Kembalih']
        ];

        $page = (object) [
            'title' => 'Pengajuan Permohonan Layanan'
        ];

        $activeMenu = 'permohonan';

        return view('permohonan.formLayanan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => 'Layanan'
        ]);
    }

    public function storeFormLayanan(Request $request)
    {
        $request->validate([
            'status_pemohon' => 'required|string',
            'judul_pemohon' => 'required|string',
            'deskripsi' => 'required|string',
            'dokumen_pendukung' => 'required|file|mimes:pdf,doc,docx,xlsx',
        ]);

        // Simpan file dokumen pendukung
        $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen_pendukung', 'public');

        PermohonanModel::create([
            'user_id' => auth()->id(), // Mengambil user_id dari user yang sedang login
            'kategori' => $request->kategori,
            'status_pemohon' => $request->status_pemohon,
            'judul_pemohon' => $request->judul_pemohon,
            'deskripsi' => $request->deskripsi,
            'dokumen_pendukung' => $dokumenPath,
            'status' => 'Diproses',
            'alasan_penolakan' => null,
            'deleted_at' => null,
        ]);
        // Proses penyimpanan data Layanan
        return redirect('/permohonan')->with('success', 'Permohonan Layanan berhasil diajukan.');
    }

    // Form Teknis
    public function formTeknis()
    {
        $breadcrumb = (object) [
            'title' => 'Permohonan Teknis',
            'list' => ['Home', 'Permohonan Teknis', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Pengajuan Permohonan Teknis'
        ];

        $activeMenu = 'permohonan';

        return view('permohonan.formTeknis', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => 'Teknis'
        ]);
    }

    public function storeFormTeknis(Request $request)
    {
        $request->validate([
            'status_pemohon' => 'required|string',
            'judul_pemohon' => 'required|string',
            'deskripsi' => 'required|string',
            'dokumen_pendukung' => 'required|file|mimes:pdf,doc,docx,xlsx',
        ]);

        // Simpan file dokumen pendukung
        $dokumenPath = $request->file('dokumen_pendukung')->store('dokumen_pendukung', 'public');

        PermohonanModel::create([
            'user_id' => auth()->id(), // Mengambil user_id dari user yang sedang login
            'kategori' => $request->kategori,
            'status_pemohon' => $request->status_pemohon,
            'judul_pemohon' => $request->judul_pemohon,
            'deskripsi' => $request->deskripsi,
            'dokumen_pendukung' => $dokumenPath,
            'status' => 'Diproses',
            'alasan_penolakan' => null,
            'deleted_at' => null,
        ]);
        // Proses penyimpanan data Teknis
        return redirect('/permohonan')->with('success', 'Permohonan Teknis berhasil diajukan.');
    }
}
