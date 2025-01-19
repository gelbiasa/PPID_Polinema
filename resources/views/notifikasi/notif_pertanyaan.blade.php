@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <a href="{{ url('notifikasi') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <h3 class="card-title"><strong> Notifikasi Pengajuan Pertanyaan Informasi </strong></h3>
    </div>
    <div class="card-body">
        @if($notifikasi->isEmpty())
            <!-- Jika tidak ada notifikasi -->
            <div class="d-flex flex-column align-items-center justify-content-center"
                style="height: 200px; background-color: #fff3cd; border: 1px solid #856404; border-radius: 10px;">
                <span style="font-size: 50px;">📭</span>
                <p style="margin: 0; font-weight: bold; font-size: 18px; text-align: center;">Tidak ada Notifikasi
                    Pertanyaan</p>
            </div>
        @else
            <!-- Container Notifikasi -->
            @foreach($notifikasi as $item)
                <div class="p-3 mb-3 notifikasi-item {{ $item->sudah_dibaca ? 'notifikasi-dibaca' : '' }}"
                    style="border-radius: 10px; display: flex; align-items: center; background-color: {{ $item->sudah_dibaca ? '#d4edda' : '#b3e5fc' }};">
                    <i class="fas fa-bell fa-2x" style="margin-right: 15px;"></i>
                    <div style="flex: 1;">
                        <p style="margin: 0; font-weight: bold;">{{ $item->pesan }}</p>
                        <p style="margin: 0;">Status pemohon: {{ $item->t_pertanyaan->status_pemohon ?? 'Data Sudah Dihapus' }}
                        </p>
                        <p style="margin: 0;">Kategori: {{ $item->t_pertanyaan->kategori ?? 'Data Sudah Dihapus' }}</p>
                        <p style="margin: 0; color: grey;">email: {{ $item->m_user->email }}</p>
                        <p style="margin: 0; color: grey;">nomor handphone: {{ $item->m_user->no_hp }}</p>
                        <p style="margin: 0;">{{ $item->created_at->diffForHumans() }}</p>
                    </div>

                    <!-- Tombol -->
                    <div style="display: flex; flex-direction: column; align-items: flex-end;">
                        <!-- Tombol Hapus -->
                        <button class="btn btn-danger btn-sm hapus-notifikasi" data-id="{{ $item->notifikasi_vfr_id }}"
                            data-sudah-dibaca="{{ $item->sudah_dibaca }}" style="width: 132px;">
                            Hapus
                        </button>
                        <!-- Tombol Tandai Telah Dibaca -->
                        @if(!$item->sudah_dibaca)
                            <button class="btn btn-secondary btn-sm mt-2 tandai-dibaca" data-id="{{ $item->notifikasi_vfr_id }}">
                                Tandai telah Dibaca
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<script>
    // Tandai Telah Dibaca
    document.querySelectorAll('.tandai-dibaca').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;

            // Menampilkan popup SweetAlert2 untuk konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan menandai notifikasi ini sebagai telah dibaca.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tandai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user mengkonfirmasi, lakukan aksi "tandai telah dibaca"
                    fetch(`{{ url('notifikasi/tandai-dibaca') }}/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        }
                    }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    allowOutsideClick: false, // Mencegah menutup modal dengan klik luar
                                }).then(() => {
                                    location.reload(); // Reload setelah pengguna menekan OK
                                });
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Terjadi kesalahan!',
                                'Tidak dapat menandai notifikasi.',
                                'error'
                            );
                        });
                }
            });
        });
    });

    // Hapus Notifikasi
    document.querySelectorAll('.hapus-notifikasi').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const sudahDibaca = this.dataset.sudahDibaca; // Ambil status sudah_dibaca

            // Jika notifikasi belum dibaca, tampilkan pesan pemberitahuan
            if (!sudahDibaca || sudahDibaca === 'null') {
                Swal.fire(
                    'Tidak Bisa Dihapus!',
                    'Notifikasi ini tidak bisa dihapus. Anda harus menandai notifikasi dengan "Tandai telah dibaca" terlebih dahulu.',
                    'warning'
                );
                return; // Keluar dari handler jika belum dibaca
            }

            // Menampilkan popup SweetAlert2 untuk konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Notifikasi ini akan dihapus dan tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user mengkonfirmasi, lakukan aksi "hapus notifikasi"
                    fetch(`{{ url('notifikasi/hapus') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        }
                    }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Dihapus!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    allowOutsideClick: false, // Mencegah menutup modal dengan klik luar
                                }).then(() => {
                                    location.reload(); // Reload setelah pengguna menekan OK
                                });
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Terjadi kesalahan!',
                                'Tidak dapat menghapus notifikasi.',
                                'error'
                            );
                        });
                }
            });
        });
    });


</script>

@endsection