@extends('layouts.master')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush
@section('main')

    <div class="px-3 border-b border-gray-200 flex justify-start items-center">
        <button id="backToProfileBtn"
            class="tab-btn text-sm m-3 text-blue-600 hover:text-blue-800 font-medium focus:outline-none border-b-2 border-transparent">
            Ubah Profile
        </button>
        <button id="toggleFormBtn"
            class="tab-btn text-sm m-3 text-blue-600 hover:text-blue-800 font-medium focus:outline-none border-b-2 border-transparent">
            Ubah Password
        </button>
    </div>

    <form id="profileForm" action="{{ route('profile.update') }}" method="POST" class="px-6 py-4 space-y-1"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="px-6 pb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <label for="avatar-upload" class="cursor-pointer">
                        <img src="{{ $data->image_avatar ? asset('storage/' . $data->image_avatar) : asset('assets/img/avatar/a-sm.jpg') }}"
                            class="w-32 h-32 rounded-full object-cover shadow-md border-4 border-white" alt="Profil"
                            id="avatar-preview">
                    </label>
                    <div class="flex flex-col">
                        <label for="avatar-upload" class="text-sm font-medium text-gray-600 mb-1">
                            Gambar Profile
                        </label>
                        <button type="button" onclick="document.getElementById('avatar-upload').click()"
                            class="bg-blue-600 text-white px-3 py-1 text-sm rounded-md shadow hover:bg-blue-700 transition w-fit">
                            Upload Image
                        </button>

                        <input type="file" id="avatar-upload" name="image_avatar" class="hidden" accept="image/*">
                        <p class="mt-3 text-sm text-gray-500">Minimal Ukuran 2Mb. Format JPG, JPEG atau PNG</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space y-4">

                <div class="mb-4">
                    <x-input.input name="name" id="name" type="text" label="Nama Lengkap"
                        value="{{ old('name', $data->name) }}"
                        class="block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name')  @enderror"
                        required />

                </div>
                {{-- <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" id="username" value=""
                            class="block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('username') border-red-500 @enderror"
                            required>
                        @error('username')
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        @enderror
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div> --}}
                <div class="mb-4">
                    <x-input.input name="nik" label="NIK" id="nik" value="{{ old('nik', $data->nik) }}"
                        type="number"
                        class="block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nik')  @enderror" />
                </div>
                <div class="mb-4">
                    <x-input.input name="email" type="email" id="email" label="Email"
                        value="{{ old('email', $data->email) }}"
                        class="block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email')  @enderror"
                        required />

                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Unggah Foto Identitas (KTP/KK/SIM)<span
                            class="text-red-600">
                            *</span></label>
                    <div id="image-upload-container">
                        <div class="image-upload-item flex items-center mb-2">
                            <input type="file"
                                class=" appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none @error('image.*') border-red-500 @enderror"
                                name="photo_identity_path" accept="image/*">
                        </div>
                    </div>

                </div>
            </div>
            <div class="space y-4">

                <div class="mb-4">
                    <x-input.input name="phone_number" type="number" id="phone_number" label="Nomor Telepon"
                        value="{{ old('phone_number', $data->phone_number) }}"
                        class="block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone_number')  @enderror"
                        required oninput="checkPhoneLength(this)" />
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Foto Identitas (KTP/KK/SIM)
                    </label>
                    <img src="{{ $data->photo_identity_path ? asset('storage/' . $data->photo_identity_path) : asset('assets/img/avatar/dok.png') }}"alt="Gambar
                                                                                    Bencana"
                        class="mt-2 mr-4 mb-4 rounded-lg object-fill w-[600px] h-[250px]">
                    </a>

                    @error('image.*')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>


            </div>

        </div>
        <div class="pt-4 flex justify-center">
            <x-button.button-submit type="submit" class="px-5 py-2.5">Ubah Profile</x-button.button-submit>

        </div>
    </form>
    <form id="passwordForm" method="POST" action="{{ route('profile.update-password') }}"
        class="px-6 py-4 space-y-6 hidden">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="old_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat
                    Ini <span class="text-red-600">
                        *</span></label>
                <div class="mt-1 relative rounded ">
                    <input type="password" name="old_password" id="old_password"
                        class="block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('old_password') border-red-500 @enderror"
                        required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePassword('old_password', this)"
                            class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                @error('old_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru <span
                        class="text-red-600">
                        *</span></label>
                <div class="mt-1 relative rounded ">
                    <input type="password" name="new_password" id="new_password"
                        class="block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('new_password') border-red-500 @enderror"
                        required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePassword('new_password', this)"
                            class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="mt-1 text-sm text-gray-500">Minimal 8 karakter</p>
                @error('new_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi
                    Password Baru <span class="text-red-600">
                        *</span></label>
                <div class="mt-1 relative rounded ">
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                        class="block w-full px-4 py-2 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" onclick="togglePassword('new_password_confirmation', this)"
                            class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-center">
            <x-button.button-submit type="submit" class="px-5 py-2.5">Ubah Password</x-button.button-submit>

        </div>
    </form>
    </div>

    @push('scripts')
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
            document.getElementById('profileForm').addEventListener('submit', function(e) {
                const fileInput = document.getElementById('avatar-upload');
                const file = fileInput.files[0];

                if (file) {
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    const maxSize = 2 * 1024 * 1024;
                    if (!validTypes.includes(file.type)) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Format Tidak Didukung',
                            text: 'Hanya file JPG, JPEG dan PNG yang diperbolehkan!',
                        });
                        return;
                    }
                    if (file.size > maxSize) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Ukuran File Terlalu Besar',
                            text: 'Ukuran maksimal file adalah 2MB.',
                        });
                        return;
                    }
                }
            });
            document.addEventListener('change', function(e) {
                if (e.target.matches('input[type="file"][name="photo_identity_path"]')) {
                    const files = Array.from(e.target.files);
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];

                    for (const file of files) {
                        if (!validTypes.includes(file.type)) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Format Tidak Didukung',
                                text: `"${file.name}" bukan file gambar valid (JPEG, PNG, GIF).`,
                            });
                            e.target.value = '';
                            return;
                        }

                        if (file.size > 2 * 1024 * 1024) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Ukuran Terlalu Besar',
                                text: `"${file.name}" melebihi batas ukuran 2MB.`,
                            });
                            e.target.value = '';
                            return;
                        }
                    }
                }
            });
            const toggleFormBtn = document.getElementById('toggleFormBtn');
            const backToProfileBtn = document.getElementById('backToProfileBtn');
            const profileForm = document.getElementById('profileForm');
            const passwordForm = document.getElementById('passwordForm');

            function setActiveTab(activeBtn) {
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('border-blue-600', 'font-semibold');
                    btn.classList.add('border-transparent');
                });
                activeBtn.classList.remove('border-transparent');
                activeBtn.classList.add('border-blue-600', 'font-semibold');
            }

            toggleFormBtn.addEventListener('click', () => {
                profileForm.classList.add('hidden');
                passwordForm.classList.remove('hidden');
                setActiveTab(toggleFormBtn);
            });

            backToProfileBtn.addEventListener('click', () => {
                passwordForm.classList.add('hidden');
                profileForm.classList.remove('hidden');
                setActiveTab(backToProfileBtn);
            });

            window.addEventListener('DOMContentLoaded', () => {
                setActiveTab(backToProfileBtn);
            });

            function togglePassword(inputId, button) {
                const input = document.getElementById(inputId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                const icon = button.querySelector('svg');
                if (type === 'password') {
                    icon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                } else {
                    icon.innerHTML =
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
                }
            }
            (function() {
                'use strict'
                const forms = document.querySelectorAll('form')

                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
            })();

            document.getElementById('avatar-upload').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById('avatar-preview').src = event.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('passwordForm').addEventListener('submit', function(e) {
                const newPassword = document.getElementById('new_password').value.trim();

                if (newPassword.length <= 8) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Terlalu Pendek',
                        text: 'Password baru minimal harus lebih dari 8 karakter.',
                    });
                    return;
                }
            });
            @include('components.flash-message')
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
    @endpush
@endsection
