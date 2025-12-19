<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\PermissionInterfaces;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private $permission;

    public function __construct(PermissionInterfaces $permission)
    {
        $this->permission = $permission;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables()
                ->of($this->permission->datatable())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    return view('permission.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('permission.index');
    }

    public function getById($id)
    {
        return $this->permission->getById($id);
    }

    public function create()
    {
        return view('permission.create');
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|unique:permissions',
        ]);
        try {
            $data = $request->all();
            $this->permission->store($data);
            return redirect()->route('permission.index')->with('success', 'Permission berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->route('permission.create')->with('error', 'Permission gagal ditambahkan' . $th->getMessage());
        }
    }

    public function edit($id)
    {
        $data = $this->permission->getById($id);
        return view('permission.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'name' => 'required|unique:permissions',
        ]);
        try {
            $data = $request->all();
            $this->permission->update($data, $id);
            return redirect()->route('permission.index')->with('success', 'Permission berhasil diupdate');
        } catch (\Throwable $th) {
            return redirect()->route('permission.edit', $id)->with('error', 'Permission gagal diupdate' . $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->permission->delete($id);
            return response()->json(['success' => 'Permission berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Permission gagal dihapus' . $th->getMessage()]);
        }
    }



}
