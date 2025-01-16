@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <a href="{{ url('notifAdmin') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <h3 class="card-title"><strong> Notifikasi Pengajuan Permohonan Informasi </strong></h3>
    </div>
    <div class="card-body">
        <!-- Container Notifikasi -->
        @foreach($notifikasi as $item)
        <div class="p-3 mb-3 notifikasi-item {{ $item->sudah_dibaca ? 'notifikasi-dibaca' : '' }}" 
             style="border-radius: 10px; display: flex; align-items: center; background-color: {{ $item->sudah_dibaca ? '#d4edda' : '#b3e5fc' }};">
            <i class="fas fa-bell fa-2x" style="margin-right: 15px;"></i>
            <div style="flex: 1;">
                <p style="margin: 0; font-weight: bold;">{{ $item->pesan }}</p>
                <p style="margin: 0;">Status pemohon: {{ $item->t_permohonan->status_pemohon ?? '-' }}</p>
                <p style="margin: 0;">Kategori: {{ $item->t_permohonan->kategori ?? '-' }}</p>
                <p style="margin: 0; color: grey;">email: {{ $item->m_user->email }}</p>
                <p style="margin: 0; color: grey;">nomor handphone: {{ $item->m_user->no_hp }}</p>
                <p style="margin: 0;">{{ $item->created_at->diffForHumans() }}</p>
            </div>

            <!-- Tombol -->
            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                <!-- Tombol Hapus -->
                <button class="btn btn-danger btn-sm hapus-notifikasi" data-id="{{ $item->notifikasi_adm_id }}" style="width: 132px;">
                    Hapus
                </button>
                <!-- Tombol Tandai Telah Dibaca -->
                @if(!$item->sudah_dibaca)
                <button class="btn btn-secondary btn-sm mt-2 tandai-dibaca" data-id="{{ $item->notifikasi_adm_id }}">
                    Tandai telah Dibaca
                </button>
                @endif
            </div>
        </div>
        @endforeach
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
                    fetch(`{{ url('notifAdmin/tandai-dibaca') }}/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Berhasil!',
                                data.message,
                                'success'
                            );
                            location.reload();
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
                    fetch(`{{ url('notifAdmin/hapus') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        }
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Dihapus!',
                                data.message,
                                'success'
                            );
                            location.reload();
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
