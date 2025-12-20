<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\PermissionInterfaces;
use App\Http\Controllers\Interfaces\RoleInterfaces;
use App\Http\Controllers\Interfaces\UserManagementInterfaces;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    private $userManagement;
    private $role;
    // private $permission;

    public function __construct(UserManagementInterfaces $userManagement, RoleInterfaces $role)
    {
        $this->userManagement = $userManagement;
        $this->role = $role;
        // $this->permission = $permission;
    }

    public function index(Request $request)
    {
        // return $this->userManagement->get();
        if ($request->ajax()) {
            return datatables()
                ->of($this->userManagement->datatable())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('email', function ($data) {
                    return $data->email;
                })
                ->addColumn('phone_number', function ($data) {
                    return $data->phone_number;
                })
                ->addColumn('role', function ($data) {
                    if (method_exists($data, 'getRoleNames')) {
                        return ucwords(str_replace('_', ' ', $data->getRoleNames()->first()));
                    }
                    return 'N/A';
                })
                ->addColumn('status', function ($data) {
                    if ($data->is_banned === "none") {
                        $isActive = isset($data->is_active) ? $data->is_active : true;
                        return $isActive
                            ? '<span class="badge bg-success">Aktif</span>'
                            : '<span class="badge bg-secondary">Tidak Aktif</span>';
                    } elseif ($data->is_banned === "temporary") {
                        return '<span class="badge bg-warning text-dark">Ban Sementara</span>';
                    } elseif ($data->is_banned === "permanent") {
                        return '<span class="badge bg-danger">Ban Permanen</span>';
                    } else {
                        return '<span class="badge bg-info">Status Tidak Diketahui</span>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return view('user-management.column.action', compact('data'));
                })
                ->rawColumns(['status', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('user-management.index');
    }

    public function getById($id)
    {
        return response()->json($this->userManagement->getById($id));
    }
    public function create()
    {
        $role = $this->role->get();
        return view('user-management.create', compact('role'));
    }
    public function store(Request $request)
    {

        $validate = $request->validate(
            [
                'name' => 'required',
                'email' => 'required|unique:users',
                'phone_number' => ['required', 'min:10', 'unique:users,phone_number'],
                'role' => 'required',

            ],
            [
                'phone_number.min' => 'Nomor telepon harus terdiri minimal dari 10 digit.',
                'phone_number.unique' => 'Nomor telepon sudah terdaftar.',
            ]
        );
        try {
            $request['username'] = '-';
            $data = $request->all();
            // $data['password'] = Hash::make($request->password);
            $this->userManagement->store($data);
            return redirect()->route('user-management.index')->with('success', 'Data User berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('user-management.create')->with('error', 'Data User gagal ditambahkan' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data = $this->userManagement->getById($id);
        // $role = $data->roles->pluck('name');
        $role = $this->role->get();
        // $userRoleId= $data->roles->pluck('id')->toArray();
        return view('user-management.edit', compact('data', 'role'));
    }

    public function update(Request $request, $id)
    {
        $user = $this->userManagement->getById($id);
        DB::table('reset_password_tokens')->where('email', $user->email)->delete();
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => ['required', 'min:10', Rule::unique('users', 'phone_number')->ignore($user)],
            'role' => 'required',
        ], [
            'phone_number.min' => 'Nomor telepon harus terdiri minimal dari 10 digit.',
            'phone_number.unique' => 'Nomor telepon sudah terdaftar.',
        ]);
        try {
            $updateData = [];
            foreach ($validate as $key => $value) {
                if ($user->$key != $value) {
                    $updateData[$key] = $value;
                }
            }
            $newRoleId = $request->input('role');
            $oldRoleId = optional($user->roles->first())->id;
            if ($newRoleId != $oldRoleId) {
                $updateData['role'] = $newRoleId;
            }
            $this->userManagement->update($updateData, $id);

            return redirect()->route('user-management.index')->with('success', 'Data User berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('user-management.edit', $id)->with('error', 'Data User gagal diubah');
        }
    }

    public function delete($id)
    {
        try {
            $this->userManagement->delete($id);

            return response()->json(['status' => 'success', 'message' => 'Data user berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Data user gagal dihapus' . $th->getMessage()]);
        }
    }

    public function updatePermission(Request $request, $id)
    {
        $request->validate([
            'permission' => 'required',
        ]);
        try {
            $this->userManagement->updatePermission($request->permission, $id);
            return redirect()->route('user-management.index')->with('success', 'Permission berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->route('user-management.index')->with('error', 'Permission gagal diubah' . $th->getMessage());
        }
    }

    public function resetPassword($id)
    {
        $user = $this->userManagement->getById($id);
        // dd($user);

        try {
            $user->password = Hash::make('password');
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'Password berhasil direset']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Password gagal direset' . $th->getMessage()]);
        }
    }

    public function banUser($id)
    {
        try {
            $this->userManagement->banUser($id);
            return response()->json(['status' => 'success', 'message' => 'Akun berhasil di blokir']);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => 'Akun gagal di blokir']);
        }
    }
    public function unBanUser($id)
    {
        try {
            $this->userManagement->unBanUser($id);
            return response()->json(['status' => 'success', 'message' => 'Akun berhasil dibuka blokir']);
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => 'error', 'message' => 'Akun gagal di buka blokir']);
        }
    }
}
