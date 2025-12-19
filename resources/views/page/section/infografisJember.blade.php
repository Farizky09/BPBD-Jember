<section id="Infografis" class="Infografis section">
    <div class="container mx-auto" data-aos="fade-up" data-aos-delay="100">
        <div class="w-full">
            <div class="container section-title d-flex flex-column align-items-center" data-aos="fade-up">
                <h1 class="font-bold text-black">Infografis <span class="text-orange-400"> Jember</span></h1>
            </div>
            <div class=" max-w-full h-full">
                <div class="swiper mySwiper w-full h-full rounded-xl overflow-hidden relative">
                    <div class="swiper-wrapper">
                        @if ($infografisJember->isEmpty())
                        <div class="text-center p-4 w-full h-full flex justify-center items-center">
                            <h2 class="text-black text-sm md:text-lg font-medium">Tidak ada data infografis Kabupaten Jember</h2>
                        </div>
                            
                        @endif

                        @foreach ($infografisJember as $data)
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
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

    });
</script>
