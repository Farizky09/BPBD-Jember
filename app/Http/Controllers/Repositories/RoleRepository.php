<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\RoleInterfaces;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleInterfaces
{
    private $role;
    private $permission;

    public function __construct(Role $role, Permission $permission)
    {

        $this->role = $role;
        $this->permission = $permission;
    }
    public function get()
    {
        return $this->role->get();
    }

    public function getById($id)
    {
        return $this->role->find($id);
    }
    public function getByName($name)
    {
        return $this->role->where('name', $name)->first();
    }
    public function store($data)
    {
        DB::beginTransaction();
        try {
            $role = $this->role->create(['name' => $data['name']]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        try {
            foreach ($data['permissions'] as $permission) {
                $role->givePermissionTo($permission);
            }
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        DB::commit();
    }

    public function show()
    {
        return $this->role->all();
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $role = $this->role->find($id);
            $role->update(['name' => $data['name']]);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        try {
            $role->syncPermissions($data['permissions']);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        DB::commit();
    }
    public function delete($id)
    {
        try {
            $role = $this->role->find($id);
            $role->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function datatable()
    {
        $startDate = request()->start_date;
        $endDate = request()->end_date;
        return DB::table('roles')
            ->select('id', 'name', 'created_at')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                if ($startDate == $endDate) {
                    $query->whereDate('created_at', $startDate);
                } else {
                    $query->whereBetween('created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }
            })->orderBy('created_at', 'desc')
            ->get();
    }
}
