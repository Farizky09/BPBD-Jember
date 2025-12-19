<!DOCTYPE html>
<html lang="en">
@include('auth.head')

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css " />
</head>

<body class="bg-blue-100 flex items-center justify-center min-h-screen px-4">
    <div class="w-full max-w-xl bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="text-center py-8 px-6 border-b border-gray-200">
            <div class="flex justify-center mb-4">
                <i class="fas fa-lock text-red-500 text-5xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-red-600">Akun Anda Diban Permanen</h2>
            <p class="text-gray-600 mt-2">
                Maaf, akun Anda tidak dapat mengakses sistem karena pelanggaran ketentuan penggunaan yang serius.
            </p>
        </div>
        <div class="p-6">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3 mt-1"></i>
                    <div>
                        <h5 class="font-semibold text-red-700">Ban Permanen</h5>
                        <p class="text-sm text-red-600">
                            Akun Anda telah diblokir secara permanen dan tidak akan bisa dipulihkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 pb-6 text-center">
            <p class="text-sm text-gray-500">
                Jika Anda merasa ini adalah kesalahan, silakan hubungi tim dukungan kami untuk informasi lebih lanjut.
            </p>
        </div>
        <div class="px-6 pb-6 flex justify-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center px-5 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-sign-out-alt mr-2"></i>
                Kembali ke Halaman Login
            </a>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com "></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2 @11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Akses Ditolak',
                text: 'Akun Anda telah dibanned secara permanen.',
                confirmButtonText: 'Tutup'
            });
        });
    </script>
</body>

</html>
