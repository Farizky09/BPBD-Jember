<section id="map" class="mapt section">
    <div class="container section-title d-flex flex-column align-items-center" data-aos="fade-up">
        <h1 class="font-bold text-black">Maps<span class="text-orange-400"> Bencana</span></h1>
    </div>
    <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white rounded-lg shadow-lg w-full h-full p-6">
            <p id="status" class="mt-2"></p>
            <a id="map-link" href="#" class="text-orange-500 underline" hidden></a>
            <div id="petamap" class="w-full h-96 border rounded-lg"></div>
        </div>
    </div>
</section>

<script src="{{ asset('assets/js/section/map.js') }}"></script>
<script src="{{ route('page.mapload') }}" async defer></script>
<script>
    window.ASSET_URLS = {
        pinMapIcon: "{{ asset('assets/img/icons/pin-map.png') }}",
        informationPointIcon: "{{ asset('assets/img/icons/information-point.png') }}",
    };
    @if (auth()->check())
        window.USER_ROLE = @json(auth()->user()->getRoleNames());
        window.USER_ID = {{ auth()->user()->id }};
    @else
        window.USER_ROLE = 'guest';
        window.USER_ID = null;
    @endif
</script>
