@props(['type' => 'button', 'class' => '', 'id' => ''])
<button type="{{ $type }}" id="{{ $id }}"
    {{$attributes}}
    class="focus:outline-none text-white bg-red-500 hover:bg-red-500 focus:ring-4 focus:ring-blbg-red-500 font-medium rounded-lg text-sm  {{ $class }}">
    {{ $slot }}
</button>
