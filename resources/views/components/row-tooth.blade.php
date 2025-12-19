@props(['toothNumber', 'data'])

{{ $toothNumber }}:
@foreach ($data as $d)
    {{ $d->diagnosis }}@if ($loop->last)
    ;@else,
    @endif
@endforeach
