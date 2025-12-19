<div class="lg:flex gap-x-2">
    {{-- <a href="{{ route('reports.detail', $data->id) }}">
        <x-button.button-gray class="px-3 py-1">Detail</x-button.button-gray>
    </a> --}}

    <a href="{{ route('disaster_impacts.edit', $data->id) }}"><x-button.button-submit
            class="px-3 py-1">Ubah</x-button.button-submit>
    </a>
    <a><x-button.button-danger class="px-3 py-1"
            onclick="btnDelete({{ $data->id }}, '{{ $data->confirmReport->report->kd_report }}')">Hapus</x-button.button-danger>
    </a>
    <a href="{{ route('disaster_victims.create', $data->id) }}" class="btn btn-sm btn-primary">
        isi korban
    </a>
</div>
