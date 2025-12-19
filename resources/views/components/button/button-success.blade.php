@props(['type' => 'button', 'class' => '', 'id' => ''])
<button type="{{ $type }}" id="{{ $id }}"
    {{$attributes}}
    class="focus:outline-none text-white bg-green-500 hover:bg-green-500 focus:ring-4 focus:ring-blbg-green-500 font-medium rounded-lg text-sm px-5 py-2.5 {{ $class }}">
    {{ $slot }}
</button>
