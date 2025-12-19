@extends('layouts.master')
@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Jenis Bencana', 'url' => route('disaster.index')],
            ['name' => 'Tambah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Tambah Jenis Bencana" class="text-center" />
@endsection
<form action="{{ route('disaster.store') }}" method='POST' id='form-create'>
    @csrf
    <div class="mb-4">
        <x-input.input label="Nama Bencana" name="name" id="name"
            placeholder="Contoh: Banjir" class="w-40" required="true" />
    </div>
    <div class="flex items-center justify-center">
        <x-button.button-submit type="submit" class="px-5 py-2.5">Simpan</x-button.button-submit>

    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
        const formCreate = document.getElementById('form-create');
        formCreate.addEventListener('submit', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menambahkan data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tambahkan!',
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
