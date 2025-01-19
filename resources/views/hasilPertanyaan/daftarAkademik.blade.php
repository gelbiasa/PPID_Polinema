@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <a href="{{ url('hasilPertanyaan') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <h3 class="card-title"><strong> Hasil Pertanyaan Akademik </strong></h3>
    </div>
    <div class="card-body">
        @php
            $checkPertanyaan = $pertanyaan->filter(function ($item) {
                return $item->kategori === 'Akademik';
            });
        @endphp

        @if($checkPertanyaan->isEmpty())
            <div class="d-flex flex-column align-items-center justify-content-center"
                style="height: 200px; background-color: #fff3cd; border: 1px solid #856404; border-radius: 10px;">
                <span style="font-size: 50px;">📭</span>
                <p style="margin: 0; font-weight: bold; font-size: 18px; text-align: center;">Tidak ada Hasil
                    Pertanyaan Akademik</p>
            </div>
        @else
            @foreach($pertanyaan as $item)
                <div class="custom-container {{ $item->sudah_dibaca ? 'dibaca' : '' }}" data-id="{{ $item->id }}"
                    data-status="{{ $item->status }}">
                    <div class="info">
                        <p>
                            <strong>Nama:</strong> {{ $item->m_user->nama }} |
                            <strong>Nomor Handphone:</strong> {{ $item->m_user->no_hp }} |
                            <strong>Email:</strong> {{ $item->m_user->email }}
                        </p>
                        <p class="pemohon-info">
                            <strong>Status pemohon:</strong> {{ $item->status_pemohon }} <br>
                        </p>
                    </div>
                    <button class="btn btn-primary badge-button toggle-detail">Lihat Detail</button>
                </div>
                <!-- Container Detail -->
                <div class="detail-container" style="display: none; position: relative;">
                    <div class="detail-content">
                        <div>
                            <strong>Pertanyaan:</strong>
                            <ol>
                                @foreach ($item->t_pertanyaan_detail_lanjut as $index => $detail)
                                    <li>
                                        <p><strong> Pertanyaan: </strong>{{ $detail->pertanyaan }}</p>
                                        <p><strong> Jawaban: </strong> {{ $detail->jawaban }}</p>
                                    </li>
                                @endforeach
                            </ol>
                        </div>
                        @if(!$item->sudah_dibaca)
                            <button class="btn btn-secondary btn-sm tandai-dibaca" data-id="{{ $item->pertanyaan_lanjut_id }}"
                                style="position: absolute; bottom: 10px; right: 10px;">
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
                text: "Anda akan menandai hasil pengajuan pertanyaan ini sebagai telah dibaca.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tandai!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user mengkonfirmasi, lakukan aksi "tandai telah dibaca"
                    fetch(`{{ url('hasilPertanyaan/tandai-dibaca') }}/${id}`, {
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

    document.querySelectorAll('.toggle-detail').forEach(button => {
        button.addEventListener('click', function () {
            const detailContainer = this.closest('.custom-container').nextElementSibling;
            detailContainer.style.display = detailContainer.style.display === 'none' ? 'block' : 'none';
        });
    });
</script>

<style>
    .custom-container {
        background-color: lightblue;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
        color: black;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .custom-container.dibaca {
        background-color: lightgreen;
    }

    .info {
        flex: 1;
        margin-right: 20px;
    }

    .pemohon-info {
        margin-bottom: 0;
    }

    .badge-button {
        background-color: blue;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 15px;
        font-size: 14px;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
    }

    .badge-button:hover {
        background-color: darkblue;
    }

    .detail-container {
        background-color: white;
        border: 1px solid black;
        border-radius: 10px;
        margin-top: 15px;
        margin-bottom: 15px;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .detail-content p {
        margin: 0;
        font-size: 14px;
        font-family: Arial, sans-serif;
    }

    ol {
        padding-left: 20px;
    }
</style>

@endsection