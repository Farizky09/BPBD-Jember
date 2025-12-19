@extends('layouts.master')

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Pengguna', 'url' => route('user-management.index')],
            ['name' => 'Ubah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Ubah Pengguna" class="text-center" />
@endsection
<form action="{{ route('user-management.update', $data->id) }}" method="POST" id="registerForm">

    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text"
                class="w-full px-3 py-2 border rounded-lg  focus:outline-none focus:ring-2 focus:ring-blue-400 @error('name') border-red-500 @enderror"
                id="name" name="name" value="{{ $data->name }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email"
                class="w-full px-3 py-2 border rounded-lg  focus:outline-none focus:ring-2 focus:ring-blue-400 @error('name') border-red-500 @enderror"
                id="email" name="email" value="{{ $data->email }}" required>
        </div>
        <div class="mb-3">
            <label for="phone_number" class="form-label">No Telepon</label>
            <input type="number"
                class="w-full px-3 py-2 border rounded-lg  focus:outline-none focus:ring-2 focus:ring-blue-400 @error('phone_number') border-red-500 @enderror"
                id="phone_number" name="phone_number" value="{{ trim($data->phone_number) }}" required
                oninput="checkPhoneLength(this)">
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 @error('username') border-red-500 @enderror"
                id="username" name="username" value="{{ $data->username }}" required>
        </div>
        <div class="mb-3">
            <label for="poin" class="form-label">Poin</label>
            <input type="number"
                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 @error('poin') border-red-500 @enderror"
                id="poin" name="poin" value="{{ $data->poin }}"
                @if ($data->is_banned != 'none') readonly @endif required>
        </div>



        <div>
            <label for="role" class="form-label">Role</label>
            <select label="Role" name="role" required="required"
                class="w-full select2 px-3 py-2 border rounded-lg  focus:outline-none focus:ring-2 focus:ring-blue-400 @error('role') border-red-500 @enderror">
                <option value="" selected disabled hidden>Pilih Role</option>
                @foreach ($role as $row)
                    <option value="{{ $row->id }}" @if ($data->hasRole($row->name)) selected @endif>
                        {{ $row->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex items-center justify-center mt-6">
        <button type="submit"
            class="bg-blue-500 w-full hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Simpan</button>
    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function checkPhoneLength(input) {
        if (input.value.length > 13) {
            input.disabled = true;
            Swal.fire({
                icon: 'warning',
                title: 'Maksimal 13 Digit',
                text: 'Nomor telepon tidak boleh lebih dari 13 digit.',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                input.disabled = false;
                input.focus();
                input.value = input.value.slice(0, 12);
            });
        }

    }
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        const email = document.getElementById('email').value;
        const phoneNumber = document.getElementById('phone_number').value;
        const password = document.getElementById('password').value;
        if (!email || !phoneNumber) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Semua kolom harus diisi',
                text: 'Silakan lengkapi semua kolom sebelum melanjutkan.',
                confirmButtonColor: '#3085d6'
            });
        }
    });
</script>
@if ($errors->has('email'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Email sudah terdaftar',
            text: 'Silakan gunakan email lain.',
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif

@if ($errors->has('phone_number'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan pada Nomor Telepon',
            text: @json($errors->first('phone_number')),
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif

@endsection
