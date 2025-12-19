{{-- @extends('layouts.master')

@section('main')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Main Content -->
            <div class="w-full md:w-2/3">
                <!-- Article Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $data->title }}</h1>
                    <div class="text-sm text-gray-500">
                        <span class="mr-4">{{ $data->title }}</span>
                        <span>{{ \Carbon\Carbon::parse($data->published_at)->translatedFormat('d F Y H:i') }}</span>
                    </div>
                </div>

                <!-- Image Slider -->
                <div class="swiper-container mb-6 rounded-lg overflow-hidden">
                    <div class="swiper-wrapper">
                        @foreach ($data->imageNews as $image)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gambar Berita"
                                    class="w-full h-96 object-cover">
                            </div>
                        @endforeach
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- Navigation Buttons -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <!-- Article Content -->
                <div class="prose max-w-none mb-8">
                    {!! $data->content !!}
                </div>
            </div>

            <!-- Related News -->
            <div class="w-full md:w-1/3">
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                    <h3 class="text-xl font-semibold mb-4">Berita Terkait</h3>
                    <div class="space-y-4">
                        @foreach ($relatedData as $related)
                            <div class="border-b pb-4 last:border-b-0">
                                <a href="{{ route('news.show', $related->slug) }}"
                                    class="block hover:text-blue-600 transition-colors">
                                    <h4 class="font-medium mb-1">{{ $related->title }}</h4>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($related->published_at)->translatedFormat('d F Y') }}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('news.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Daftar Berita
            </a>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <style>
        .swiper-container {
            position: relative;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: white;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 50%;
        }

        .swiper-pagination-bullet-active {
            background: white !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.swiper-container', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
@endpush --}}
