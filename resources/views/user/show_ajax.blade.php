@empty($user)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger alert-dismissible">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                Data yang Anda cari tidak ditemukan.
            </div>
            <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Detail Data Pengguna</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>ID Pengguna</th>
                    <td>{{ $user->user_id }}</td>
                </tr>
                <tr>
                    <th>Profile Pengguna</th>
                    <td>
                        <img src="{{ asset('storage/' . ($user->foto_profile ?? '/uploads/profile/default-profile.jpg')) }}"
                            class="img-thumbnail"
                            style="width: 100px; height: 100px; object-fit: cover;">
                    </td>
                </tr>
                <tr>
                    <th>Username Pengguna</th>
                    <td>{{ $user->username }}</td>
                </tr>
                <tr>
                    <th>Nama Pengguna</th>
                    <td>{{ $user->nama }}</td>
                </tr>
                <tr>
                    <th>Level Pengguna</th>
                    <td>{{ $user->level->level_nama }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-warning">Tutup</button>
        </div>
    </div>
</div>
@endempty