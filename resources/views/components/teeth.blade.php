@props(['label', 'number', 'left', 'right', 'top', 'bottom', 'center'])

<div class="tooth" id="tooth_{{ $number }}" data-id="{{ $number }}" data-modal-target="default-modal"
    data-modal-toggle="default-modal">
    <span id="diagnose_{{ $number }}_top" class="text-lg font-semibold"></span>
    <label data-label="{{ $number }}" class="text-sm">{{ $number }}</label>
    <div class="tooth-group">
        @if ($top)
            <div class="side side_top" data-side="top" id="P{{ $number }}-T"></div>
        @endif
        @if ($left)
            <div class="side side_left" data-side="left" id="P{{ $number }}-L"></div>
        @endif
        @if ($center)
            <div class="side side_center" data-side="center" id="P{{ $number }}-C"></div>
        @endif
        @if ($right)
            <div class="side side_right" data-side="right" id="P{{ $number }}-R"></div>
        @endif
        @if ($bottom)
            <div class="side side_bottom" data-side="bottom" id="P{{ $number }}-B"></div>
        @endif
    </div>
    <span id="diagnose_{{ $number }}_bottom" class="text-lg font-semibold"></span>
</div>
