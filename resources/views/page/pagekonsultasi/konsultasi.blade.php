@include('page.components.head')

<body class="index-page mt-4">
    @include('page.components.header')
    <section id="konsultasi" class="konsultasi section">
        <div class="text-center mb-2 px-4 sm:px-6 lg:px-8 mt-8">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800">Edukasi <span
                    class="text-orange-500">Bencana</span></h1>
            <p class="text-sm sm:text-base text-gray-500 mx-auto max-w-2xl">
                Edukasi ini akan membantu Anda memahami jenis bencana dari pencegahan bencana, penanggulangan bencana dan pasca bencana, serta langkah-langkah pencegahan yang dapat diambil.
            </p>
            <div id="search-container">
                <label class="font-bold text-orange-700 block mt-4 text-base sm:text-lg">💡Jenis Bencana:</label>
                <div class="flex justify-center mt-3 px-4">
                    <div class="relative w-full max-w-md">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 6.65a7.5 7.5 0 010 10.6z" />
                            </svg>
                        </span>
                        <input id="jenis-search" type="text" name="jenis"
                            class="pl-10 pr-4 py-2 w-full h-12 border-2 border-orange-100 bg-orange-50 rounded-lg text-black placeholder-gray-500 focus:outline-none text-center text-sm sm:text-base"
                            placeholder="Masukkan jenis bencana ..." />
                    </div>
                </div>

            </div>

        </div>
        <div class="container mx-auto px-4 sm:px-6 lg:px-8" data-aos="fade-up" data-aos-delay="100">
            <div
                class="px-3 border-b grid grid-cols-2 sm:flex sm:flex-wrap justify-center items-center gap-2 sm:gap-4 border-gray-200">
                <button id="sebelumBencanaBtn"
                    class="tab-btn text-xs sm:text-sm m-1 sm:m-3 text-orange-600 hover:text-orange-800 font-medium focus:outline-none border-b-2 border-transparent py-2 px-3 sm:py-3 sm:px-4">
                    Pencegahan Bencana
                </button>
                <button id="saatBencanaBtn"
                    class="tab-btn text-xs sm:text-sm m-1 sm:m-3 text-orange-600 hover:text-orange-800 font-medium focus:outline-none border-b-2 border-transparent py-2 px-3 sm:py-3 sm:px-4">
                    penanggulangan Bencana
                </button>
                <button id="setelahBencanaBtn"
                    class="tab-btn text-xs sm:text-sm m-1 sm:m-3 text-orange-600 hover:text-orange-800 font-medium focus:outline-none border-b-2 border-transparent py-2 px-3 sm:py-3 sm:px-4">
                    Pasca Bencana
                </button>
                <button id="konsultasiAiBtn"
                    class="tab-btn text-xs sm:text-sm m-1 sm:m-3 text-orange-600 hover:text-orange-800 font-medium focus:outline-none border-b-2 border-transparent py-2 px-3 sm:py-3 sm:px-4">
                    Edukasi Foto dengan Ai
                </button>
            </div>
            <form id="sebelumBencanaForm" class="w-full py-4 relative min-h-[300px]">
                <div class="w-full" id="before-bencana-results">
                    {{-- di isi sama ajax dibawa --}}
                </div>
                <div id="not-found-before" class="hidden text-center text-gray-500 mt-10">
                    <p class="text-sm">😕 Tidak ada data edukasi.</p>
                </div>
            </form>

            <form id="saatBencanaForm" class="hidden w-full py-4 relative min-h-[300px]">
                <div class="w-full" id="during-bencana-results">
                    {{-- di isi sama ajax dibawa --}}

                </div>
                <div id="not-found-during" class="hidden text-center text-gray-500 mt-10">
                    <p class="text-sm">😕 Tidak ada data edukasi.</p>
                </div>
            </form>

            <form id="setelahBencanaForm" class="hidden w-full py-4 relative min-h-[300px]">
                <div class="w-full" id="after-bencana-results">
                    {{-- di isi sama ajax dibawa --}}
                </div>
                <div id="not-found-after" class="hidden text-center text-gray-500 mt-10">
                    <p class="text-sm">😕 Tidak ada data edukasi.</p>
                </div>
            </form>


            @include('page.pagekonsultasi.konsultasiAi')
        </div>
    </section>
    @include('page.components.footer')
    <script src="{{ asset('assets/js/edukasi-landinpage.js') }}"></script>
</body>
