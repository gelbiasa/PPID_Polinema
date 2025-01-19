<style>
    /* Styling similar to edit_profile */
    .card.border-danger {
        border: 2px solid red;
        border-radius: 10px;
    }

    .form-group label {
        text-align: left;
        display: block;
        margin-bottom: 5px;
    }

    .user-level {
        background-color: blue;
        color: white;
        display: inline-block;
        padding: 5px 15px;
        border-radius: 10px;
        margin-top: 10px;
    }

    .profile-section {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
        margin-bottom: 50px;
    }

    .jarak {
        margin-top: 50px;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProfileModalLabel">Hapus Foto Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="card-body">
                    <!-- Foto Profil dan Level Pengguna -->
                    <div class="profile-section">
                        <img id="modal-profile-pic" 
                             src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('user.png') }}" 
                             class="rounded-circle" width="150" height="150" alt="Profile Picture">
                             
                        <div class="user-level mt-3">{{ Auth::user()->level->level_nama }}</div>
                    </div>

                    <form method="POST" action="{{ url('profile/delete_profile') }}" id="delete-profile-form">
                        @csrf
                        @method('DELETE')
                        <div class="form-group mt-4 text-left">
                            <label for="nama">Nama:</label>
                            <input type="text" class="form-control" id="nama" 
                                   value="{{ Auth::user()->nama }}" disabled>
                        </div>

                        <div class="jarak"></div>
                        <div class="form-group text-right">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('success') }}',
        });
    @endif
</script>
