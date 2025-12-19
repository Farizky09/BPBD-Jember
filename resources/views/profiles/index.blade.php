@extends('layouts.master')

@section('main')
    <div class="px-3 border-b border-gray-200 flex justify-start items-center">
        <button
            class="tab-btn text-sm m-3 text-blue-600 hover:text-blue-800 font-medium focus:outline-none border-b-2 border-transparent">
            Profile
        </button>
    </div>

    <div class="px-6 py-6 space-y-1 bg-gray-50">
        <div class="px-6 pb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <label for="avatar-upload" class="cursor-pointer">
                        <img src="{{ $data->image_avatar ? asset('storage/' . $data->image_avatar) : asset('assets/img/avatar/a-sm.jpg') }}"
                            class="w-32 h-32 rounded-full object-cover shadow-md border-4 border-white" alt="Profil"
                            id="avatar-preview">
                    </label>

                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                    <x-input.input name="name" id="name" value="{{ $data->name }}" disabled="true" />
                </div>

                <div>
                    <label for="nik" class="block text-sm font-medium text-gray-600 mb-1">NIK</label>
                    <x-input.input name="nik" id="nik" value="{{ $data->nik }}" disabled="true" />
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-600 mb-1">Nomor Telepon</label>
                    <x-input.input name="phone_number" id="phone_number" value="{{ $data->phone_number }}" type="number"
                        disabled="true" />
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-600 mb-1">Username</label>
                    <x-input.input name="username" id="username" value="{{ $data->username }}" disabled="true" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Alamat Email</label>
                    <x-input.input name="email" id="email" value="{{ $data->email }}" type="email"
                        disabled="true" />
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Foto Identitas (KTP/KK/SIM)
                    </label>
                    <div class="mt-2">
                        <img src="{{ $data->photo_identity_path ? asset('storage/' . $data->photo_identity_path) : asset('assets/img/avatar/dok.png') }}"
                            alt="Foto Identitas" class="rounded-lg object-cover w-[600px] h-[250px]">
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-center">
            <a href="{{ route('profile.edit', $data->id) }}">
                <x-button.button-submit class="px-5 py-2.5">Ubah Profile</x-button.button-submit>
            </a>
        </div>
    </div>
    </div>
@endsection
