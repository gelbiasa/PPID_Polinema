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
        try {
            // Ambil data pertanyaan beserta detail dan user
            $pertanyaan = PertanyaanModel::with(['t_pertanyaan_detail', 'm_user'])->findOrFail($id);
            $user = $pertanyaan->m_user;

            // Update status dan alasan penolakan
            $pertanyaan->status = 'Ditolak';
            $pertanyaan->alasan_penolakan = $request->reason;
            $pertanyaan->updated_at = now();
            $pertanyaan->save();

            // Kirim email notifikasi
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
        } catch (\Exception $e) {
            Log::error('Error saat menolak pertanyaan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menolak pertanyaan.'
            ], 500);
        }
    }


    public function setujuiPertanyaanAkademik($id)
    {
        try {
            // Ambil data pertanyaan beserta detail dan user
            $pertanyaan = PertanyaanModel::with(['t_pertanyaan_detail', 'm_user'])->findOrFail($id);

            // Update status pertanyaan
            $pertanyaan->status = 'Disetujui';
            $pertanyaan->updated_at = now();
            $pertanyaan->save();

            // Buat record baru di t_pertanyaan_lanjut
            $pertanyaanLanjut = PertanyaanLanjutModel::create([
                'user_id' => $pertanyaan->user_id,
                'kategori' => $pertanyaan->kategori,
                'status_pemohon' => $pertanyaan->status_pemohon,
                'status' => 'Diproses',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Salin semua detail pertanyaan ke t_pertanyaan_detail_lanjut
            foreach ($pertanyaan->t_pertanyaan_detail as $detail) {
                DetailPertanyaanLanjutModel::create([
                    'pertanyaan_lanjut_id' => $pertanyaanLanjut->pertanyaan_lanjut_id,
                    'pertanyaan' => $detail->pertanyaan,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Buat notifikasi
            NotifikasiMPUModel::create([
                'user_id' => $pertanyaan->user_id,
                'kategori' => 'pertanyaan',
                'permohonan_lanjut_id' => null,
                'pertanyaan_lanjut_id' => $pertanyaanLanjut->pertanyaan_lanjut_id,
                'pesan' => $pertanyaan->m_user->nama . ' Mengajukan Pertanyaan ' . $pertanyaan->kategori,
                'sudah_dibaca' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil disetujui.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menyetujui pertanyaan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui pertanyaan.'
            ], 500);
        }
    }

    public function hapusPertanyaanAKademik($id)
    {
        try {
            // Ambil data pertanyaan berdasarkan ID
            $pertanyaan = PertanyaanModel::findOrFail($id);

            // Update nilai deleted_at pada t_pertanyaan
            $pertanyaan->deleted_at = Carbon::now();
            $pertanyaan->save();

            // Update nilai deleted_at untuk semua detail yang berelasi
            DetailPertanyaanModel::where('pertanyaan_id', $id)
                ->update(['deleted_at' => Carbon::now()]);

            return response()->json(['success' => true, 'message' => 'Pertanyaan dan detail berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error saat menghapus pertanyaan teknis: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus pertanyaan'], 500);
        }
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
        $pertanyaan = PertanyaanModel::with(['t_pertanyaan_detail', 'm_user'])->findOrFail($id);
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
            'message' => 'Permohonan berhasil ditolak.',
        ]);
    }

    public function setujuiPertanyaanLayanan($id)
    {
        try {
            // Ambil data pertanyaan beserta detail dan user
            $pertanyaan = PertanyaanModel::with(['t_pertanyaan_detail', 'm_user'])->findOrFail($id);

            // Update status pertanyaan
            $pertanyaan->status = 'Disetujui';
            $pertanyaan->updated_at = now();
            $pertanyaan->save();

            // Buat record baru di t_pertanyaan_lanjut
            $pertanyaanLanjut = PertanyaanLanjutModel::create([
                'user_id' => $pertanyaan->user_id,
                'kategori' => $pertanyaan->kategori,
                'status_pemohon' => $pertanyaan->status_pemohon,
                'status' => 'Diproses',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Salin semua detail pertanyaan ke t_pertanyaan_detail_lanjut
            foreach ($pertanyaan->t_pertanyaan_detail as $detail) {
                DetailPertanyaanLanjutModel::create([
                    'pertanyaan_lanjut_id' => $pertanyaanLanjut->pertanyaan_lanjut_id,
                    'pertanyaan' => $detail->pertanyaan,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Buat notifikasi
            NotifikasiMPUModel::create([
                'user_id' => $pertanyaan->user_id,
                'kategori' => 'pertanyaan',
                'permohonan_lanjut_id' => null,
                'pertanyaan_lanjut_id' => $pertanyaanLanjut->pertanyaan_lanjut_id,
                'pesan' => $pertanyaan->m_user->nama . ' Mengajukan Pertanyaan ' . $pertanyaan->kategori,
                'sudah_dibaca' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil disetujui.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menyetujui pertanyaan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui pertanyaan.'
            ], 500);
        }
    }
    public function hapusPertanyaanLayanan($id)
    {
        try {
            // Ambil data pertanyaan berdasarkan ID
            $pertanyaan = PertanyaanModel::findOrFail($id);

            // Update nilai deleted_at pada t_pertanyaan
            $pertanyaan->deleted_at = Carbon::now();
            $pertanyaan->save();

            // Update nilai deleted_at untuk semua detail yang berelasi
            DetailPertanyaanModel::where('pertanyaan_id', $id)
                ->update(['deleted_at' => Carbon::now()]);

            return response()->json(['success' => true, 'message' => 'Pertanyaan dan detail berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error saat menghapus pertanyaan teknis: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus pertanyaan'], 500);
        }
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
        $pertanyaan = PertanyaanModel::with(['t_pertanyaan_detail', 'm_user'])->findOrFail($id);
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
        try {
            // Ambil data pertanyaan beserta detail dan user
            $pertanyaan = PertanyaanModel::with(['t_pertanyaan_detail', 'm_user'])->findOrFail($id);

            // Update status pertanyaan
            $pertanyaan->status = 'Disetujui';
            $pertanyaan->updated_at = now();
            $pertanyaan->save();

            // Buat record baru di t_pertanyaan_lanjut
            $pertanyaanLanjut = PertanyaanLanjutModel::create([
                'user_id' => $pertanyaan->user_id,
                'kategori' => $pertanyaan->kategori,
                'status_pemohon' => $pertanyaan->status_pemohon,
                'status' => 'Diproses',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Salin semua detail pertanyaan ke t_pertanyaan_detail_lanjut
            foreach ($pertanyaan->t_pertanyaan_detail as $detail) {
                DetailPertanyaanLanjutModel::create([
                    'pertanyaan_lanjut_id' => $pertanyaanLanjut->pertanyaan_lanjut_id,
                    'pertanyaan' => $detail->pertanyaan,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Buat notifikasi
            NotifikasiMPUModel::create([
                'user_id' => $pertanyaan->user_id,
                'kategori' => 'pertanyaan',
                'permohonan_lanjut_id' => null,
                'pertanyaan_lanjut_id' => $pertanyaanLanjut->pertanyaan_lanjut_id,
                'pesan' => $pertanyaan->m_user->nama . ' Mengajukan Pertanyaan ' . $pertanyaan->kategori,
                'sudah_dibaca' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil disetujui.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saat menyetujui pertanyaan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui pertanyaan.'
            ], 500);
        }
    }
    public function hapusPertanyaanTeknis($id)
    {
        try {
            // Ambil data pertanyaan berdasarkan ID
            $pertanyaan = PertanyaanModel::findOrFail($id);

            // Update nilai deleted_at pada t_pertanyaan
            $pertanyaan->deleted_at = Carbon::now();
            $pertanyaan->save();

            // Update nilai deleted_at untuk semua detail yang berelasi
            DetailPertanyaanModel::where('pertanyaan_id', $id)
                ->update(['deleted_at' => Carbon::now()]);

            return response()->json(['success' => true, 'message' => 'Pertanyaan dan detail berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error saat menghapus pertanyaan teknis: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus pertanyaan'], 500);
        }
    }
}
