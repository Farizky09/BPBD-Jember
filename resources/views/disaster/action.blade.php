<div class="lg:flex gap-x-2">

    <a href="{{ route('disaster.edit', $data->id) }}">
        <x-button.button-submit class="px-3 py-1">Ubah</x-button.button-submit>
    </a>
    <x-button.button-danger onclick="btnDelete({{ $data->id }}, '{{ $data->name }}')" class="px-3 py-1">
        Hapus
    </x-button.button-danger>
    

</div>
