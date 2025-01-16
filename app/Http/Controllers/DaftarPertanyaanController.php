<?php

namespace App\Http\Controllers;

use App\Mail\PertanyaanNotificationMail;
use App\Models\DetailPertanyaanLanjutModel;
use App\Models\DetailPertanyaanModel;
use App\Models\NotifikasiMPUModel;
use App\Models\PertanyaanLanjutModel;
use App\Models\PertanyaanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DaftarPertanyaanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Dftar Pertanyaan',
            'list' => ['Home', 'Daftar Pertanyaan']
        ];

        $page = (object) [
            'title' => 'Daftar Pertanyaan'
        ];

        $activeMenu = 'daftar_pertanyaan'; // Set the active menu

        return view('daftarPertanyaan.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function daftarAkademik()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pertanyaan',
            'list' => ['Home', 'Daftar Pertanyaan']
        ];

        $page = (object) [
            'title' => 'Daftar Pertanyaan'
        ];

        $activeMenu = 'daftar_pertanyaan';

        // Ambil data pertanyaan beserta detailnya
        $pertanyaan = PertanyaanModel::where('kategori', 'Akademik')
            ->with(['m_user', 't_pertanyaan_detail']) // Relasi detail pertanyaan
            ->get();

        return view('daftarPertanyaan.daftarAkademik', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'pertanyaan' => $pertanyaan,
        ]);
    }

    public function tolakPertanyaanAkademik(Request $request, $id)
    {
        $pertanyaan = PertanyaanModel::with('m_user')->findOrFail($id); // Include m_user relation
        $user = $pertanyaan->m_user; // Mendapatkan data pengguna terkait

        $pertanyaan->status = 'Ditolak';
        $pertanyaan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $pertanyaan->updated_at = Carbon::now();
        $pertanyaan->save();

        // Kirim email
        Mail::to($user->email)->send(new PertanyaanNotificationMail(
            $user->nama,
            'Ditolak',
            $pertanyaan->kategori,
            $pertanyaan->status_pemohon,
            $request->reason
        ));

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil ditolak.',
        ]);
    }

    public function setujuiPertanyaanAkademik($id)
    {
        try {
            // Cari Pertanyaan berdasarkan id
            $pertanyaan = PertanyaanModel::with('m_user')->findOrFail($id);

            // Cari Detail Pertanyaan berdasarkan pertanyaan_id
            $DetailPertanyaan = DetailPertanyaanModel::where('pertanyaan_id', $id)->firstOrFail();

            // Update status dan timestamp
            $pertanyaan->status = 'Disetujui';
            $pertanyaan->updated_at = now();
            $DetailPertanyaan->updated_at = now();

            $pertanyaan->save();
            $DetailPertanyaan->save();

            // Buat entri di PertanyaanLanjutModel dan simpan referensinya
            $pertanyaanLanjut = PertanyaanLanjutModel::create([
                'pertanyaan_lanjut_id' => $pertanyaan->pertanyaan_id,
                'user_id' => $pertanyaan->user_id,
                'kategori' => $pertanyaan->kategori,
                'status_pemohon' => $pertanyaan->status_pemohon,
                'status' => 'Diproses', // Tetap 'Diproses' di t_pertanyaan_lanjut
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Buat entri di DetailPertanyaanLanjutModel dengan referensi dari $pertanyaanLanjut
            DetailPertanyaanLanjutModel::create([
                'detail_pertanyaan_lanjut_id' => $DetailPertanyaan->detail_pertanyaan_id,
                'pertanyaan_lanjut_id' => $pertanyaanLanjut->pertanyaan_lanjut_id,
                'pertanyaan' => $DetailPertanyaan->pertanyaan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simpan notifikasi ke database
            NotifikasiMPUModel::create([
                'user_id' => $pertanyaan->user_id,
                'kategori' => 'Pertanyaan',
                'permohonan_lanjut_id' => null,
                'pertanyaan_lanjut_id' => $pertanyaan->pertanyaan_id,
                'pesan' => auth()->user()->nama . ' Mengajukan Pertanyaan ' . $pertanyaan->kategori,
                'sudah_dibaca' => null,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil disetujui.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menyetujui pertanyaan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui pertanyaan.',
            ], 500);
        }
    }

    public function hapusPertanyaanAKademik($id)
    {
        $notifikasi = PertanyaanModel::findOrFail($id);
        $notifikasi->deleted_at = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Pertanyaan berhasil dihapus']);
    }

    public function daftarLayanan()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pertanyaan',
            'list' => ['Home', 'Daftar Pertanyaan']
        ];

        $page = (object) [
            'title' => 'Daftar Pertanyaan'
        ];

        $activeMenu = 'daftar_pertanyaan'; // Set the active menu

        // Ambil data pertanyaan beserta detailnya
        $pertanyaan = PertanyaanModel::where('kategori', 'Layanan')
            ->with(['m_user', 't_pertanyaan_detail']) // Relasi detail pertanyaan
            ->get();

        return view('daftarPertanyaan.daftarLayanan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'pertanyaan' => $pertanyaan // Kirim data ke view
        ]);
    }

    public function tolakPertanyaanLayanan(Request $request, $id)
    {
        $pertanyaan = PertanyaanModel::with('m_user')->findOrFail($id); // Include m_user relation
        $user = $pertanyaan->m_user; // Mendapatkan data pengguna terkait

        $pertanyaan->status = 'Ditolak';
        $pertanyaan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $pertanyaan->updated_at = Carbon::now();
        $pertanyaan->save();

        // Kirim email
        Mail::to($user->email)->send(new PertanyaanNotificationMail(
            $user->nama,
            'Ditolak',
            $pertanyaan->kategori,
            $pertanyaan->status_pemohon,
            $request->reason
        ));

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil ditolak.',
        ]);
    }

    public function setujuiPertanyaanLayanan($id)
    {
        $pertanyaan = PertanyaanModel::with('m_user')->findOrFail($id);
        $DetailPertanyaan = DetailPertanyaanModel::with('pertanyaan_id')->findOrFail($id);

        $pertanyaan->status = 'Disetujui';
        $DetailPertanyaan->updated_at = Carbon::now();
        $pertanyaan->updated_at = Carbon::now();
        $pertanyaan->save();
        $DetailPertanyaan->save();

        PertanyaanLanjutModel::create([
            'pertanyaan_lanjut_id' => $pertanyaan->Pertanyaan_id,
            'user_id' => $pertanyaan->user_id,
            'kategori' => $pertanyaan->kategori,
            'status_pemohon' => $pertanyaan->status_pemohon,
            'judul_pemohon' => $pertanyaan->judul_pemohon,
            'deskripsi' => $pertanyaan->deskripsi,
            'dokumen_pendukung' => $pertanyaan->dokumen_pendukung,
            'status' => 'Diproses', // Tetap 'Diproses' di t_Pertanyaan_lanjut
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DetailPertanyaanLanjutModel::create([
            'detail_pertanyaan_lanjut_id' => $DetailPertanyaan->detail_pertanyaan_id,
            'pertanyaan_lanjut_id' => $pertanyaan->Pertanyaan_id,
            'pertanyaan' => $DetailPertanyaan->pertanyaan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan notifikasi ke database
        NotifikasiMPUModel::create([
            'user_id' => $pertanyaan->user_id,
            'kategori' => 'Pertanyaan',
            'Permohonan_lanjut_id' => null,
            'pertanyaan_lanjut_id' => $pertanyaan->Pertanyaan_id,
            'pesan' => auth()->user()->nama . ' Mengajukan Pertanyaan ' . $pertanyaan->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil disetujui.',
        ]);
    }
    public function hapusPertanyaanLayanan($id)
    {
        $notifikasi = PertanyaanModel::findOrFail($id);
        $notifikasi->deleted_at = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Pertanyaan berhasil dihapus']);
    }

    public function daftarTeknis()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pertanyaan',
            'list' => ['Home', 'Daftar Pertanyaan']
        ];

        $page = (object) [
            'title' => 'Daftar Pertanyaan'
        ];

        $activeMenu = 'daftar_pertanyaan'; // Set the active menu

        // Ambil data Pertanyaan dengan kategori Teknis
        // Ambil data pertanyaan beserta detailnya
        $pertanyaan = PertanyaanModel::where('kategori', 'Teknis')
            ->with(['m_user', 't_pertanyaan_detail']) // Relasi detail pertanyaan
            ->get();

        return view('daftarPertanyaan.daftarTeknis', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'pertanyaan' => $pertanyaan // Kirim data ke view
        ]);
    }

    public function tolakPertanyaanTeknis(Request $request, $id)
    {
        $pertanyaan = PertanyaanModel::with('m_user')->findOrFail($id); // Include m_user relation
        $user = $pertanyaan->m_user; // Mendapatkan data pengguna terkait

        $pertanyaan->status = 'Ditolak';
        $pertanyaan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $pertanyaan->updated_at = Carbon::now();
        $pertanyaan->save();

        // Kirim email
        Mail::to($user->email)->send(new PertanyaanNotificationMail(
            $user->nama,
            'Ditolak',
            $pertanyaan->kategori,
            $pertanyaan->status_pemohon,
            $request->reason
        ));

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil ditolak.',
        ]);
    }

    public function setujuiPertanyaanTeknis($id)
    {
        $pertanyaan = PertanyaanModel::with('m_user')->findOrFail($id);
        $DetailPertanyaan = DetailPertanyaanModel::with('pertanyaan_id')->findOrFail($id);

        $pertanyaan->status = 'Disetujui';
        $DetailPertanyaan->updated_at = Carbon::now();
        $pertanyaan->updated_at = Carbon::now();
        $pertanyaan->save();
        $DetailPertanyaan->save();

        PertanyaanLanjutModel::create([
            'pertanyaan_lanjut_id' => $pertanyaan->Pertanyaan_id,
            'user_id' => $pertanyaan->user_id,
            'kategori' => $pertanyaan->kategori,
            'status_pemohon' => $pertanyaan->status_pemohon,
            'judul_pemohon' => $pertanyaan->judul_pemohon,
            'deskripsi' => $pertanyaan->deskripsi,
            'dokumen_pendukung' => $pertanyaan->dokumen_pendukung,
            'status' => 'Diproses', // Tetap 'Diproses' di t_Pertanyaan_lanjut
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DetailPertanyaanLanjutModel::create([
            'detail_pertanyaan_lanjut_id' => $DetailPertanyaan->detail_pertanyaan_id,
            'pertanyaan_lanjut_id' => $pertanyaan->Pertanyaan_id,
            'pertanyaan' => $DetailPertanyaan->pertanyaan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Simpan notifikasi ke database
        NotifikasiMPUModel::create([
            'user_id' => $pertanyaan->user_id,
            'kategori' => 'Pertanyaan',
            'permohonan_lanjut_id' => null,
            'pertanyaan_lanjut_id' => $pertanyaan->Pertanyaan_id,
            'pesan' => auth()->user()->nama . ' Mengajukan Pertanyaan ' . $pertanyaan->kategori,
            'sudah_dibaca' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil disetujui.',
        ]);
    }
    public function hapusPertanyaanTeknis($id)
    {
        $notifikasi = PertanyaanLanjutModel::findOrFail($id);
        $notifikasi->deleted_at = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Pertanyaan berhasil dihapus']);
    }
}
