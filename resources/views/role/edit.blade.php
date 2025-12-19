@extends('layouts.master')

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Hak Akses', 'url' => route('role.index')],
            ['name' => 'Ubah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Ubah Hak Akses" class="text-center" />
@endsection
<form action="{{ route('role.update', $data->id) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <x-input.input label="Nama Hak Akses" name="name" id="name"
            placeholder="Contoh: permission_create" class="w-40" value="{{ $data->name }}" required="true"></x-input.input>
    </div>

    <div class="mb-3">
        <label for="permission" class="form-label">Permission</label>
        <div class="grid max-md:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6 mt-6">
            @foreach ($permissions as $permission)
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="permissions[]" id="{{ $permission->name }}"
                        value="{{ $permission->name }}" class="checkbox checkbox-primary"
                        @if ($data->hasPermissionTo($permission->name)) checked @endif>
                    <label for="{{ $permission->name }}">{{ ucwords(str_replace('_', ' ', $permission->name)) }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="flex items-center justify-center">
        <x-button.button-submit type="submit" class="px-5 py-2.5">Simpan Hak Akses</x-button.button-submit>

    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const formCreate = document.getElementById('form-edit');
    formCreate.addEventListener('submit', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin menambahkan data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Ubah!',
            focusConfirm: false,
            customClass: {
                popup: 'swal2-border-radius',
                confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none',
                cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
            },
        }).then((result) => {
            if (result.isConfirmed) {
                formCreate.submit();
            }
        });

    });
</script>
@if ($errors->has('name'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Nama sudah terdaftar',
            text: 'Silakan gunakan nama lain.',
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif
@endsection
