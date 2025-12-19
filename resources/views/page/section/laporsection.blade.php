<section id="lapor" class="laport section">
    <div class="container section-title d-flex flex-column align-items-center" data-aos="fade-up">
        <h1 class="font-bold text-black">Lapor <span class="text-orange-400">Bencana</span></h1>
    </div>
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="d-flex items-center justify-center text-center py-6">
            <h1 class="max-w-lg">Bantu kami memantau dan mengatasi bencana dengan laporan Anda.</h1>
        </div>
        <div class="d-flex items-center justify-center">
            <x-button.button-submit onclick="checkLogin()" class="px-5 py-2.5">
                {{ Auth::check() ? 'Lapor' : 'Mulai Untuk Coba' }}
            </x-button.button-submit>
        </div>
        <div class="d-flex items-center justify-center text-center py-6">
            <p class="max-w-lg">Laporkan bencana di sekitar Anda dengan mudah dan cepat.</p>
        </div>
    </div>
</section>

<script>
    function checkLogin() {
        @if(Auth::check())
            window.location.href = "{{ route('page.lapor') }}";
        @else
            Swal.fire({
                title: 'Login Diperlukan',
                text: "Anda harus login terlebih dahulu untuk melanjutkan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Login',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    window.location.href = '/login?redirect_url={{ urlencode(route('reports.create')) }}';
                }
            });
       @endif
    }
</script>
