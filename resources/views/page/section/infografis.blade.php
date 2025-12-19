<section id="Infografis" class="Infografis section py-6">
    <div class="container mx-auto" data-aos="fade-up" data-aos-delay="100">
        <div class="section-title text-center mb-6" data-aos="fade-up">
            <h1 class="font-bold text-black text-2xl md:text-3xl">Data <span class="text-orange-400">Infografis</span>
            </h1>
        </div>

        <div class="flex flex-col md:flex-row gap-6">

            <div class="w-full md:w-1/2">
                <h2 class="text-lg font-bold text-center mb-2">Infografis Bulanan</h2>
                <div class="swiper swiper-jember w-full  rounded-xl overflow-hidden relative">
                    <div class="swiper-wrapper">
                        @if ($infografisJember->isEmpty())
                            <div class="text-center p-4 w-full h-full flex justify-center items-center">
                                <p class="text-black text-sm md:text-base font-medium">Tidak ada data infografis 
                                </p>
                            </div>
                        @endif
                        @foreach ($infografisJember as $data)
                            <div class="swiper-slide relative w-full h-full">
                                <img class="w-full h-full object-cover" src="{{ asset('storage/' . $data->image) }}"
                                    alt="Infografis Jember {{ $loop->iteration }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination jember-pagination"></div>
                    <div class="swiper-button-next jember-next text-white"></div>
                    <div class="swiper-button-prev jember-prev text-white"></div>
                </div>
            </div>
            
            <div class="w-full md:w-1/2">
                <h2 class="text-lg font-bold text-center mb-2">Infografis Bencana</h2>
                <div class="swiper swiper-raung w-full h-full rounded-xl overflow-hidden relative">
                    <div class="swiper-wrapper">
                        @if ($infografisRaung->isEmpty())
                            <div class="text-center p-4 w-full h-full flex justify-center items-center">
                                <p class="text-black text-sm md:text-base font-medium">Tidak ada data infografis</p>
                            </div>
                        @endif
                        @foreach ($infografisRaung as $data)
                            <div class="swiper-slide relative w-full h-full">
                                <img class="w-full h-full object-cover" src="{{ asset('storage/' . $data->image) }}"
                                    alt="Infografis Raung {{ $loop->iteration }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="swiper-pagination raung-pagination"></div>
                    <div class="swiper-button-next raung-next text-white"></div>
                    <div class="swiper-button-prev raung-prev text-white"></div>
                </div>
            </div>


        </div>
    </div>
</section>


<script src="{{ asset('assets/js/section/infografis.js') }}"></script>