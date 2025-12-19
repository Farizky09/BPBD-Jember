@props(['active' => false, 'title' => '', 'icon' => '', 'toggle' => ''])
<li>
    <button type="button" class="flex w-full items-center px-3 py-3 rounded-md text-gray-900"
        aria-controls="{{ $toggle }}" data-collapse-toggle="{{ $toggle }}">
        <i
            class="{{ $icon }} w-4 h-4 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></i>
        <span class="ml-3">{{ $title }}</span>
        </span>
        <i class="fas {{ $active ? 'fa-chevron-up' : 'fa-chevron-down' }} ml-auto"></i>
    </button>
    <ul id="{{ $toggle }}" class="{{ $active ? 'block' : 'hidden' }}">
        {{ $slot }}
    </ul>
</li>
