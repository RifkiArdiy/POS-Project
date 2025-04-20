@extends('layouts.template')

@section('content')
<section class="content">
    <div class="container">
        <div class="card border-0 rounded-4 shadow-lg p-4 bg-glass text-light mx-auto" style="max-width: 600px;">
            <div class="card-body text-center">
                
                <!-- Avatar -->
                <div class="position-relative d-inline-block mb-4">
                    <img src="{{ asset('storage/' . ($user->foto_profile ?? 'uploads/profile/default-profile.jpg')) }}"
                        alt="Profile Picture"
                        class="rounded-circle shadow avatar-img border border-light border-3"
                        width="130" height="130" id="profile-picture">

                    <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data" id="photo-form">
                        @csrf
                        <label for="foto_profile"
                        class="position-absolute bottom-0 end-0 bg-dark text-dark rounded-circle shadow"
                        style="transform: translate(30%, 30%); cursor: pointer;">
                        <i class="fas fa-pen"></i>
                            <input type="file" name="foto_profile" id="foto_profile" hidden accept="image/*">
                        </label>
                    </form>
                </div>
                

                <!-- User Info -->
                <h3 class="fw-bold mb-1">{{ $user->nama }}</h3>
                <p class="text-muted mb-4">{{ $user->getRoleName() }}</p>

                <div class="text-start px-3 mb-4">
                    <div class="d-flex justify-content-between py-2 border-bottom border-light-subtle">
                        <span><i class="fas fa-user me-2 text-info"></i>Username</span>
                        <span>{{ $user->username }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span><i class="fas fa-calendar-alt me-2 text-info"></i>Bergabung</span>
                        <span>{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- Button -->
                <button class="btn btn-outline-light px-5 rounded-pill fw-semibold" id="btn-edit-profile">
                    <i class="fas fa-edit me-2"></i> Edit Profil
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-body p-0" id="editProfileModalContent"></div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
<style>
    body {
        background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
        background-attachment: fixed;
    }

    .bg-glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 1rem;
    }

    .avatar-img {
        transition: transform 0.3s ease;
    }

    .avatar-img:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    }

    .form-control:disabled {
        background: transparent;
        color: #fff;
    }
</style>
@endpush

@push('js')
<script>
    $(function () {
        // Upload foto AJAX
        $('#foto_profile').change(function () {
            const form = $('#photo-form')[0];
            const formData = new FormData(form);

            if (this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => $('#profile-picture').attr('src', e.target.result);
                reader.readAsDataURL(this.files[0]);

                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    },
                    error(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Gagal update foto.'
                        });
                    }
                });
            }
        });

        // Load form edit via AJAX
        $('#btn-edit-profile').click(function () {
            $('#editProfileModalContent').html('<div class="text-center py-5"><div class="spinner-border text-light"></div></div>');
            $('#editProfileModal').modal('show');

            $.get('{{ route('profile.edit') }}')
                .done(html => $('#editProfileModalContent').html(html))
                .fail(() => {
                    $('#editProfileModal').modal('hide');
                    toastr.error('Gagal memuat form.');
                });
        });
    });
</script>
@endpush
