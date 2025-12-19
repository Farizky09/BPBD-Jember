    <!DOCTYPE html>
<html lang="en">
@include('auth.head')

<body>
    <div class="limiter">
        <div class="container p-5 bg-primary rounded shadow d-flex justify-content-center align-items-center"
            style="max-width: 100%; height: 100vh;">
            <div class="bg-white " style=" width: 650px;">
                <a href="{{ route('login') }}">
                    <div class="text-center mt-4 ml-4 bg-primary d-flex justify-content-center align-items-center"
                        style="width: 40px; height: 40px; border-radius: 100%;">
                        <b class="p text-white" style="font-size: 20px;">
                            <
                    </div>
                </a>
                <div class="text-center mt-4">
                    <h2>Ganti Password</h2>
                    <p>Silakan masukkan password baru Anda untuk memperbarui akun Anda.</p>
                </div>
                <div class="" style="padding: 20px 50px 70px 50px;">
                    <form action="{{ route('user.update-password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="password" class="form-label">Masukkan Password baru</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary px-5">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('auth.footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->has('password'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Password Terlalu Pendek',
                text: '{{ $errors->first('password') }}',
                confirmButtonColor: '#3085d6'
            });
        </script>
    @endif

</body>

</html>
