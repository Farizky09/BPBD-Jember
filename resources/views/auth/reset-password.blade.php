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
            <form id="reset-password-form" method="POST" action="{{ route('reset.password', ['token' => $token]) }}"
                class="space-y-6">
                @csrf
                <h2 class="text-3xl font-bold text-gray-800 text-center">Reset Password</h2>
                <p class="text-gray-600 text-sm text-left">
                    Silakan masukkan password baru Anda. Pastikan gunakan password yang mudah diingat dan jangan
                    beritahu ke siapapun untuk menjaga keamanan akun.
                </p>
                <div class="relative">
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="new_password" id="new_password" placeholder="Masukkan password baru"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        required />

                </div>
                <div class="relative">
                    <label for="confirm_new_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                        Password</label>
                    <input type="password" name="confirm_new_password" id="confirm_new_password"
                        placeholder="Ketik ulang password baru"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        required />

                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md shadow transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com "></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('reset-password-form').addEventListener('submit', function(e) {
            const newPassword = document.querySelector('input[name="new_password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_new_password"]').value;

            if (newPassword !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Password baru dan konfirmasi password tidak cocok.',
                    confirmButtonColor: '#dc3545'
                });
            }
        });
    </script>

</body>

</html>
