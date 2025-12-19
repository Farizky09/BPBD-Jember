<div class="lg:flex gap-x-2">
    {{-- <a href="{{ route('reports.detail', $data->id) }}">
        <x-button.button-gray class="px-3 py-1">Detail</x-button.button-gray>
    </a> --}}

    <a href="{{ route('recap.detail', $data->id) }}"><x-button.button-submit
            class="px-3 py-1">Detail</x-button.button-submit>
    </a>

</div>
