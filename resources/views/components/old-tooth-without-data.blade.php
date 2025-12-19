@props(['i'])

@php
    use Illuminate\Support\Facades\asset;
@endphp

<div id="P{{ $i }}" data-modal-target="default-modal" data-modal-toggle="default-modal"
    class="relative mb-8 tooth">
    <div class="side side_top flex justify-center" id="top">
    </div>
    <div class="side side_left" id="left"></div>
    <img src="{{ in_array($i, [
        13,
        12,
        11,
        21,
        22,
        23,
        31,
        32,
        33,
        53,
        52,
        51,
        61,
        62,
        63,
        18,
        48,
        83,
        82,
        81,
        71,
        72,
        73,
        43,
        42,
        41,
        71,
        72,
        73,
    ])
        ? asset('image/4_side.png')
        : asset('image/5_side.png') }}"
        alt="" height="50" width="50" id="main-image">
    <div class="side side_right" id="right">
    </div>
    <div class="side side_bottom justify-center flex" id="bottom">
    </div>
    <p id="label" class="mt-2 text-center pointer">{{ $i }}</p>
</div>
