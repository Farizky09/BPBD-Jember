@extends('layouts.master')

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Permission', 'url' => route('permission.index')],
            ['name' => 'Ubah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Ubah Permission" class="text-center" />
@endsection
<form action="{{ route('permission.update', $data->id) }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <x-input.input label="Nama Permission" name="name" id="name"
            placeholder="Contoh: permission_create" class="w-40" value="{{ $data->name }}" required="true" />

    </div>
    <div class="flex items-center justify-center">
        <x-button.button-submit type="submit" class="px-5 py-2.5">Simpan Permission</x-button.button-submit>
        
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
            confirmButtonText: 'Ya, Ubah!'
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
