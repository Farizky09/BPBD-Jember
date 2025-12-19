<div class="lg:flex gap-x-2">
    {{-- @if ($data->status == 'pending' && Auth::user()->hasRole('user'))
        <a href="{{ route('reports.edit', $data->id) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i>
        </a>
        <button onclick="btnDelete({{ $data->id }}, '{{ $data->name }}')" class="btn btn-sm btn-danger">
            <i class="fas fa-trash"></i>
        </button>
    @endif --}}
    <a href="{{ route('confirm-reports.detail', $data->id) }}"
        class="btn btn-sm {{ $data->status == 'proses' && Auth::user()->hasRole(['admin', 'super_admin']) ? 'btn-success' : 'btn-secondary' }}">
        @if ($data->status == 'proses' && Auth::user()->hasRole(['admin', 'super_admin']))
            Verifikasi
        @else
            Detail
        @endif
    </a>

    @if ($data->status == 'accepted' && Auth::user()->hasRole(['admin', 'super_admin']) && $data->disasterImpacts == null)
        <a href="{{ route('disaster_impacts.create', $data->id) }}" class="btn btn-sm btn-primary">
            isi penanganan
        </a>
    @endif



</div>
