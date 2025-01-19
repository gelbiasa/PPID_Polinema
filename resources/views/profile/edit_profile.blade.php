<style>
    /* Menambahkan border biru pada container */
    .card.border-primary {
        border: 2px solid blue;
        border-radius: 10px;
    }

    /* Memindahkan label ke kiri dan merapikan layout */
    .form-group label {
        text-align: left;
        display: block;
        margin-bottom: 5px;
    }

    /* Styling untuk box biru di belakang teks level */
    .user-level {
        background-color: blue;
        color: white;
        display: inline-block;
        padding: 5px 15px;
        border-radius: 10px;
        margin-top: 10px;
    }

    /* Menyelaraskan elemen dalam layout */
    .profile-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
        margin-bottom: 50px;
    }

    .custom-file {
        position: relative;
        display: inline-block;
        width: 100%;
        height: 2.5rem;
        border: 1px solid #000; /* Black border as in your example */
        border-radius: 5px;
        overflow: hidden;
    }

    .custom-file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        right: 0;
        opacity: 0;
        cursor: pointer;
    }

    .custom-file-label {
        position: relative;
        padding: 0.5rem;
        height: 100%;
        width: 100%;
        border-left: 1px solid #000;
        background-color: white; /* Gray background for 'Browse' button */
        color: #000;
        text-align: right;
        line-height: 1.5rem;
        padding-right: 1rem;
        cursor: pointer;
    }

    .jarak {
        margin-top: 50px;
    }

</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-primary">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="card-body">
                    <!-- Foto Profil dan Level Pengguna -->
                    <div class="profile-section">
                        <!-- Hidden input untuk menyimpan path gambar asli -->
                        <input type="hidden" id="original-profile-pic" value="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('user.png') }}">

                        <img id="modal-profile-pic" 
                             src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('user.png') }}" 
                             class="rounded-circle" width="150" height="150" alt="Profile Picture">
                             
                        <!-- Level Pengguna di Bawah Foto Profil -->
                        <div class="user-level mt-3">{{ Auth::user()->level->level_nama }}</div>
                    </div>

                    <form method="POST" action="{{ url('profile/update_profile') }}" 
                          enctype="multipart/form-data" id="profile-form">
                        @csrf
                        <div class="form-group mt-4 text-left">
                            <label for="nama">Nama:</label>
                            <input type="text" class="form-control" id="nama" 
                                   value="{{ Auth::user()->nama }}" disabled>
                        </div>

                        <div class="form-group text-left">
                            <label for="foto_profil">Pilih File:</label>
                            <div class="custom-file">
                                <input type="file" id="foto_profil" name="foto_profil" class="custom-file-input" onchange="previewImage(event)">
                                <label class="custom-file-label" for="foto_profil"></label>
                            </div>
                            <small id="error-foto_profil" class="text-danger"></small>
                        </div>

                        <div class="jarak"></div>
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-warning" data-dismiss="modal" onclick="resetImage()">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    // SweetAlert2: Tampilkan notifikasi "Gambar berhasil diubah"
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
        });
    @endif

    // Preview gambar langsung setelah dipilih tanpa menunggu tombol Simpan
    function previewImage(event) {
        var fileInput = event.target;
        var file = fileInput.files[0];
        var error = document.getElementById('error-foto_profil');
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        var maxSize = 5 * 1024 * 1024; // 5 MB

        error.textContent = ''; // Hapus pesan error sebelumnya

        if (file) {
            // Validasi format file gambar
            if (!allowedExtensions.exec(file.name)) {
                error.textContent = 'Format gambar harus berupa jpeg, png, jpg, atau gif';
                fileInput.value = ''; // Bersihkan input jika format tidak sesuai
                return false;
            }

            // Validasi ukuran file gambar
            if (file.size > maxSize) {
                error.textContent = 'Gambar maksimal 5 MB';
                fileInput.value = ''; // Bersihkan input jika ukuran terlalu besar
                return false;
            }

            // Tampilkan preview gambar secara langsung
            var reader = new FileReader();
            reader.onload = function(e) {
                var output = document.getElementById('modal-profile-pic');
                output.src = e.target.result; // Set gambar preview
            };
            reader.readAsDataURL(file);
        }
    }

    // Kembalikan gambar profil ke versi asli jika modal ditutup atau tombol "Batal" diklik
    function resetImage() {
        var originalPic = document.getElementById('original-profile-pic').value;
        document.getElementById('modal-profile-pic').src = originalPic;
    }

    document.querySelector('.custom-file-input').addEventListener('change', function(e){
    var fileName = document.getElementById("foto_profil").files[0].name;
    var nextSibling = e.target.nextElementSibling;
    nextSibling.innerText = fileName;
});

</script>

