@extends('layouts.master')
@section('title', 'Ubah Hak Akses')
@php
    $breadcrumbs = [
        [
            'label' => 'Dashboard',
            'url' => route('dashboard.index'),
            'icon' => 'fas fa-home',
        ],
        [
            'label' => 'Manajemen Pengguna',
            'url' => route('role.index'),
        ],
        [
            'label' => 'Ubah Hak Akses',
            'url' => null,
        ],
    ];

@endphp
@section('content')

<<<<<<< Updated upstream
    <div>
        <p class="text-lg font-semibold mb-2 text-black">Permission <span class="text-red-600">*</span></p>
        <div class="grid max-md:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6 xl:grid-cols-4">
            @foreach ($permissions as $permission)
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="permissionsz[]" id="{{ $permission->name }}"
                        value="{{ $permission->name }}" class="checkbox checkbox-primary"
                        {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
                    <label for="{{ $permission->name }}" class="text-sm">
                        {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                    </label>
=======
    <x-card-container class="mt-4" header="Ubah Hak akses">

        <x-form.container action="{{ route('role.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')
            <x-form.group cols="1">
                <x-form.input name="name" label="Nama" value="{{ old('name', $data->name) }}" id="name" required />

                <p class="text-sm font-medium text-gray-900">Permission <span class="text-red-600">*</span></p>
                <div class="grid max-md:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-4 xl:grid-cols-4">
                    @foreach ($permissions as $permission)
                        @php
                            // Cek apakah permission ini sudah dipilih (old input atau data existing)
                            $currentPermissions = old('permissions', $data->permissions->pluck('name')->toArray());
                            $isChecked = in_array($permission->name, $currentPermissions);
                        @endphp

                        <x-form.checkbox name="permissions[]" id="permission_{{ $permission->id }}"
                            value="{{ $permission->name }}" label="{{ ucwords(str_replace('_', ' ', $permission->name)) }}"
                            :checked="$isChecked" />
                    @endforeach
>>>>>>> Stashed changes
                </div>
            </x-form.group>

            <div class="mt-5 flex justify-center">
                <x-form.button class="mt-4">Simpan Hak Akses</x-form.button>
            </div>
        </x-form.container>
    </x-card-container>
@endsection
