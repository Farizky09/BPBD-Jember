<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // $id = $this->route('reports') ?? $this->route('id');
        return [
            'id_category' => 'required|exists:disaster_categories,id',
            'latitude' => 'required|string|max:50',
            'longitude' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'subdistrict' => 'required|string|max:100',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'id_category.required' => 'Kategori bencana wajib diisi.',
            'id_category.exists' => 'Kategori bencana tidak valid.',
            'latitude.required' => 'Latitude wajib diisi.',
            'latitude.string' => 'Latitude harus berupa teks.',
            'latitude.max' => 'Latitude maksimal 50 karakter.',
            'longitude.required' => 'Longitude wajib diisi.',
            'longitude.string' => 'Longitude harus berupa teks.',
            'longitude.max' => 'Longitude maksimal 50 karakter.',
            'address.required' => 'Alamat wajib diisi.',
            'address.string' => 'Alamat harus berupa teks.',
            'address.max' => 'Alamat maksimal 255 karakter.',
            'subdistrict.required' => 'Kecamatan wajib diisi.',
            'subdistrict.string' => 'Kecamatan harus berupa teks.',
            'subdistrict.max' => 'Kecamatan maksimal 100 karakter.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'images.*.image' => 'Setiap file harus berupa gambar.',
            'images.*.mimes' => 'Format gambar harus jpeg, png, jpg, gif, atau svg.',
            'images.*.max' => 'Ukuran gambar maksimal 2048 KB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'id_category' => 'Kategori Bencana',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'address' => 'Alamat',
            'subdistrict' => 'Kecamatan',
            'description' => 'Deskripsi',
            'images.*' => 'Gambar',
        ];
    }
}
