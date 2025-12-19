@include('page.components.head')


<body class="index-page mt-5">
    @include('page.components.header')
    <section id="detail" class="detail section py-10 bg-gray-50">
        <div class="container mx-auto px-4 md:px-6" data-aos="fade-up">
            <!-- Section Title -->
            <div class="text-center mb-10">
                <h1 class="font-bold text-3xl md:text-4xl text-black relative pb-3">
                    Detail<span class="text-blue-600"> Bencana</span>
                    <span class="block h-1 w-20 bg-blue-600 mt-2 mx-auto rounded-full"></span>
                </h1>
            </div>

            <!-- Grid Layout untuk Desktop -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <!-- Carousel Gambar & Deskripsi -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Carousel -->
                    <div id="default-carousel" class="relative w-full border rounded-xl overflow-hidden"
                        data-carousel="slide">
                        <div class="relative h-64 md:h-[500px] overflow-hidden rounded-t-xl" id="carousel-container">
                            @if ($news->imageNews && count($news->imageNews) > 0)
                                @foreach ($news->imageNews as $index => $image)
                                    <div class="{{ $index === 0 ? '' : 'hidden' }} duration-700 ease-in-out"
                                        data-carousel-item>
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            class="w-full h-full object-cover" alt="Image {{ $index + 1 }}">
                                    </div>
                                @endforeach
                            @else
                                <div class="flex items-center justify-center h-full bg-gray-100">
                                    <svg class="w-16 h-16 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Indicators -->
                        @if ($news->imageNews && count($news->imageNews) > 1)
                            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse"
                                id="carousel-indicators">
                                @foreach ($news->imageNews as $index => $image)
                                    <button type="button"
                                        class="w-3 h-3 rounded-full {{ $index === 0 ? 'bg-blue-600' : 'bg-gray-300' }} hover:bg-blue-500"
                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"
                                        data-carousel-slide-to="{{ $index }}"></button>
                                @endforeach
                            </div>
                        @endif

                        <!-- Controls -->
                        @if ($news->imageNews && count($news->imageNews) > 1)
                            <button type="button"
                                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                data-carousel-prev>
                                <span
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-black/30 group-hover:bg-black/50 group-focus:ring-4 group-focus:ring-white">
                                    <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 1L1 5l4 4" />
                                    </svg>
                                </span>
                            </button>
                            <button type="button"
                                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                data-carousel-next>
                                <span
                                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-black/30 group-hover:bg-black/50 group-focus:ring-4 group-focus:ring-white">
                                    <svg class="w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 9l4-4-4-4" />
                                    </svg>
                                </span>
                            </button>
                        @endif
                    </div>

                    <!-- Deskripsi -->
                    <div class="bg-slate-50 p-6 rounded-xl border">
                        <h1 class="font-bold text-2xl text-gray-800 mb-3">{{ $news->title }}</h1>
                        <div class="flex items-center mb-4">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-2">
                                {{ $news->confirmReports->report->disasterCategory->name }}
                            </span>
                            <span class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($news->created_at)->translatedFormat('d F Y') }}
                            </span>
                        </div>
                        <p id="text-description" class="mt-2 text-justify text-gray-700 leading-relaxed">
                            {!! $news->content !!}
                        </p>
                    </div>

                    <!-- Komentar -->
                    <div class="bg-white p-6 rounded-xl border">
                        <h4 class="font-semibold text-lg text-gray-800 mb-4">Komentar</h4>
                        <div id="comments"
                            class="space-y-4 max-h-96 overflow-y-auto pr-2 scrollbar-thin scrollbar-thumb-blue-400 scrollbar-track-gray-200">
                            @forelse ($comments as $comment)
                                <div class="p-4 border border-gray-100 rounded-lg bg-white transition-shadow">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100">
                                            @if ($comment->user->image_avatar)
                                                <img src="{{ asset('storage/' . $comment->user->image_avatar) }}"
                                                    alt="Foto Profil"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-blue-600 font-semibold">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>

                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $comment->user->name }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ $comment->created_at->diffForHumans() }}</p>
                                            <p class="mt-1 text-gray-600">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">Belum ada komentar.</p>
                            @endforelse
                        </div>

                        @auth
                            <form id="commentForm" class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                @csrf
                                <input type="hidden" name="news_id" value="{{ $news->id }}">
                                <textarea name="content" id="comment-text" rows="3"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Tulis komentar Anda..." required></textarea>
                                <div class="mt-4 flex justify-center">
                                    <x-button.button-submit type="submit" class="px-3 py-2.5">Kirim
                                        Komentar</x-button.button-submit>

                                </div>
                            </form>
                        @else
                            <div class="mt-4 text-center text-gray-500">
                                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> untuk
                                menambahkan komentar.
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Berita Lainnya (Vertical Scroll) -->
                @if (!empty(strip_tags($news->content)))
                    @php
                        $filteredAllNews =
                            isset($news) && $news
                                ? $allNews->filter(fn($item) => $item->slug !== $news->slug)
                                : $allNews;
                    @endphp

                    @if ($filteredAllNews->isNotEmpty())
                        <div class="lg:col-span-2 lg:col-start-4 lg:row-span-4">
                            <h3 class="font-semibold text-lg mb-3 text-gray-700">Berita Bencana Lainnya</h3>
                            <div id="vertical-scrolled"
                                class="h-[600px] overflow-y-auto pr-2 space-y-4 scrollbar-thin scrollbar-thumb-blue-500 scrollbar-track-gray-200">
                                @foreach ($filteredAllNews as $item)
                                    <div class="bg-slate-100 border rounded p-4 hover:bg-gray-50 cursor-pointer transition-colors"
                                        onclick="loadNews('{{ $item->slug }}', {{ json_encode($item) }})">
                                        @if ($item->imageNews && count($item->imageNews))
                                            <div class="w-24 h-24 overflow-hidden rounded float-left mr-4">
                                                <img src="{{ asset('storage/' . $item->imageNews[0]->image_path) }}"
                                                    alt="Gambar" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-md font-semibold">{!! Str::limit(strip_tags($item->title), 20) !!}</p>
                                            <p class="text-gray-700">{!! Str::limit(strip_tags($item->content), 30) !!}</p>
                                            <small
                                                class="text-gray-500">{{ $item->created_at->format('d M Y') }}</small>
                                        </div>
                                        <div class="clear-both"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div
                            class="lg:col-span-2 lg:col-start-4 lg:row-span-4 flex items-center justify-center h-[600px] bg-slate-100 rounded shadow">
                            <div class="text-center text-gray-500">
                                <svg class="mx-auto mb-3 w-10 h-10 text-gray-400" fill="none"
                                    stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v.75a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 15v-.75m15 0a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 14.25m15 0v-1.5a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 12.75v1.5m0 0v.75m0-7.5V6a2.25 2.25 0 012.25-2.25h10.5A2.25 2.25 0 0119.5 6v1.5">
                                    </path>
                                </svg>
                                <p class="text-lg font-semibold">Belum ada berita tersedia</p>
                            </div>
                        </div>
                    @endif

                @endif
            </div>


        </div>
    </section>
    @include('page.components.footer')
    <script>
        window.loadNews = function(slug) {
            window.location.href = routes.beritaDetail.replace('__slug__', slug);
        }
        window.routes = {
            beritaDetail: "{{ route('berita.detail', ['slug' => '__slug__']) }}",
            home: "{{ route('page.home') }}"
        };

        const htmlContent = document.getElementById('news-content-template').innerHTML;

        function injectDescription() {
            const desktopDesc = document.getElementById('text-description');
            const mobileDesc = document.getElementById('mobile-text-description');

            if (desktopDesc) desktopDesc.innerHTML = htmlContent;
            if (mobileDesc) mobileDesc.innerHTML = htmlContent;
        }

        function injectDate() {
            const dateElement = document.getElementById('date');
            if (!dateElement) return;

            const newsDate = new Date('{{ $news->created_at }}');
            const options = {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };
            dateElement.textContent = newsDate.toLocaleDateString('id-ID', options);
        }

        window.toggleComments = function() {
            const section = document.getElementById('comment-section');
            if (section) section.classList.toggle('hidden');
        }

        function updateCarousel(images, type = 'desktop') {
            const prefix = type === 'desktop' ? '' : 'mobile-';
            const container = document.getElementById(`${prefix}carousel-container`);
            const indicators = document.getElementById(`${prefix}carousel-indicators`);

            if (!container) return;
            container.innerHTML = '';
            if (indicators) indicators.innerHTML = '';

            if (!images || images.length === 0) {
                container.innerHTML = `
                <div class="flex items-center justify-center h-full bg-gray-200">
                    <svg class="w-12 h-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>`;
                return;
            }

            images.forEach((image, index) => {
                const imgDiv = document.createElement('div');
                imgDiv.className = index === 0 ? 'duration-700 ease-in-out' : 'hidden duration-700 ease-in-out';
                imgDiv.setAttribute('data-carousel-item', '');
                imgDiv.innerHTML = `
                <img src="/storage/${image.image_path}" class="w-full h-full object-cover" alt="Image ${index + 1}">
            `;
                container.appendChild(imgDiv);

                if (indicators) {
                    const indicator = document.createElement('button');
                    indicator.type = 'button';
                    indicator.className =
                        `w-3 h-3 rounded-full ${index === 0 ? 'bg-black' : 'bg-black/50'} hover:bg-black`;
                    indicator.setAttribute('aria-current', index === 0 ? 'true' : 'false');
                    indicator.setAttribute('aria-label', `Slide ${index + 1}`);
                    indicator.setAttribute('data-carousel-slide-to', index);
                    indicators.appendChild(indicator);
                }
            });

            const carouselElement = container.closest('[data-carousel]');
            if (!carouselElement) return;

            const prevButton = carouselElement.querySelector('[data-carousel-prev]');
            const nextButton = carouselElement.querySelector('[data-carousel-next]');

            if (images.length > 1) {
                if (prevButton) prevButton.classList.remove('hidden');
                if (nextButton) nextButton.classList.remove('hidden');
            } else {
                if (prevButton) prevButton.classList.add('hidden');
                if (nextButton) nextButton.classList.add('hidden');
            }
        }

        function initCarousel(id) {
            const carouselEl = document.getElementById(id);
            if (!carouselEl) return;

            const carousel = new Carousel(carouselEl, {
                defaultPosition: 0,
                interval: 3000,
                indicators: {
                    activeClasses: 'bg-black',
                    inactiveClasses: 'bg-black/50 hover:bg-black',
                    items: Array.from(carouselEl.querySelectorAll('[data-carousel-slide-to]'))
                }
            });

            const indicators = carouselEl.querySelectorAll('[data-carousel-slide-to]');
            indicators.forEach(indicator => {
                indicator.addEventListener('click', () => {
                    const slideTo = parseInt(indicator.getAttribute('data-carousel-slide-to'));
                    carousel.slideTo(slideTo);
                });
            });

            const prevButton = carouselEl.querySelector('[data-carousel-prev]');
            const nextButton = carouselEl.querySelector('[data-carousel-next]');

            if (prevButton) {
                prevButton.addEventListener('click', () => carousel.prev());
            }

            if (nextButton) {
                nextButton.addEventListener('click', () => carousel.next());
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            injectDescription();
            injectDate();

            initCarousel('default-carousel');
            initCarousel('mobile-carousel');
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const allComments = @json($comments);
        let currentPage = 1;
        const perPage = 3;
        const totalPages = Math.ceil(allComments.length / perPage);

        function updatePagination() {
            const start = (currentPage - 1) * perPage;
            const end = start + perPage;
            const pageComments = allComments.slice(start, end);
            const container = document.getElementById('comments-container');
            if (!container) return;

            container.innerHTML = '';

            if (pageComments.length === 0) {
                container.innerHTML = `
                <div class="text-center text-gray-500 py-4">Belum ada komentar</div>`;
                return;
            }

            pageComments.forEach(comment => {
                const commentElement = document.createElement('div');
                commentElement.className =
                    'comment-item p-4 mb-2 border border-gray-100 rounded-lg bg-white shadow-sm hover:shadow-md transition-shadow';

                commentElement.innerHTML = `
                <div class="flex items-start gap-3 mb-0">
                    <div class="w-8 h-8 rounded-full mt-1 bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-semibold">${comment.user.name.charAt(0)}</span>
                    </div>
                    <div>
                        <p class="font-semibold mb-0 text-gray-800">${comment.user.name}</p>
                        <p class="text-xs text-gray-500">${new Date(comment.created_at).toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric'
                        })}</p>
                    </div>
                </div>
                <p class="text-gray-600 mt-0 ml-2">${comment.content}</p>
            `;

                container.appendChild(commentElement);
            });

            document.getElementById('pageInfo').textContent = `Halaman ${currentPage} dari ${totalPages}`;
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === totalPages;
        }

        document.getElementById('prevPage')?.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updatePagination();
            }
        });

        document.getElementById('nextPage')?.addEventListener('click', () => {
            if (currentPage < totalPages) {
                currentPage++;
                updatePagination();
            }
        });

        document.getElementById('commentForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const textarea = form.querySelector('textarea');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';

            fetch('{{ route('comments.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        news_id: form.querySelector('input[name="news_id"]').value,
                        content: textarea.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                            allComments.unshift(data.comment);
                            currentPage = 1;
                            updatePagination();
                            textarea.value = '';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat mengirim komentar.'
                    });
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Kirim Komentar';
                });
        });
    </script>
