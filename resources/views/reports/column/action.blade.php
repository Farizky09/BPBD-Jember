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
        {{-- <button onclick="btnProcess({{ $data->id }}, '{{ $data->name }}')"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 me-1  dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800  ">
            Proses
        </button> --}}
        <x-button.button-submit onclick="btnProcess({{ $data->id }}, '{{ $data->kd_report }}')" class="px-3 py-1">Proses</x-button.button-submit>

    @endif

    {{-- @if ($data->status == 'pending' && Auth::user()->hasRole(['admin', 'super_admin']))
        <form action="{{ route('reports.process', $data->id) }}" method="POST" class="inline"
            id="process-form-{{ $data->id }}">
            @csrf
            @method('PUT')
            <button type="button" class="btn btn-info btn-sm" onclick="confirmProcess({{ $data->id }})">
                Proses
            </button>
        </form>
        <script>
            function confirmProcess(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Anda tidak dapat membatalkan tindakan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, proses!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`process-form-${id}`).submit();
                    }
                });
            }
        </script>
    @endif --}}

</div>
