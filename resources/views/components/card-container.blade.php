@props(['header' => '', 'id' => '', 'padding' => true, 'class' => ''])

<div class="py-2" id="{{ $id }}">
    <div class="mx-auto">
        <div class="bg-white  border border-gray-200 overflow-hidden sm:rounded-lg {{ $class }}">
            <div class="{{ $padding == true ? 'p-4' : '' }}">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
