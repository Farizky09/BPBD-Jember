@props(['type' => 'button', 'class' => '', 'id' => ''])
<button type="{{ $type }}" id="{{ $id }}"
    {{$attributes}}
    class="focus:outline-none text-white bg-blue-500 hover:bg-blue-500 focus:ring-4 focus:ring-blbg-blue-500 font-medium rounded-lg text-sm  {{ $class }}">
    {{ $slot }}
</button>
