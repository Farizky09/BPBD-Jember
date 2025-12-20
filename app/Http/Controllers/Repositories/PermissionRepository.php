<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\PermissionInterfaces;
use Illuminate\Support\Facades\DB;
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
        $startDate = request()->start_date;
        $endDate = request()->end_date;
        return DB::table('permissions')
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
