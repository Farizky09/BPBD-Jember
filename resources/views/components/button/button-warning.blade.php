@props(['type' => 'button', 'class' => '', 'id' => ''])
<button type="{{ $type }}" id="{{ $id }}"
    {{$attributes}}
    class="focus:outline-none text-white bg-yellow-500 hover:bg-yellow-500 focus:ring-4 focus:ring-blbg-yellow-500 font-medium rounded-lg text-sm  {{ $class }}">
    {{ $slot }}
</button>
