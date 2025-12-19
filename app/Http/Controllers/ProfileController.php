<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\ProfileInterfaces;
use App\Models\cr;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\RelsRibbon;


class ProfileController extends Controller
{
    protected $profile;
    public function __construct(ProfileInterfaces $profile)
    {
        $this->profile = $profile;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->profile->getById(Auth::user()->id);
        return view('profiles.index', compact('data'));
    }

    public function edit()
    {
        $data = $this->profile->getById(Auth::user()->id);
        return view('profiles.edit', compact(var_name: 'data'));
    }
    public function update(Request $request)
    {
        $idUser = Auth::user()->id;
        $user = Auth::user();
        // return $request->all();
        FacadesDB::table('reset_password_tokens')->where('email', $user->email)->delete();
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $idUser,
            'phone_number' => ['required', 'min:10', Rule::unique('users', 'phone_number')->ignore($idUser)],
            'image_avatar' => 'nullable|image|mimes:png,jpg|max:2048',
            'nik' => 'nullable|string|max:255',
            'photo_identity_path' => 'nullable|image|mimes:png,jpg|max:2048',
        ], [
            'phone_number.unique' => 'Nomor telepon sudah terdaftar',
            'phone_number.min' => 'Nomor telepon harus terdiri minimal 10 digit',
        ]);
        try {
            $dataToUpdate = [];
            foreach (['name', 'email', 'phone_number', 'nik'] as $field) {
                if ($validatedData[$field] !== $user->$field) {
                    $dataToUpdate[$field] = $validatedData[$field];
                }
            }
            if ($request->hasFile('image_avatar')) {
                if (Auth::user()->avatar) {
                    Storage::delete('image_avatar/' . basename(Auth::user()->avatar));
                }

                $folderpath = 'image_avatar/' . now()->format('Y-m-d') . '/' . Auth::user()->id;
                $path = $request->file('image_avatar')->store($folderpath, 'public');
                $dataToUpdate['image_avatar'] = $path;
            }
            if ($request->hasFile('photo_identity_path')) {
                if (Auth::user()->photo_identity_path) {
                    // Storage::delete(Auth::user()->photo_identity_path);
                    Storage::disk('public')->delete(Auth::user()->photo_identity_path);
                }

                $folderpath = 'photo_identity_path/' . now()->format('Y-m-d') . '/' . Auth::user()->id;
                $path = $request->file('photo_identity_path')->store($folderpath, 'public');
                $dataToUpdate['photo_identity_path'] = $path;
            }
            if (!empty($dataToUpdate)) {
                $this->profile->updateProfile($idUser, $dataToUpdate);
            }
            // $this->profile->updateProfile($idUser, $validatedData);

            return redirect()->route('profile.edit')
                ->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('profile.edit')
                ->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {

        $validatedData = $request->validate([
            'new_password' => 'required|min:8|confirmed',
            'old_password' => 'required',
        ]);

        try {
            if (!$this->profile->checkOldPassword(Auth::user()->id, $request->old_password)) {
                return redirect()->route('profile.edit', Auth::user()->id)->with('error', 'Password lama tidak sesuai');
            }

            $this->profile->updateProfile(Auth::user()->id, [
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->route('profile.edit',  Auth::user()->id)->with('error', 'Terjadi kesalahan saat memperbarui password: ' . $th->getMessage());
        }
    }
}
