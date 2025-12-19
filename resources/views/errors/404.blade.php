<!DOCTYPE html>
<html lang="en">
@include('auth.head')

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css " />
</head>

<body class="bg-blue-100 min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-lg bg-white rounded-xl shadow-lg overflow-hidden mx-auto">

        <a href="{{ route('page.home') }}" class="m-6 inline-block">

        </a>
        <div class="p-8 md:p-10">
            <form id="403" class="space-y-6">
                @csrf
                <h1 class="text-9xl font-bold text-gray-800 text-center">404</h1>
                <p class="text-gray-600 text-xl text-center" >
                    Halaman tidak tersedia
                </p>

                <a href="{{ route('page.home') }}"
               class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-md shadow transition duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Kembali
            </a>
            </form>
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com "></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>
