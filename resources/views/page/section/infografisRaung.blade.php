<section id="Infografis" class="Infografis section">

    <div class="container mx-auto" data-aos="fade-up" data-aos-delay="100">

        <div class="w-full">

            <div class="container section-title d-flex flex-column align-items-center" data-aos="fade-up">

                <h1 class="font-bold text-black">Infografis <span class="text-orange-400">Gunung
                        Raung</span></h1>

            </div>

            <div class=" max-w-full h-full">

                <div class="swiper mySwiper w-full h-full rounded-xl overflow-hidden relative">

                    <div class="swiper-wrapper">

                        @if ($infografisRaung->isEmpty())
                            <div class="text-center p-4 w-full h-full flex justify-center items-center">

                                <p class="text-black text-sm md:text-lg font-medium">Tidak
                                    ada data infografis Gunung Raung</p>

                            </div>
                        @endif



                        @foreach ($infografisRaung as $data)
                            <div class="swiper-slide relative w-full h-[180px] md:h-[200px]">

                                <img class="w-full h-full object-cover" src="{{ asset('storage/' . $data->image) }}"
                                    alt="Infografis Jember {{ $loop->iteration }}">

                            </div>
                        @endforeach

                    </div>

                    <div class="swiper-pagination"></div>

                    <div class="swiper-button-next text-white text-sm md:text-sm"></div>

                    <div class="swiper-button-prev text-white text-sm md:text-sm"></div>

                </div>

            </div>

        </div>

    </div>

</section>
