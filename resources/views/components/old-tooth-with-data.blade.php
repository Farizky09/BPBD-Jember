@props(['odontogramGroup' => [], 'toothNumber' => 0])

<div id="P{{ $toothNumber }}" data-modal-target="default-modal" data-modal-toggle="default-modal"
    class="relative mb-8 tooth">
    <div class="relative flex justify-center items-start" id="top">
        @if (array_key_exists('top', $odontogramGroup) && $odontogramGroup['top'])
            <img src="{{ asset('image/' . $odontogramGroup['top'] . '_' . $odontogramGroup['side'] . '_side' . '_top.png') }}"
                width="50" height="50" alt="" class="absolute pointer-events-none">
        @endif
    </div>
    <div class="relative flex justify-start" id="left">
        @if (array_key_exists('left', $odontogramGroup) && $odontogramGroup['left'])
            <img src="{{ asset('image/' . $odontogramGroup['left'] . '_' . $odontogramGroup['side'] . '_side' . '_left.png') }}"
                alt="" class="absolute pointer-events-none"
                style="height:50px; width: {{ $odontogramGroup['side'] == 4 ? '20px' : '17px' }};">
        @endif
    </div>
    <div class="relative flex justify-center items-center">
        <img src="{{ asset('image/' . $odontogramGroup['img_name']) }}" alt="" height="50" width="50"
            id="main-image">
        @if (array_key_exists('center', $odontogramGroup) && $odontogramGroup['center'])
            <img src="{{ asset('image/' . $odontogramGroup['center'] . '_' . $odontogramGroup['side'] . '_side' . '_center.png') }}"
                width="50" height="50" alt="" class="absolute pointer-events-none">
        @endif

        @if (array_key_exists('all', $odontogramGroup) && $odontogramGroup['all'])
            @foreach ($odontogramGroup['all'] as $data)
                @if ($data['is_outside'] == 'yes')
                    <img src="{{ asset('image/' . $data['diagnosis'] . '.png') }}" width="20" height="20"
                        alt="" class="absolute pointer-events-none" style="top: -17px"
                        data-outside="{{ $data['is_outside'] }}" data-modal-target="default-modal"
                        data-modal-toggle="default-modal">
                @else
                    <img src="{{ asset('image/' . $data['diagnosis'] . '.png') }}" width="50" height="50"
                        alt="" class="absolute pointer-events-none" data-modal-target="default-modal"
                        data-modal-toggle="default-modal">
                @endif
            @endforeach
        @endif
    </div>
    <div class="relative flex justify-end" id="right">
        @if (array_key_exists('right', $odontogramGroup) && $odontogramGroup['right'])
            <img src="{{ asset('image/' . $odontogramGroup['right'] . '_' . $odontogramGroup['side'] . '_side' . '_right.png') }}"
                alt="" class="absolute pointer-events-none"
                style="height:50px; width: {{ $odontogramGroup['side'] == 4 ? '20px' : '17px' }}; top: -50px">
        @endif
    </div>
    <div class="relative flex justify-center items-end" id="bottom">
        @if (array_key_exists('bottom', $odontogramGroup) && $odontogramGroup['bottom'])
            <img src="{{ asset('image/' . $odontogramGroup['bottom'] . '_' . $odontogramGroup['side'] . '_side' . '_bottom.png') }}"
                alt="" class="absolute pointer-events-none" width="50" height="50" alt="">
        @endif
    </div>
    <p id="label" class="mt-2 text-center pointer">
        {{ $toothNumber }}
    </p>
</div>
