<section id="berita" class="berita section">
    <div class="container mx-auto" data-aos="fade-up" data-aos-delay="100">
        <div class="w-full">
            <div class="container section-title d-flex flex-column align-items-center" data-aos="fade-up">
                <h1 class="font-bold text-black">Berita <span class="text-orange-400">Terkini</span></h1>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-6">
                @if ($news->isEmpty())
                    <div class="col-span-4 text-center">
                        <p class="text-black">Tidak ada berita terbaru saat ini.</p>
                    </div>
                @endif
                @foreach ($news as $category)
                    <a href="{{ route('berita.detail', $category->slug) }}" class="text-center text-black">
                        <div class="relative w-full pb-[100%] rounded-lg overflow-hidden bg-gray-200 mb-2">
                            <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 hover:scale-90"
                                src="{{ isset($category->imageNews) ? asset('storage/' . $category->imageNews[0]['image_path']) : asset('assets/img/avatar/a-sm.jpg') }}"
                                alt="Berita Image">
                        </div>
                        <p class="mt-2 text-sm font-semibold break-words max-w-[200px] mx-auto text-center">
                            {{ $category->title }}
                        </p>
                        <div class="text-xs text-gray-500 bg-orange-50 p-2 rounded-lg w-24 mx-auto">
                            {{ $category->published_at ? \Carbon\Carbon::parse($category->published_at)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
