<div class="lg:flex gap-x-2">


    <a href="{{ route('disaster_victims.edit', $data->id) }}"><x-button.button-submit
            class="px-3 py-1">Ubah</x-button.button-submit>
    </a>
    <x-button.button-danger class="px-3 py-1"
        onclick="btnDelete({{ $data->id }}, '{{ $data->fullname }}')">Hapus</x-button.button-danger>

    {{-- <a href="{{ route('disaster_victims.create', $data->id) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-print"></i> isi korban
    </a> --}}
</div>
