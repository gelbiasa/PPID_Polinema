<?php

namespace App\Http\Controllers;

use App\Mail\HasilPertanyaanMail;
use App\Models\DetailPertanyaanLanjutModel;
use App\Models\PertanyaanLanjutModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PengajuanPertanyaanController extends Controller
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

        $activeMenu = 'pengajuan_pertanyaan'; // Set the active menu

        return view('pengajuanPertanyaan.index', [
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

        $activeMenu = 'pengajuan_pertanyaan';

        // Ambil data pertanyaan beserta detailnya
        $pertanyaan = PertanyaanLanjutModel::where('kategori', 'Akademik')
            ->with(['m_user', 't_pertanyaan_detail_lanjut']) // Relasi detail pertanyaan
            ->get();

        return view('pengajuanPertanyaan.daftarAkademik', [
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
            $pertanyaan = PertanyaanLanjutModel::with(['t_pertanyaan_detail_lanjut', 'm_user'])->findOrFail($id);
            $user = $pertanyaan->m_user;

            // Update status dan alasan penolakan
            $pertanyaan->status = 'Ditolak';
            $pertanyaan->alasan_penolakan = $request->reason;
            $pertanyaan->updated_at = now();
            $pertanyaan->save();

            // Kirim email notifikasi
            Mail::to($user->email)->send(new HasilPertanyaanMail(
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

    public function setujuiPertanyaanAkademik(Request $request, $id)
    {
        try {
            // Ambil data pertanyaan beserta detail dan user
            $pertanyaan = PertanyaanLanjutModel::with(['t_pertanyaan_detail_lanjut', 'm_user'])->findOrFail($id);
            $user = $pertanyaan->m_user;

            // Update jawaban untuk setiap pertanyaan detail
            $detailPertanyaan = [];
            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $jawaban) {
                    // Update jawaban
                    DetailPertanyaanLanjutModel::where('detail_pertanyaan_lanjut_id', $jawaban['id'])
                        ->update(['jawaban' => $jawaban['jawaban']]);

                    // Simpan detail untuk email
                    $detail = DetailPertanyaanLanjutModel::find($jawaban['id']);
                    $detailPertanyaan[] = [
                        'pertanyaan' => $detail->pertanyaan,
                        'jawaban' => $jawaban['jawaban'] // Gunakan jawaban yang baru diupdate
                    ];
                }
            }

            // Update status pertanyaan
            $pertanyaan->status = 'Disetujui';
            $pertanyaan->updated_at = now();
            $pertanyaan->save();

            // Kirim email notifikasi dengan data yang sudah diupdate
            Mail::to($user->email)->send(new HasilPertanyaanMail(
                $user->nama,
                'Disetujui',
                $pertanyaan->kategori,
                $pertanyaan->status_pemohon,
                null,
                $detailPertanyaan
            ));

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
            $pertanyaan = PertanyaanLanjutModel::findOrFail($id);

            // Update nilai deleted_at pada t_pertanyaan
            $pertanyaan->deleted_at = Carbon::now();
            $pertanyaan->save();

            // Update nilai deleted_at untuk semua detail yang berelasi
            DetailPertanyaanLanjutModel::where('pertanyaan_lanjut_id', $id)
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

        $activeMenu = 'pengajuan_pertanyaan';

        // Ambil data pertanyaan beserta detailnya
        $pertanyaan = PertanyaanLanjutModel::where('kategori', 'layanan')
            ->with(['m_user', 't_pertanyaan_detail_lanjut']) // Relasi detail pertanyaan
            ->get();

        return view('pengajuanPertanyaan.daftarlayanan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'pertanyaan' => $pertanyaan,
        ]);
    }

    public function tolakPertanyaanLayanan(Request $request, $id)
    {
        $pertanyaan = PertanyaanLanjutModel::with(['t_pertanyaan_detail_lanjut', 'm_user'])->findOrFail($id);
        $user = $pertanyaan->m_user; // Mendapatkan data pengguna terkait

        $pertanyaan->status = 'Ditolak';
        $pertanyaan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $pertanyaan->updated_at = Carbon::now();
        $pertanyaan->save();

        // Kirim email
        Mail::to($user->email)->send(new HasilPertanyaanMail(
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

    public function setujuiPertanyaanLayanan(Request $request, $id)
    {
        try {
            // Ambil data pertanyaan beserta detail dan user
            $pertanyaan = PertanyaanLanjutModel::with(['t_pertanyaan_detail_lanjut', 'm_user'])->findOrFail($id);
            $user = $pertanyaan->m_user;

            // Update jawaban untuk setiap pertanyaan detail
            $detailPertanyaan = [];
            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $jawaban) {
                    // Update jawaban
                    DetailPertanyaanLanjutModel::where('detail_pertanyaan_lanjut_id', $jawaban['id'])
                        ->update(['jawaban' => $jawaban['jawaban']]);

                    // Simpan detail untuk email
                    $detail = DetailPertanyaanLanjutModel::find($jawaban['id']);
                    $detailPertanyaan[] = [
                        'pertanyaan' => $detail->pertanyaan,
                        'jawaban' => $jawaban['jawaban'] // Gunakan jawaban yang baru diupdate
                    ];
                }
            }

            // Update status pertanyaan
            $pertanyaan->status = 'Disetujui';
            $pertanyaan->updated_at = now();
            $pertanyaan->save();

            // Kirim email notifikasi dengan data yang sudah diupdate
            Mail::to($user->email)->send(new HasilPertanyaanMail(
                $user->nama,
                'Disetujui',
                $pertanyaan->kategori,
                $pertanyaan->status_pemohon,
                null,
                $detailPertanyaan
            ));

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
            $pertanyaan = PertanyaanLanjutModel::findOrFail($id);

            // Update nilai deleted_at pada t_pertanyaan
            $pertanyaan->deleted_at = Carbon::now();
            $pertanyaan->save();

            // Update nilai deleted_at untuk semua detail yang berelasi
            DetailPertanyaanLanjutModel::where('pertanyaan_lanjut_id', $id)
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

        $activeMenu = 'pengajuan_pertanyaan'; // Set the active menu

        // Ambil data pertanyaan beserta detailnya
        $pertanyaan = PertanyaanLanjutModel::where('kategori', 'Teknis')
            ->with(['m_user', 't_pertanyaan_detail_lanjut']) // Relasi detail pertanyaan
            ->get();

        return view('pengajuanPertanyaan.daftarTeknis', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'pertanyaan' => $pertanyaan // Kirim data ke view
        ]);
    }

    public function tolakPertanyaanTeknis(Request $request, $id)
    {
        $pertanyaan = PertanyaanLanjutModel::with(['t_pertanyaan_detail_lanjut', 'm_user'])->findOrFail($id);
        $user = $pertanyaan->m_user; // Mendapatkan data pengguna terkait

        $pertanyaan->status = 'Ditolak';
        $pertanyaan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $pertanyaan->updated_at = Carbon::now();
        $pertanyaan->save();

        // Kirim email
        Mail::to($user->email)->send(new HasilPertanyaanMail(
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

    public function setujuiPertanyaanTeknis(Request $request, $id)
    {
        try {
            // Ambil data pertanyaan beserta detail dan user
            $pertanyaan = PertanyaanLanjutModel::with(['t_pertanyaan_detail_lanjut', 'm_user'])->findOrFail($id);
            $user = $pertanyaan->m_user;

            // Update jawaban untuk setiap pertanyaan detail
            $detailPertanyaan = [];
            if ($request->has('jawaban')) {
                foreach ($request->jawaban as $jawaban) {
                    // Update jawaban
                    DetailPertanyaanLanjutModel::where('detail_pertanyaan_lanjut_id', $jawaban['id'])
                        ->update(['jawaban' => $jawaban['jawaban']]);

                    // Simpan detail untuk email
                    $detail = DetailPertanyaanLanjutModel::find($jawaban['id']);
                    $detailPertanyaan[] = [
                        'pertanyaan' => $detail->pertanyaan,
                        'jawaban' => $jawaban['jawaban'] // Gunakan jawaban yang baru diupdate
                    ];
                }
            }

            // Update status pertanyaan
            $pertanyaan->status = 'Disetujui';
            $pertanyaan->updated_at = now();
            $pertanyaan->save();

            // Kirim email notifikasi dengan data yang sudah diupdate
            Mail::to($user->email)->send(new HasilPertanyaanMail(
                $user->nama,
                'Disetujui',
                $pertanyaan->kategori,
                $pertanyaan->status_pemohon,
                null,
                $detailPertanyaan
            ));

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
            $pertanyaan = PertanyaanLanjutModel::findOrFail($id);

            // Update nilai deleted_at pada t_pertanyaan
            $pertanyaan->deleted_at = Carbon::now();
            $pertanyaan->save();

            // Update nilai deleted_at untuk semua detail yang berelasi
            DetailPertanyaanLanjutModel::where('pertanyaan_lanjut_id', $id)
                ->update(['deleted_at' => Carbon::now()]);

            return response()->json(['success' => true, 'message' => 'Pertanyaan dan detail berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error saat menghapus pertanyaan teknis: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menghapus pertanyaan'], 500);
        }
    }
}
