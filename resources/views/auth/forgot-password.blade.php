<!DOCTYPE html>
<html lang="en">
@include('auth.head')

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css " />
</head>

<body class="bg-blue-100 min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg overflow-hidden mx-auto">

        <a href="{{ route('login') }}" class="m-6 inline-block">
            <div
                class="flex items-center justify-center w-10 h-10 bg-blue-600 rounded-full hover:bg-blue-700 transition">
                <span class="text-white font-bold text-xl">&larr;</span>
            </div>
        </a>
        <div class="p-8 md:p-10">
            <form id="reset-password-form" method="POST" class="space-y-6">
                @csrf
                <h2 class="text-3xl font-bold text-gray-800 text-center">Lupa Password</h2>
                <p class="text-gray-600 text-sm text-left">
                    Kami akan mengirimkan link untuk melakukan reset password kepada email Anda.
                </p>
                <div class="relative">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" placeholder="contoh@email.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        required />
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md shadow transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kirim Link Reset
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com "></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('reset-password-form').addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Memproses...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    let url = '{{ route('send.reset.link') }}';
                    let formData = new FormData(this);

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Silakan cek kotak masuk email Anda.',
                                    confirmButtonColor: '#3085d6'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message ||
                                        'Tidak dapat mengirim link reset.',
                                    confirmButtonColor: '#dc3545'
                                });
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Kesalahan!',
                                text: 'Terjadi kesalahan dalam mengirimkan link reset.',
                                confirmButtonColor: '#dc3545'
                            });
                        });
                }
            });
        });
    </script>

</body>

</html>
