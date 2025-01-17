<?php

namespace App\Http\Controllers;

use App\Mail\HasilPermohonanMail;
use App\Models\PermohonanLanjutModel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PengajuanPermohonanController extends Controller
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

        $activeMenu = 'pengajuan_permohonan'; // Set the active menu

        return view('pengajuanPermohonan.index', [
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

        $activeMenu = 'pengajuan_permohonan'; // Set the active menu

        // Ambil data permohonan dengan kategori Akademik
        $permohonan = PermohonanLanjutModel::where('kategori', 'Akademik')
            ->with('m_user') // Pastikan relasi ke User telah didefinisikan
            ->get();

        return view('pengajuanPermohonan.daftarAkademik', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'permohonan' => $permohonan // Kirim data ke view
        ]);
    }

    public function tolakPermohonanAkademik(Request $request, $id)
    {
        $permohonan = PermohonanLanjutModel::with('m_user')->findOrFail($id); // Include m_user relation
        $user = $permohonan->m_user; // Mendapatkan data pengguna terkait

        $permohonan->status = 'Ditolak';
        $permohonan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $permohonan->updated_at = Carbon::now();
        $permohonan->save();

        Mail::to($user->email)->send(new HasilPermohonanMail(
                    $user->nama,
                    'Ditolak',
                    $permohonan->kategori,
                    $permohonan->status_pemohon,
                    $request->reason // Alasan ditambahkan
                ));

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil ditolak.',
        ]);
    }

    public function setujuiPermohonanAkademik(Request $request, $id)
    {
        try {

            $permohonan = PermohonanLanjutModel::with('m_user')->findOrFail($id); // Include m_user relation
            $user = $permohonan->m_user; // Mendapatkan data pengguna terkait
            // Validasi request
            $request->validate([
                'jawaban' => 'required|file|mimes:pdf,doc,docx,xlsx|max:10240', // max 10MB
            ]);

            // Temukan permohonan berdasarkan ID
            $permohonan = PermohonanLanjutModel::with('m_user')->findOrFail($id);

            // Hapus file jawaban lama jika ada
            if ($permohonan->jawaban && Storage::disk('public')->exists($permohonan->jawaban)) {
                Storage::disk('public')->delete($permohonan->jawaban);
            }

            // Upload file baru
            if ($request->hasFile('jawaban')) {
                $file = $request->file('jawaban');

                // Buat nama file hash
                $hashName = hash('sha256', time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

                // Simpan file dengan nama hash
                $jawabanPath = $file->storeAs('jawaban', $hashName, 'public');

                // Perbarui status dan file jawaban di database
                $permohonan->status = 'Disetujui';
                $permohonan->jawaban = $jawabanPath;
                $permohonan->updated_at = now();
                $permohonan->save();

                Mail::to($user->email)->send(new HasilPermohonanMail(
                    $user->nama,
                    'Disetujui',
                    $permohonan->kategori,
                    $permohonan->status_pemohon,
                    $jawabanPath // Path file digunakan sebagai alasan
                ));

                return response()->json([
                    'success' => true,
                    'message' => 'Permohonan berhasil disetujui dan file jawaban telah diunggah.'
                ]);
            }

            throw new \Exception('Gagal mengunggah file.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error pada setujuiPermohonanAkademik: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui permohonan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function hapusPermohonanAKademik($id)
    {
        $notifikasi = PermohonanLanjutModel::findOrFail($id);
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

        $activeMenu = 'pengajuan_permohonan'; // Set the active menu

        // Ambil data permohonan dengan kategori Layanan
        $permohonan = PermohonanLanjutModel::where('kategori', 'Layanan')
            ->with('m_user') // Pastikan relasi ke User telah didefinisikan
            ->get();

        return view('pengajuanPermohonan.daftarLayanan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'permohonan' => $permohonan // Kirim data ke view
        ]);
    }

    public function tolakPermohonanLayanan(Request $request, $id)
    {
        $permohonan = PermohonanLanjutModel::with('m_user')->findOrFail($id); // Include m_user relation
        $user = $permohonan->m_user; // Mendapatkan data pengguna terkait

        $permohonan->status = 'Ditolak';
        $permohonan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $permohonan->updated_at = Carbon::now();
        $permohonan->save();

        // Kirim email
        Mail::to($user->email)->send(new HasilPermohonanMail(
            $user->nama,
            'Ditolak',
            $permohonan->kategori,
            $permohonan->status_pemohon,
            $request->reason
        ));

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil ditolak.',
        ]);
    }

    public function setujuiPermohonanLayanan(Request $request, $id)
    {
        try {

            $permohonan = PermohonanLanjutModel::with('m_user')->findOrFail($id); // Include m_user relation
            $user = $permohonan->m_user; // Mendapatkan data pengguna terkait
            // Validasi request
            $request->validate([
                'jawaban' => 'required|file|mimes:pdf,doc,docx,xlsx|max:10240', // max 10MB
            ]);

            // Temukan permohonan berdasarkan ID
            $permohonan = PermohonanLanjutModel::with('m_user')->findOrFail($id);

            // Hapus file jawaban lama jika ada
            if ($permohonan->jawaban && Storage::disk('public')->exists($permohonan->jawaban)) {
                Storage::disk('public')->delete($permohonan->jawaban);
            }

            // Upload file baru
            if ($request->hasFile('jawaban')) {
                $file = $request->file('jawaban');

                // Buat nama file hash
                $hashName = hash('sha256', time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

                // Simpan file dengan nama hash
                $jawabanPath = $file->storeAs('jawaban', $hashName, 'public');

                // Perbarui status dan file jawaban di database
                $permohonan->status = 'Disetujui';
                $permohonan->jawaban = $jawabanPath;
                $permohonan->updated_at = now();
                $permohonan->save();

                Mail::to($user->email)->send(new HasilPermohonanMail(
                    $user->nama,
                    'Disetujui',
                    $permohonan->kategori,
                    $permohonan->status_pemohon,
                    $jawabanPath // Path file digunakan sebagai alasan
                ));

                return response()->json([
                    'success' => true,
                    'message' => 'Permohonan berhasil disetujui dan file jawaban telah diunggah.'
                ]);
            }

            throw new \Exception('Gagal mengunggah file.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error pada setujuiPermohonanAkademik: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui permohonan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function hapusPermohonanLayanan($id)
    {
        $notifikasi = PermohonanLanjutModel::findOrFail($id);
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

        $activeMenu = 'pengajuan_permohonan'; // Set the active menu

        // Ambil data permohonan dengan kategori Teknis
        $permohonan = PermohonanLanjutModel::where('kategori', 'Teknis')
            ->with('m_user') // Pastikan relasi ke User telah didefinisikan
            ->get();

        return view('pengajuanPermohonan.daftarTeknis', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'permohonan' => $permohonan // Kirim data ke view
        ]);
    }

    public function tolakPermohonanTeknis(Request $request, $id)
    {
        $permohonan = PermohonanLanjutModel::with('m_user')->findOrFail($id); // Include m_user relation
        $user = $permohonan->m_user; // Mendapatkan data pengguna terkait

        $permohonan->status = 'Ditolak';
        $permohonan->alasan_penolakan = $request->reason; // Alasan dari input pengguna
        $permohonan->updated_at = Carbon::now();
        $permohonan->save();

        // Kirim email
        Mail::to($user->email)->send(new HasilPermohonanMail(
            $user->nama,
            'Ditolak',
            $permohonan->kategori,
            $permohonan->status_pemohon,
            $request->reason
        ));

        return response()->json([
            'success' => true,
            'message' => 'Permohonan berhasil ditolak.',
        ]);
    }

    public function setujuiPermohonanTeknis(Request $request, $id)
    {
        try {

            $permohonan = PermohonanLanjutModel::with('m_user')->findOrFail($id); // Include m_user relation
            $user = $permohonan->m_user; // Mendapatkan data pengguna terkait
            // Validasi request
            $request->validate([
                'jawaban' => 'required|file|mimes:pdf,doc,docx,xlsx|max:10240', // max 10MB
            ]);

            // Temukan permohonan berdasarkan ID
            $permohonan = PermohonanLanjutModel::with('m_user')->findOrFail($id);

            // Hapus file jawaban lama jika ada
            if ($permohonan->jawaban && Storage::disk('public')->exists($permohonan->jawaban)) {
                Storage::disk('public')->delete($permohonan->jawaban);
            }

            // Upload file baru
            if ($request->hasFile('jawaban')) {
                $file = $request->file('jawaban');

                // Buat nama file hash
                $hashName = hash('sha256', time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

                // Simpan file dengan nama hash
                $jawabanPath = $file->storeAs('jawaban', $hashName, 'public');

                // Perbarui status dan file jawaban di database
                $permohonan->status = 'Disetujui';
                $permohonan->jawaban = $jawabanPath;
                $permohonan->updated_at = now();
                $permohonan->save();

                Mail::to($user->email)->send(new HasilPermohonanMail(
                    $user->nama,
                    'Disetujui',
                    $permohonan->kategori,
                    $permohonan->status_pemohon,
                    $jawabanPath // Path file digunakan sebagai alasan
                ));

                return response()->json([
                    'success' => true,
                    'message' => 'Permohonan berhasil disetujui dan file jawaban telah diunggah.'
                ]);
            }

            throw new \Exception('Gagal mengunggah file.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error pada setujuiPermohonanAkademik: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyetujui permohonan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function hapusPermohonanTeknis($id)
    {
        $notifikasi = PermohonanLanjutModel::findOrFail($id);
        $notifikasi->deleted_at = Carbon::now();
        $notifikasi->save();

        return response()->json(['success' => true, 'message' => 'Permohonan berhasil dihapus']);
    }
}
