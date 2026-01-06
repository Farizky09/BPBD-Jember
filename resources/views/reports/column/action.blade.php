<div class="lg:flex gap-x-2">
    <a href="{{ route('reports.detail', $data->id) }}">
        <x-button.button-gray class="px-3 py-1">Detail</x-button.button-gray>
    </a>
    @if (
        $data->status == 'pending' &&
        (
            Auth::user()->hasRole('user') ||
            (Auth::user()->hasRole(['admin', 'super_admin']) && $data->user_id == Auth::id())
        )
    )
        <a href="{{ route('reports.edit', $data->id) }}">
            <x-button.button-submit class="px-3 py-1">Ubah</x-button.button-submit>
        </a>
        <x-button.button-danger class="px-3 py-1"
            onclick="btnDelete({{ $data->id }}, '{{ $data->kd_report }}')">Hapus</x-button.button-danger>
    @endif

    @if ($data->status == 'pending' && Auth::user()->hasRole(['admin', 'super_admin']))

        <x-button.button-submit onclick="btnProcess({{ $data->id }}, '{{ $data->kd_report }}')" class="px-3 py-1">Proses</x-button.button-submit>

    @endif

    

</div>
