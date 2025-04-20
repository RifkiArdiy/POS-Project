<div class="modal-header">
    <h5 class="modal-title">Edit Profil</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<form id="update-profile-form" class="p-4">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="nama">Nama Lengkap</label>
        <input type="text" name="nama" id="nama" class="form-control" value="{{ $currentUser->nama }}" required>
    </div>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control" value="{{ $currentUser->username }}" required>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#update-profile-form').submit(function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: '{{ route('profile.update') }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: res.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors;
                let err = xhr.responseJSON?.message;

                if (errors) {
                    let errorMessages = '';
                    $.each(errors, function(key, value) {
                        errorMessages += value[0] + '<br>';
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMessages
                    });
                } else if (err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: err
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan.'
                    });
                }
            }
        });
    });
});
</script>
