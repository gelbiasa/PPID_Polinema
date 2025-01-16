<?php

namespace App\Http\Controllers;

use App\Mail\PermohonanNotificationMail;
use App\Models\NotifikasiMPUModel;
use App\Models\PermohonanLanjutModel;
use App\Models\PermohonanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

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

    public function tolakPermohonanAkademik(Request $request, $id)
    {
        $permohonan = PermohonanModel::with('m_user')->findOrFail($id); // Include m_user relation
        $user = $permohonan->m_user; // Mendapatkan data pengguna terkait

        $permohonan->status = 'Ditolak';
        $permohonan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $permohonan->updated_at = Carbon::now();
        $permohonan->save();

        // Kirim email
        Mail::to($user->email)->send(new PermohonanNotificationMail($user->nama,
         'Ditolak', $permohonan->kategori, $permohonan->status_pemohon, $request->reason));

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil ditolak.',
        ]);
    }

    public function setujuiPermohonanAkademik($id)
    {
        $permohonan = PermohonanModel::with('m_user')->findOrFail($id); 

        $permohonan->status = 'Disetujui';
        $permohonan->updated_at = Carbon::now();
        $permohonan->save();

        PermohonanLanjutModel::create([
            'permohonan_lanjut_id' => $permohonan->permohonan_id,
            'user_id' => $permohonan->user_id,
            'kategori' => $permohonan->kategori,
            'status_pemohon' => $permohonan->status_pemohon,
            'judul_pemohon' => $permohonan->judul_pemohon,
            'deskripsi' => $permohonan->deskripsi,
            'dokumen_pendukung' => $permohonan->dokumen_pendukung,
            'status' => 'Diproses', // Tetap 'Diproses' di t_permohonan_lanjut
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan notifikasi ke database
        NotifikasiMPUModel::create([
            'user_id' => $permohonan->user_id,
            'kategori' => 'permohonan',
            'permohonan_lanjut_id' => $permohonan->permohonan_id,
            'pertanyaan_lanjut_id' => null,
            'pesan' => auth()->user()->nama . ' Mengajukan Permohonan ' . $permohonan->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil disetujui.',
        ]);
    }
    public function hapusPermohonanAKademik($id)
    {
        $notifikasi = PermohonanModel::findOrFail($id);
        $notifikasi->deleted_at = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Permohonan berhasil dihapus']);
    }

    public function daftarLayanan()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Permohonan',
            'list' => ['Home', 'Daftar Permohonan']
        ];

        $page = (object) [
            'title' => 'Daftar Permohonan'
        ];

        $activeMenu = 'daftar_permohonan'; // Set the active menu

        // Ambil data permohonan dengan kategori Layanan
        $permohonan = PermohonanModel::where('kategori', 'Layanan')
            ->with('m_user') // Pastikan relasi ke User telah didefinisikan
            ->get();

        return view('daftarPermohonan.daftarLayanan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'permohonan' => $permohonan // Kirim data ke view
        ]);
    }

    public function tolakPermohonanLayanan(Request $request, $id)
    {
        $permohonan = PermohonanModel::with('m_user')->findOrFail($id); // Include m_user relation
        $user = $permohonan->m_user; // Mendapatkan data pengguna terkait

        $permohonan->status = 'Ditolak';
        $permohonan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $permohonan->updated_at = Carbon::now();
        $permohonan->save();

        // Kirim email
        Mail::to($user->email)->send(new PermohonanNotificationMail($user->nama,
         'Ditolak', $permohonan->kategori, $permohonan->status_pemohon, $request->reason));

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil ditolak.',
        ]);
    }

    public function setujuiPermohonanLayanan($id)
    {
        $permohonan = PermohonanModel::with('m_user')->findOrFail($id); 

        $permohonan->status = 'Disetujui';
        $permohonan->updated_at = Carbon::now();
        $permohonan->save();

        PermohonanLanjutModel::create([
            'permohonan_lanjut_id' => $permohonan->permohonan_id,
            'user_id' => $permohonan->user_id,
            'kategori' => $permohonan->kategori,
            'status_pemohon' => $permohonan->status_pemohon,
            'judul_pemohon' => $permohonan->judul_pemohon,
            'deskripsi' => $permohonan->deskripsi,
            'dokumen_pendukung' => $permohonan->dokumen_pendukung,
            'status' => 'Diproses', // Tetap 'Diproses' di t_permohonan_lanjut
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan notifikasi ke database
        NotifikasiMPUModel::create([
            'user_id' => $permohonan->user_id,
            'kategori' => 'permohonan',
            'permohonan_lanjut_id' => $permohonan->permohonan_id,
            'pertanyaan_lanjut_id' => null,
            'pesan' => auth()->user()->nama . ' Mengajukan Permohonan ' . $permohonan->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil disetujui.',
        ]);
    }
    public function hapusPermohonanLayanan($id)
    {
        $notifikasi = PermohonanModel::findOrFail($id);
        $notifikasi->deleted_at = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Permohonan berhasil dihapus']);
    }

    public function daftarTeknis()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Permohonan',
            'list' => ['Home', 'Daftar Permohonan']
        ];

        $page = (object) [
            'title' => 'Daftar Permohonan'
        ];

        $activeMenu = 'daftar_permohonan'; // Set the active menu

        // Ambil data permohonan dengan kategori Teknis
        $permohonan = PermohonanModel::where('kategori', 'Teknis')
            ->with('m_user') // Pastikan relasi ke User telah didefinisikan
            ->get();

        return view('daftarPermohonan.daftarTeknis', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'permohonan' => $permohonan // Kirim data ke view
        ]);
    }

    public function tolakPermohonanTeknis(Request $request, $id)
    {
        $permohonan = PermohonanModel::with('m_user')->findOrFail($id); // Include m_user relation
        $user = $permohonan->m_user; // Mendapatkan data pengguna terkait

        $permohonan->status = 'Ditolak';
        $permohonan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $permohonan->updated_at = Carbon::now();
        $permohonan->save();

        // Kirim email
        Mail::to($user->email)->send(new PermohonanNotificationMail($user->nama,
         'Ditolak', $permohonan->kategori, $permohonan->status_pemohon, $request->reason));

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil ditolak.',
        ]);
    }

    public function setujuiPermohonanTeknis($id)
    {
        $permohonan = PermohonanModel::with('m_user')->findOrFail($id); 

        $permohonan->status = 'Disetujui';
        $permohonan->updated_at = Carbon::now();
        $permohonan->save();

        PermohonanLanjutModel::create([
            'permohonan_lanjut_id' => $permohonan->permohonan_id,
            'user_id' => $permohonan->user_id,
            'kategori' => $permohonan->kategori,
            'status_pemohon' => $permohonan->status_pemohon,
            'judul_pemohon' => $permohonan->judul_pemohon,
            'deskripsi' => $permohonan->deskripsi,
            'dokumen_pendukung' => $permohonan->dokumen_pendukung,
            'status' => 'Diproses', // Tetap 'Diproses' di t_permohonan_lanjut
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan notifikasi ke database
        NotifikasiMPUModel::create([
            'user_id' => $permohonan->user_id,
            'kategori' => 'permohonan',
            'permohonan_lanjut_id' => $permohonan->permohonan_id,
            'pertanyaan_lanjut_id' => null,
            'pesan' => auth()->user()->nama . ' Mengajukan Permohonan ' . $permohonan->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil disetujui.',
        ]);
    }
    public function hapusPermohonanTeknis($id)
    {
        $notifikasi = PermohonanModel::findOrFail($id);
        $notifikasi->deleted_at = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Permohonan berhasil dihapus']);
    }
}
