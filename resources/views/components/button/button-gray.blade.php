@props(['type' => 'button', 'class' => '', 'id' => ''])
<button type="{{ $type }}" id="{{ $id }}"
    class="focus:outline-none text-white bg-gray-600 hover:bg-gray-600 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm {{ $class }}">
    {{ $slot }}
</button>
