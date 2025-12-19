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
            <form action="{{ route('login-otp.verify') }}" method="POST" class="space-y-6">
                @csrf
                <h2 class="text-3xl font-bold text-gray-800 text-center">Masukkan Kode OTP Anda</h2>
                <div class="relative">
                    <input type="number" name="otp" id="otp"
                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        required />
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md shadow transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Verifikasi OTP
                </button>
                <div class="text-center p-t-12">
                    <a id="resendLink" class="txt2" href="{{ route('login-otp.resend') }}"
                        style="pointer-events: none; opacity: 0.5;">
                        Kirim ulang kode OTP! (<span id="countdown">60</span>)
                    </a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com "></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = 60;
            const countdownElement = document.getElementById('countdown');
            const resendLink = document.getElementById('resendLink');

            const timer = setInterval(function() {
                timeLeft--;
                countdownElement.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(timer);
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.style.opacity = '1';
                    resendLink.innerHTML = 'Kirim ulang kode OTP!';
                }
            }, 1000);
        });
        @if ($errors->has('otp'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ $errors->first('otp') }}',
            });
        @endif

    </script>

</body>

</html>
