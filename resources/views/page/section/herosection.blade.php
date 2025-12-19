<section id="hero" class="hero dark-background relative w-full h-[60vh] md:h-screen overflow-hidden">
    <div class="swiper heroSwiper w-full h-full absolute inset-0">
        <div class="swiper-wrapper">
            @forelse ($informasiBPBD as $item)
                <div class="swiper-slide relative">
                    <div class="absolute inset-0 w-full h-full">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                            class="w-full h-full object-cover object-center" />
                    </div>
                    <div class="absolute inset-0 bg-black/30 z-[1]"></div>

                    <div class="container relative z-[2] h-full flex items-end px-4 sm:px-6 lg:px-8 pb-16 md:pb-24">
                        <div class="w-full md:w-2/3 text-white" data-aos="fade-in">
                            <h4 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-2 md:mb-4">
                                {{ $item->name }}</h4>
                            <p class="text-xl sm:text-xl md:text-xl lg:text-xl font-bold mb-2 md:mb-4">
                                {{ $item->created_at->format('d F Y') }}</p>
                            <div class="mt-4 md:mt-6">
                                <button onclick="checkLogin()"
                                    class="py-2 px-6 sm:py-2.5 sm:px-8 md:px-14 text-xs sm:text-sm md:text-base text-white backdrop-blur-md bg-white/10 border border-white/20 rounded-lg shadow-md hover:bg-white/20 transition">
                                    {{ Auth::check() ? 'Lapor Sekarang' : 'Mulai Untuk Melapor' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="swiper-slide relative">
                    <div class="absolute inset-0 w-full h-full">
                        <img src="{{ asset('assets/img/bpbd.png') }}" alt="Default Background"
                            class="w-full h-full object-cover object-center" />
                    </div>
                    <div class="absolute inset-0 bg-black/30 z-[1]"></div>

                    <div
                        class="container relative z-[11] h-full flex items-end px-4 sm:px-6 lg:px-8 pb-16 md:pb-24">
                        <div class="w-full md:w-1/2 text-white" data-aos="fade-in">
                            <p class="text-sm sm:text-base md:text-lg font-bold mb-2 md:mb-4">
                                SELAMAT DATANG DI WEBSITE RESMI
                            </p>
                            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-4">
                                BPBD KAB. JEMBER
                            </h1>
                            <div class="mt-4 md:mt-6">
                                <button onclick="checkLogin()"
                                    class="py-2 px-6 sm:py-2.5 sm:px-8 md:px-14 text-xs sm:text-sm md:text-base text-white backdrop-blur-md bg-white/10 border border-white/20 rounded-lg shadow-md hover:bg-white/20 transition">
                                    {{ Auth::check() ? 'Lapor Sekarang' : 'Mulai Untuk Melapor' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        </div>

    <svg class="hero-waves absolute bottom-0 left-0 w-full h-[70px] sm:h-[100px] md:h-[150px] z-10"
        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28"
        preserveAspectRatio="none">
        <defs>
            <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"></path>
        </defs>
        <g class="wave1">
            <use xlink:href="#wave-path" x="50" y="3"></use>
        </g>
        <g class="wave2">
            <use xlink:href="#wave-path" x="50" y="0"></use>
        </g>
        <g class="wave3">
            <use xlink:href="#wave-path" x="50" y="9"></use>
        </g>
    </svg>
</section>

<script>
    function checkLogin() {
        @if (Auth::check())
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

<script src="{{ asset('assets/js/section/hero.js') }}"></script>
