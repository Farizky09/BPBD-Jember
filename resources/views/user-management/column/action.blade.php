<div class="lg:flex gap-x-2">
    <a href="{{ route('user-management.edit', $data->id) }}">
        <x-button.button-submit class="px-3 py-1">Ubah</x-button.button-submit>
    </a>
    <x-button.button-danger onclick="btnDelete({{ $data->id }}, '{{ $data->name }}')" class="px-3 py-1">
        Hapus
    </x-button.button-danger>
    {{-- <label for="modal" onclick="btnReset('{{ $data->id }}', '{{ $data->name }}')"
        class="text-white bg-green-400 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-md text-sm p-2 text-center inline-flex items-center hover:bg-green-500 transition duration-300 ease-in-out">
        Reset Password
    </label> --}}
    <x-button.button-warning onclick="btnReset({{ $data->id }}, '{{ $data->name }}')" class="px-3 py-1">
        Reset Password
    </x-button.button-warning>
    @if ($data->is_banned == 'none')
        <x-button.button-danger onclick="btnBanUser({{ $data->id }}, '{{ $data->name }}')" class="px-3 py-1">
            Blokir
        </x-button.button-danger>
    @elseif ($data->is_banned == 'permanent' || $data->is_banned == 'temporary')
        <x-button.button-success onclick="btnUnbanUser({{ $data->id }}, '{{ $data->name }}')" class="px-3 py-1">
            Buka Blokir
        </x-button.button-success>
    @endif


</div>
