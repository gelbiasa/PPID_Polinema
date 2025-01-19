@extends('layouts.template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">{{ $page->title }}</h3>
                </div>

                <div class="card-body">
                    <!-- Profile Picture Section -->
                    <div class="text-center mb-4">
                        <img id="profile-pic" src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : asset('user.png') }}" class="rounded-circle" width="200" height="200" alt="Profile Picture">
                        <div class="jarak"></div>
                        <div class="mt-2 mb-5">
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editProfileModal">
                                <i class="fas fa-edit"></i> Ganti Foto
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteProfileModal">
                                <i class="fas fa-trash-alt"></i> Hapus Foto
                            </button>                            
                        </div>
                    </div>

                    <!-- Tab Navigation -->
                    <div class="jarak-menu"></div>
                    <ul class="nav nav-menu_profil mb-0" id="profileTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ session('error_type') ? '' : 'active' }}" id="data-tab" data-toggle="tab" href="#data-pengguna" role="tab" aria-controls="data-pengguna" aria-selected="true">Data Pengguna</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ session('error_type') ? 'active' : '' }}" id="password-tab" data-toggle="tab" href="#ubah-password" role="tab" aria-controls="ubah-password" aria-selected="false">Ubah Password</a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="profileTabContent">
                        <!-- Data Pengguna -->
                        <div class="tab-pane fade {{ session('error_type') ? '' : 'show active' }}" id="data-pengguna" role="tabpanel" aria-labelledby="data-tab">
                            <div class="container border-container">
                                <form id="profile-form" method="POST" action="{{ url('profile/update_pengguna', Auth::user()->user_id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group mt-4">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="{{ Auth::user()->username }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama" value="{{ Auth::user()->nama }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_hp">Nomor Handphone</label>
                                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ Auth::user()->no_hp }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="level_nama">Level Pengguna</label>
                                        <input type="text" class="form-control" id="level_nama" value="{{ Auth::user()->level->level_nama }}" disabled>
                                    </div>
                                    <div class="form-group text-right mt-4">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Ubah Password -->
                        <div class="tab-pane fade {{ session('error_type') ? 'show active' : '' }}" id="ubah-password" role="tabpanel" aria-labelledby="password-tab">
                            <div class="container border-container">
                                <form id="password-form" method="POST" action="{{ url('profile/update_password', Auth::user()->user_id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group mt-4">
                                        <label for="current_password">Password Lama</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        </div>
                                        <small id="error-current_password" class="error-text form-text text-danger">
                                            @error('current_password') {{ $message }} @enderror
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label for="new_password">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        </div>
                                        <small id="error-new_password" class="error-text form-text text-danger">
                                            @error('new_password') {{ $message }} @enderror
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label for="confirm_password">Verifikasi Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password" name="new_password_confirmation" required>
                                        </div>
                                        <small id="error-confirm_password" class="error-text form-text text-danger">
                                            @error('new_password_confirmation') {{ $message }} @enderror
                                        </small>
                                    </div>

                                    <div class="form-group text-right mt-4">
                                        <button type="submit" class="btn btn-primary">Ubah Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal for changing profile picture -->
                    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-body">
                                @include('profile.edit_profile')
                            </div>
                        </div>
                    </div>
                    <!-- Modal for deleting profile picture -->
                    <div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-labelledby="deleteProfileModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-body">
                                @include('profile.delete_profile')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.toggle-password').click(function() {
            let input = $($(this).attr("toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });

        @if(session('success'))
            alert('{{ session('success') }}');
        @endif

        @if(session('error_type') === 'current_password')
            alert('Password lama tidak sesuai');
        @endif

        @if(session('error_type') === 'new_password' || session('error_type') === 'new_password_confirmation')
            $('#password-tab').tab('show'); // Aktifkan tab "Ubah Password"
        @endif
    });
</script>


<!-- CSS Styling -->
<style>
    .border-container {
        border: 1px solid black;
        border-radius: 0 10px 10px 10px;
        padding: 20px;
    }

    .nav-menu_profil .nav-link {
        border-radius: 10px 10px 0 0;
        border: 1px solid grey;
        color: black;
        background-color: white;
    }

    .nav-menu_profil .nav-link.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .form-group input {
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
    }

    .jarak {
    margin-top: 20px;
    }

    .jarak-menu {
    margin-top: 80px;
    }
</style>
    
@endsection
