<!DOCTYPE html>
<html lang="en">
@include('auth.head')

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css " />
</head>

<body class="bg-blue-100 flex items-center justify-center h-screen px-4 py-8 md:py-0">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
        {{-- <div class="flex justify-center bg-gradient-to-br from-blue-50 to-indigo-50 py-3 pb-2">
            <dotlottie-player src="https://lottie.host/4b99d19e-2342-4e8f-a06d-7a30b666a96b/OSJesewnTf.lottie "
                background="transparent" speed="1" style="width: 200px; height: 200px" loop
                autoplay></dotlottie-player>
        </div> --}}
        <form method="POST" action="{{ route('login') }}" class="p-6 md:p-8 space-y-6">
            @csrf

            <h2 class="text-2xl font-bold text-center text-gray-800">Masuk untuk Lanjutkan</h2>
            <div class="text-center">
                <a href="{{ route('socialite.redirect') }}"
                    class="inline-flex items-center justify-center w-full gap-3 bg-white border border-gray-300 rounded-lg px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M23.766 12.276c0-.816-.066-1.636-.207-2.438H12.24v4.621h6.482a5.554 5.554 0 0 1-2.395 3.647v2.998h3.868c2.27-2.09 3.575-5.177 3.575-8.828z"
                            fill="#4285F4" />
                        <path
                            d="M12.24 24c3.237 0 5.966-1.063 7.955-2.897l-3.867-3.098c-1.076.732-2.465 1.146-4.083 1.146-3.13 0-5.784-2.112-6.737-4.952h-3.99v3.09a12.002 12.002 0 0 0 10.722 6.711z"
                            fill="#34A853" />
                        <path
                            d="M5.503 14.3a7.188 7.188 0 0 1 0-4.594V6.615H1.517a12.01 12.01 0 0 0 0 10.776l3.986-3.09z"
                            fill="#FBBC04" />
                        <path
                            d="M12.24 4.75c1.71-.026 3.364.624 4.603 1.806l3.426-3.426C18.1.085 15.22-.034 12.24 0 7.703 0 3.554 2.558 1.517 6.615l3.987 3.09C6.45 6.862 9.11 4.75 12.24 4.75z"
                            fill="#EA4335" />
                    </svg>
                    <span>lanjutkan dengan Google</span>
                </a>
            </div>

            <div class="flex items-center justify-between">
                <hr class="w-full border-gray-300">
                <span class="text-sm text-gray-500 mx-2">atau</span>
                <hr class="w-full border-gray-300">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input id="email" name="email" type="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">

            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">

            </div>
            <div class="flex items-center justify-end">
                <a href="{{ route('forgot.password') }}" class="text-sm text-blue-600 hover:underline">Lupa Password?</a>
            </div>
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-md shadow transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Masuk
            </button>
            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    Tidak punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:underline">Buat akun</a>
                </p>
            </div>
        </form>
    </div>
    @include('auth.footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->has('email') || $errors->has('password'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email atau password salah.',
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Coba Lagi'
            });
        </script>
    @endif

    <script src="https://cdn.tailwindcss.com "></script>

    <script src="https://unpkg.com/ @dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
</body>

</html>
