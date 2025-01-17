@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div>
            <a href="{{ url('pengajuanPertanyaan') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
        <h3 class="card-title"><strong> Daftar Pertanyaan Akademik </strong></h3>
    </div>
    <div class="card-body">
        @foreach($pertanyaan as $item)
            <div class="custom-container" data-id="{{ $item->id }}" data-status="{{ $item->status }}" style="
                            @if($item->status === 'Disetujui') background-color: lightgreen;
                            @elseif($item->status === 'Ditolak') background-color: lightpink;
                                @else background-color: lightblue; 
                            @endif">

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
            <div class="detail-container" style="display: none;">
                <div class="detail-content">
                    <div>
                        <strong>Pertanyaan:</strong>
                        <ul>
                            @foreach ($item->t_pertanyaan_detail_lanjut as $detail)
                                <li>
                                    <p>{{ $detail->pertanyaan }}</p>
                                    <textarea class="form-control jawaban-box" rows="2"
                                        data-id="{{ $detail->detail_pertanyaan_lanjut_id }}">{{ $detail->jawaban }}</textarea>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="action-buttons">
                        @if($item->status === 'Diproses')
                            <button class="btn btn-success btn-setujui setujui-pertanyaan"
                                data-id="{{ $item->pertanyaan_lanjut_id }}"
                                data-nama="{{ $item->m_user->nama }}">Setujui</button>
                        @endif
                        <button class="btn btn-danger btn-hapus hapus-pertanyaan"
                            data-id="{{ $item->pertanyaan_lanjut_id }}" data-nama="{{ $item->m_user->nama }}"
                            data-status="{{ $item->status }}">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>

<script>
    document.querySelectorAll('.toggle-detail').forEach(button => {
        button.addEventListener('click', function () {
            const detailContainer = this.closest('.custom-container').nextElementSibling;
            detailContainer.style.display = detailContainer.style.display === 'none' ? 'block' : 'none';
        });
    });

    document.querySelectorAll('.setujui-pertanyaan').forEach(button => {
        button.addEventListener('click', function () {
            const pertanyaanId = this.dataset.id; // This is already correctly defined
            const nama = this.dataset.nama;
            const jawabanBoxes = this.closest('.detail-container').querySelectorAll('.jawaban-box');

            const jawabanData = Array.from(jawabanBoxes).map(box => {
                return {
                    id: box.dataset.id,
                    jawaban: box.value.trim()
                };
            });

            if (jawabanData.some(j => j.jawaban === '')) {
                Swal.fire({
                    title: 'Jawaban Tidak Lengkap!',
                    text: 'Harap isi semua jawaban sebelum menyetujui.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pertanyaan ini akan disetujui beserta jawaban yang telah diisi.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Changed 'id' to 'pertanyaanId' here
                    fetch(`{{ url('pengajuanPertanyaan/akademik/setujui') }}/${pertanyaanId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ jawaban: jawabanData })
                    }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: `Pertanyaan ${nama} telah disetujui dengan jawaban yang diberikan.`,
                                    icon: 'success',
                                    allowOutsideClick: false,
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Terjadi kesalahan!',
                                text: 'Tidak dapat menyetujui pertanyaan.',
                                icon: 'error',
                                allowOutsideClick: false,
                            });
                        });
                }
            });
        });
    });

    document.querySelectorAll('.hapus-pertanyaan').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama;
            const status = this.dataset.status;

            if (status !== 'Disetujui' && status !== 'Ditolak') {
                Swal.fire({
                    title: 'Tidak Dapat Menghapus!',
                    text: 'Anda harus menyetujui atau menolak pertanyaan ini terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Pertanyaan ini akan dihapus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('pengajuanPertanyaan/akademik/hapus') }}/${id}`, {
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
                                    text: `Pertanyaan ${nama} telah dihapus.`,
                                    icon: 'success',
                                    allowOutsideClick: false,
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Terjadi kesalahan!',
                                text: 'Tidak dapat menghapus pertanyaan.',
                                icon: 'error',
                                allowOutsideClick: false,
                            });
                        });
                }
            });
        });
    });

    document.querySelectorAll('.tolak-pertanyaan').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const nama = this.dataset.nama;

            Swal.fire({
                title: 'Pilih alasan penolakan penyetujuan ini?',
                html: `
                <label><input type="radio" name="reason" value="Data tidak Valid"> Data tidak Valid</label><br>
                <label><input type="radio" name="reason" value="Data tidak Relevan"> Data tidak Relevan</label><br>
                <label><input type="radio" name="reason" value="Lainnya"> Lainnya</label><br>
                <textarea id="otherReason" placeholder="Masukkan alasan lain..." style="display:none; width:100%; margin-top:10px;"></textarea>
            `,
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const selectedReason = document.querySelector('input[name="reason"]:checked');
                    const otherReason = document.getElementById('otherReason').value;
                    if (!selectedReason) {
                        Swal.showValidationMessage('Pilih salah satu alasan!');
                    } else if (selectedReason.value === 'Lainnya' && !otherReason.trim()) {
                        Swal.showValidationMessage('Masukkan alasan!');
                    }
                    return selectedReason.value === 'Lainnya' ? otherReason : selectedReason.value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('pengajuanPertanyaan/akademik/tolak') }}/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ reason: result.value })
                    }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: `Pertanyaan ${nama} telah ditolak.`,
                                    icon: 'success',
                                    allowOutsideClick: false,
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Terjadi kesalahan!',
                                text: 'Tidak dapat menolak pertanyaan.',
                                icon: 'error',
                                allowOutsideClick: false,
                            });
                        });
                }
            });

            document.querySelectorAll('input[name="reason"]').forEach(radio => {
                radio.addEventListener('change', function () {
                    const otherReasonInput = document.getElementById('otherReason');
                    if (this.value === 'Lainnya') {
                        otherReasonInput.style.display = 'block';
                    } else {
                        otherReasonInput.style.display = 'none';
                    }
                });
            });
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

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
    }
</style>

@endsection