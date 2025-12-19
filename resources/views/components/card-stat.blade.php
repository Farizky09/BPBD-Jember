@props(['title' => '', 'data' => null])

<div class="block max-w-sm px-4 py-6 bg-white border border-gray-200 rounded-lg">
    <h5 class="mb-2 text-sm font-medium uppercase tracking-tight text-gray-900">
        {{ $title }}
    </h5>
    <p class="font-semibold text-gray-700 text-lg">
        {{ $data }}
    </p>
</div>
