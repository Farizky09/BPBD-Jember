@props(['odontogramGroup' => [], 'toothNumber' => 0, 'side' => 0, 'tooth_location' => null])
@php
    use Illuminate\Support\Facades\asset;
    $terminology = collect($tooth_location)->where('display_id', $toothNumber)->first();
    $terminology_id = $terminology ? $terminology['id'] : null;
@endphp

<div id="P{{ $toothNumber }}" data-terminology-id="{{ $terminology_id }}" data-modal-target="default-modal" data-modal-toggle="default-modal"
    class="relative mb-8 tooth">
    <div class="relative flex justify-center items-start" id="top">
        @if (array_key_exists('occlusial_action', $odontogramGroup) && $odontogramGroup['occlusial_action'] && !$odontogramGroup['all'])
            @php
                $condition = '';
                foreach ($odontogramGroup['occlusial_action']->odontogram_observation as $key => $value) {
                    if ($value->type == 'keadaan_gigi') {
                        $condition = explode(' = ', $value?->observation?->display_id)[0] ?? '';
                    }
                }
            @endphp
            @if ($condition != 'sou')
                <img src="{{ asset('image/' . $condition . '.png') }}"
                    style="height:{{ $side == 4 ? '20px' : '17px' }}; width: {{ $side == 4 ? '20px' : '17px' }};" alt="" class="absolute pointer-events-none">
            @endif
        @endif
    </div>
    <div class="relative flex justify-start" id="left">
        @if (array_key_exists('mesial', $odontogramGroup) && $odontogramGroup['mesial'] && !$odontogramGroup['all'])
            @php
                $condition = '';
                foreach ($odontogramGroup['mesial']->odontogram_observation as $key => $value) {
                    if ($value->type == 'keadaan_gigi') {
                        $condition = explode(' = ', $value?->observation?->display_id)[0] ?? '';
                    }
                }
            @endphp
            @if ($condition != 'sou' && $condition != '')
                <img src="{{ asset('image/' . $condition . '.png') }}"
                    style="height:{{ $side == 4 ? '20px' : '17px' }}; width: {{ $side == 4 ? '20px' : '17px' }}; top: 10px" alt="" class="absolute pointer-events-none">
            @endif
        @endif
    </div>
    <div class="relative flex justify-center items-center">
        <img src="{{ in_array($side, [
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
                : asset('image/5_side.png') }}" alt="" height="50" width="50"
            id="main-image">
        @if (array_key_exists('buccalis', $odontogramGroup) && $odontogramGroup['buccalis'] && !$odontogramGroup['all'])
            @php
                $condition = '';
                foreach ($odontogramGroup['buccalis']->odontogram_observation as $key => $value) {
                    if ($value->type == 'keadaan_gigi') {
                        $condition = explode(' = ', $value?->observation?->display_id)[0] ?? '';
                    }
                }
            @endphp
            @if ($condition != 'sou' && $condition != '')
                <img src="{{ asset('image/' . $condition . '.png') }}"
                    width="17" height="17" alt="" class="absolute pointer-events-none">
            @endif
        @endif

        @if (array_key_exists('all', $odontogramGroup) && $odontogramGroup['all'])
            @php
                $condition = '';
                foreach ($odontogramGroup['all']->odontogram_observation as $key => $value) {
                    if ($value->type == 'keadaan_gigi') {
                        $condition = explode(' = ', $value?->observation?->display_id)[0] ?? '';
                    }
                }
                $is_center = in_array($condition, ['acr', 'cfr', 'mis', 'mam', 'mis-pon-pob', 'miss-prd-acr', 'mpc', 'mpm', 'onl', 'pob', 'poc', 'pon', 'prd', 'rrx', 'sou']) ? true : false;
                $is_top = in_array($condition, ['ano', 'att', 'imv', 'ipx', 'non', 'dia', 'pre', 'une']) ? true : false;
                $height = 50;
                if ($is_center) {
                    $height = 50;
                } else if ($is_top) {
                    $height = 17;
                }
            @endphp
            @if ($condition != 'sou' && $condition != '')
                <img src="{{ asset('image/' . $condition . '.png') }}"
                    style="object-fit: cover;" alt="" class="absolute pointer-events-none">
            @endif
        @endif
    </div>
    <div class="relative flex justify-end" id="right">
        @if (array_key_exists('distal', $odontogramGroup) && $odontogramGroup['distal'] && !$odontogramGroup['all'])
            @php
                $condition = '';
                foreach ($odontogramGroup['distal']->odontogram_observation as $key => $value) {
                    if ($value->type == 'keadaan_gigi') {
                        $condition = explode(' = ', $value?->observation?->display_id)[0] ?? '';
                    }
                }
            @endphp
            @if ($condition != 'sou' && $condition != '')
                <img src="{{ asset('image/' . $condition . '.png') }}"
                    style="height:{{ $side == 4 ? '20px' : '17px' }}; width: {{ $side == 4 ? '20px' : '17px' }}; top: -40px" alt="" class="absolute pointer-events-none">
            @endif
        @endif
    </div>
    <div class="relative flex justify-center items-end" id="bottom">
        @if (array_key_exists('palatal_lingual', $odontogramGroup) && $odontogramGroup['palatal_lingual'] && !$odontogramGroup['all'])
            @php
                $condition = '';
                foreach ($odontogramGroup['palatal_lingual']->odontogram_observation as $key => $value) {
                    if ($value->type == 'keadaan_gigi') {
                        $condition = explode(' = ', $value?->observation?->display_id)[0] ?? '';
                    }
                }
            @endphp
            @if ($condition != 'sou' && $condition != '')
                <img src="{{ asset('image/' . $condition . '.png') }}"
                    width="50" height="50" alt="" class="absolute pointer-events-none">
            @endif
        @endif
    </div>
    <p id="label" class="mt-2 text-center pointer">
        {{ $toothNumber }}
    </p>
</div>
