<?php

namespace App\Http\Controllers;

use App\Models\DetailPertanyaanModel;
use App\Models\NotifikasiAdminModel;
use App\Models\NotifikasiVerifikatorModel;
use App\Models\PertanyaanModel;
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Pertanyaan',
            'list' => ['Home', 'Pertanyaan']
        ];

        $page = (object) [
            'title' => 'Pengajuan Pertanyaan'
        ];

        $activeMenu = 'pertanyaan'; // Set the active menu

        return view('pertanyaan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function formAkademik()
    {
        $breadcrumb = (object) [
            'title' => 'Pertanyaan Akademik',
            'list' => ['Home', 'Pertanyaan Akademik', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Pengajuan Pertanyaan Akademik'
        ];

        $activeMenu = 'pertanyaan';

        return view('pertanyaan.formAkademik', [
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
            'jumlah_pertanyaan' => 'required|integer|min:1',
            'pertanyaan.*' => 'required|string'
        ]);

        // Simpan data ke table `t_pertanyaan`
        $pertanyaan = PertanyaanModel::create([
            'user_id' => auth()->id(),
            'kategori' => $request->kategori,
            'status_pemohon' => $request->status_pemohon,
            'status' => 'Diproses',
            'alasan_penolakan' => null,
            'deleted_at' => null,
        ]);

        // Simpan data ke table `t_pertanyaan_detail`
        foreach ($request->pertanyaan as $pertanyaanText) {
            DetailPertanyaanModel::create([
                'pertanyaan_id' => $pertanyaan->pertanyaan_id,
                'pertanyaan' => $pertanyaanText,
                'jawaban' => null,
            ]);
        }

        // Simpan notifikasi ke database
        NotifikasiVerifikatorModel::create([
            'user_id' => auth()->id(),
            'kategori' => 'pertanyaan',
            'permohonan_id' => null,
            'pertanyaan_id' => $pertanyaan->pertanyaan_id,
            'pesan' => auth()->user()->nama . ' Mengajukan pertanyaan ' . $request->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        NotifikasiAdminModel::create([
            'user_id' => auth()->id(),
            'kategori' => 'pertanyaan',
            'permohonan_id' => null,
            'pertanyaan_id' => $pertanyaan->pertanyaan_id,
            'pesan' => auth()->user()->nama . ' Mengajukan pertanyaan ' . $request->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        return redirect('/pertanyaan')->with('success', 'Pertanyaan Akademik berhasil diajukan.');
    }

    // Form Layanan
    public function formLayanan()
    {
        $breadcrumb = (object) [
            'title' => 'Pertanyaan Layanan',
            'list' => ['Home', 'Pertanyaan Layanan', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Pengajuan Pertanyaan Layanan'
        ];

        $activeMenu = 'pertanyaan';

        return view('pertanyaan.formLayanan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => 'Layanan' // Menyediakan kategori default
        ]);
    }

    public function storeFormLayanan(Request $request)
    {
        $request->validate([
            'status_pemohon' => 'required|string',
            'jumlah_pertanyaan' => 'required|integer|min:1',
            'pertanyaan.*' => 'required|string'
        ]);

        // Simpan data ke table `t_pertanyaan`
        $pertanyaan = PertanyaanModel::create([
            'user_id' => auth()->id(),
            'kategori' => $request->kategori,
            'status_pemohon' => $request->status_pemohon,
            'status' => 'Diproses',
            'alasan_penolakan' => null,
            'deleted_at' => null,
        ]);

        // Simpan data ke table `t_pertanyaan_detail`
        foreach ($request->pertanyaan as $pertanyaanText) {
            DetailPertanyaanModel::create([
                'pertanyaan_id' => $pertanyaan->pertanyaan_id,
                'pertanyaan' => $pertanyaanText,
                'jawaban' => null,
            ]);
        }

        // Simpan notifikasi ke database
        NotifikasiVerifikatorModel::create([
            'user_id' => auth()->id(),
            'kategori' => 'pertanyaan',
            'permohonan_id' => null,
            'pertanyaan_id' => $pertanyaan->pertanyaan_id,
            'pesan' => auth()->user()->nama . ' Mengajukan pertanyaan ' . $request->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        NotifikasiAdminModel::create([
            'user_id' => auth()->id(),
            'kategori' => 'pertanyaan',
            'permohonan_id' => null,
            'pertanyaan_id' => $pertanyaan->pertanyaan_id,
            'pesan' => auth()->user()->nama . ' Mengajukan pertanyaan ' . $request->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        return redirect('/pertanyaan')->with('success', 'Pertanyaan Layanan berhasil diajukan.');
    }

    // Form Teknis
    public function formTeknis()
    {
        $breadcrumb = (object) [
            'title' => 'Pertanyaan Teknis',
            'list' => ['Home', 'Pertanyaan Teknis', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Pengajuan Pertanyaan Teknis'
        ];

        $activeMenu = 'pertanyaan';

        return view('pertanyaan.formTeknis', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => 'Teknis' // Menyediakan kategori default
        ]);
    }

    public function storeFormTeknis(Request $request)
    {
        $request->validate([
            'status_pemohon' => 'required|string',
            'jumlah_pertanyaan' => 'required|integer|min:1',
            'pertanyaan.*' => 'required|string'
        ]);

        // Simpan data ke table `t_pertanyaan`
        $pertanyaan = PertanyaanModel::create([
            'user_id' => auth()->id(),
            'kategori' => $request->kategori,
            'status_pemohon' => $request->status_pemohon,
            'status' => 'Diproses',
            'alasan_penolakan' => null,
            'deleted_at' => null,
        ]);

        // Simpan data ke table `t_pertanyaan_detail`
        foreach ($request->pertanyaan as $pertanyaanText) {
            DetailPertanyaanModel::create([
                'pertanyaan_id' => $pertanyaan->pertanyaan_id,
                'pertanyaan' => $pertanyaanText,
                'jawaban' => null,
            ]);
        }

        // Simpan notifikasi ke database
        NotifikasiVerifikatorModel::create([
            'user_id' => auth()->id(),
            'kategori' => 'pertanyaan',
            'permohonan_id' => null,
            'pertanyaan_id' => $pertanyaan->pertanyaan_id,
            'pesan' => auth()->user()->nama . ' Mengajukan pertanyaan ' . $request->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        NotifikasiAdminModel::create([
            'user_id' => auth()->id(),
            'kategori' => 'pertanyaan',
            'permohonan_id' => null,
            'pertanyaan_id' => $pertanyaan->pertanyaan_id,
            'pesan' => auth()->user()->nama . ' Mengajukan pertanyaan ' . $request->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        return redirect('/pertanyaan')->with('success', 'Pertanyaan Teknis berhasil diajukan.');
    }
}
