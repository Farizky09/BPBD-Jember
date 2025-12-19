<div class="lg:flex gap-x-2">

    <x-button.button-danger class="px-3 py-1"
        onclick="btnDelete({{ $data->id }}, '{{ $data->tittle }}')">Hapus</x-button.button-danger>
    @if ($data->status == 'draft')
        <a href="{{ route('news.edit', $data->id) }}">
            <x-button.button-gray class="px-3 py-1">Ubah</x-button.button-gray>
        </a>
        <x-button.button-submit class="px-3 py-1"
            onclick="btnPublish({{ $data->id }}, '{{ $data->tittle }}')">Publish</x-button.button-submit>
    @endif
    @if ($data->status == 'published')
        <x-button.button-danger class="px-3 py-1"
            onclick="btnTakedown({{ $data->id }}, '{{ $data->tittle }}')">Takedown</x-button.button-danger>
    @endif

    <a href="{{ route('news.detail', $data->id) }}">
        <x-button.button-gray class="px-3 py-1">Detail</x-button.button-gray>
    </a>

</div>
