<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\PermissionInterfaces;
use App\Http\Controllers\Interfaces\RoleInterfaces;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $role;
    private $permission;
    public function __construct(RoleInterfaces $role, PermissionInterfaces $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->role->datatable())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    return view('role.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('role.index');
    }

    public function getById($id)
    {
        return $this->role->getById($id);
    }

    public function create()
    {
        $permissions = $this->permission->get();
        return view('role.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:roles',
            'permissions' => 'required',
        ]);
        try {
            $data = $request->all();
            $this->role->store($data);
            return redirect()->route('role.index')->with('success', 'Role berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->route('role.create')->with('error', 'Role gagal ditambahkan' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $data = $this->role->getById($id);
        $permissions = $this->permission->get();
        return view('role.edit', compact('data', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        // $validate = $request->validate([
        //     'name' => 'required|unique:roles',
        //     'permissions' => 'required',
        // ]);
        try {
            $data = $request->all();
            $this->role->update($data, $id);
            return redirect()->route('role.index')->with('success', 'Role berhasil diupdate');
        } catch (\Throwable $th) {
            return redirect()->route('role.edit', $id)->with('error', 'Role gagal diupdate' . $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->role->delete($id);
            return response()->json(['status' => 'success', 'message' => 'Role berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Role gagal dihapus' . $th->getMessage()]);
        }
    }
}
