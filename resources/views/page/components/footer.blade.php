<footer id="footer" class="shadow-md border bg-white">
    <div class="container mx-auto px-4">
        <div class="mt-6 flex flex-col gap-8 text-center lg:flex-row lg:justify-between lg:items-start lg:text-left">

            <div class="w-full lg:w-1/4 flex flex-col items-center lg:items-center order-2 lg:order-1">
                <h4 class="text-lg font-bold mb-2">BPBD JEMBER</h4>
                <img src="{{asset('assets/img/icons/bpbd.png')}}" alt="" class="mx-auto lg:mx-0">
            </div>

            <div class="w-full lg:w-1/2 flex flex-col items-center order-1 lg:order-2">
                <div class="mb-4 w-full flex justify-center">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3347.791545994533!2d113.71480397421277!3d-8.155475281698006!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd695b3675f7995%3A0xa0f959a7e37dc91e!2sBadan%20Penanggulangan%20Bencana%20Daerah%20(BPBD)%20Kabupaten%20Jember!5e1!3m2!1sid!2sid!4v1750216914638!5m2!1sid!2sid"
                        class="w-full max-w-xs sm:max-w-md md:max-w-lg lg:max-w-full h-36 sm:h-48 md:h-56 lg:h-64 rounded"
                        style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <p class="text-sm leading-relaxed">
                    Jl. Danau Toba No.16, Lingkungan Panji, Tegalgede, Kec. Sumbersari, Kabupaten Jember, Jawa Timur
                    68124
                </p>
            </div>

            <div class="w-full lg:w-1/4 flex flex-col items-center lg:items-center order-3">
                <h4 class="text-lg font-semibold mb-2 text-center lg:text-center">Sosial Media</h4>
                <div class="flex space-x-3">
                    <a href="https://www.instagram.com/bpbd_kab.jember/"
                        class="p-3 rounded-lg shadow-md border border-gray-200 hover:bg-gray-100 transition-all duration-200">
                        <i class="bx bxl-instagram text-2xl text-pink-600"></i>
                    </a>

                    <a href="https://www.facebook.com/bpbd.jember/"
                        class="p-3 mr-3 rounded-lg shadow-md border border-gray-200 hover:bg-gray-100 transition-all duration-200">
                        <i class="bx bxl-facebook text-2xl text-blue-700"></i>
                    </a>

                    <a href="https://www.youtube.com/@bpbdjember5247"
                        class="p-3 rounded-lg shadow-md border border-gray-200 hover:bg-gray-100 transition-all duration-200">
                        <i class="bx bxl-youtube text-2xl text-red-600"></i>
                    </a>
                </div>
            </div>




        </div>
    </div>

    <div class="border-t border-gray-300 py-4 text-center text-sm text-gray-600 mt-6">
        <div>&copy; 2025 BPBD Kab.Jember</div>
        <div>
            Dikembangkan Oleh
            <a href="https://www.linkedin.com/in/saka-bramasta-a884672a1/" class="text-orange-600 hover:underline"
                data-bs-toggle="modal">Tim Doa Ibu</a>
        </div>
    </div>
</footer>


<!-- Scroll Top -->
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<!-- Preloader -->
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }} "></script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
<script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
<script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<link href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" rel="stylesheet" />
<div id="osm-map"></div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>
