<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\PermissionInterfaces;
use Spatie\Permission\Models\Permission;


class PermissionRepository implements PermissionInterfaces
{

    private $permission;

    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }
    public function get()
    {
        return $this->permission->get();
    }

    public function getById($id)
    {
        return $this->permission->find($id);
    }

    public function store($data)
    {
        return $this->permission->create($data);
    }

    public function show()
    {
        return $this->permission->all();
    }
    public function update($data, $id)
    {
        $permission = $this->permission->find($id);
        $permission->update($data);
    }
    public function delete($id)
    {
        $permission = $this->permission->find($id);
        $permission->delete();
    }
    public function datatable()
    {
        return $this->permission->orderBy('id', 'desc')->get();
    }
}
