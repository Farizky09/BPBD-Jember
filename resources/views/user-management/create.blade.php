@extends('layouts.master')

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Pengguna', 'url' => route('user-management.index')],
            ['name' => 'Tambah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Tambah Pengguna" class="text-center" />
@endsection
<form action="{{ route('user-management.store') }}" method="POST" id="registerForm">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div>
            <x-input.input label="Nama" name="name" id="name" required="true"></x-input.input>
        </div>

        <div>
            <x-input.input label="Email" name="email" type="email" id="email"  required="true"></x-input.input>


        </div>

        <div>
            <x-input.input label="No Telepon" type="number" name="phone_number" oninput="checkPhoneLength(this)" id="phone_number"
                required="true"></x-input.input>


        </div>
        <div>
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role <span class="text-red-600">
                    *</span></label>
            <select id="role" name="role"
                class="w-full select2 px-3 py-4 border rounded-lg  focus:outline-none focus:ring-2 focus:ring-blue-400 @error('role') border-red-500 @enderror"
                required>
                <option value="" selected disabled hidden>Pilih Role</option>
                @foreach ($role as $row)
                    <option value="{{ $row->id }}" {{ old('role') == $row->id ? 'selected' : '' }}>
                        {{ $row->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-6 flex justify-center">
        <x-button.button-submit type="submit" class="px-5 py-2.5">Simpan Akun</x-button.button-submit>
        
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
        if (!email || !phoneNumber || !password) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Semua kolom harus diisi',
                text: 'Silakan lengkapi semua kolom sebelum melanjutkan.',
                confirmButtonColor: '#3085d6'
            });
        }

        if (password.length < 8) {
            event.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password terlalu pendek',
                text: 'Password harus terdiri dari minimal 8 karakter.',
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
