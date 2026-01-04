<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\UserManagementInterfaces;
use App\Models\BanHistories;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Role;

class UserManagementRepository implements UserManagementInterfaces
{
    private $user;
    private $role;
    private $permission;


    public function __construct(User $user, Role $role, Permission $permission)
    {
        $this->user = $user;
        $this->role = $role;
        $this->permission = $permission;
    }

    public function get()
    {
        return $this->user->orderBy('id', 'desc')->get();
    }

    public function getById($id)
    {
        return $this->user->find($id);
    }

    public function store($data)
    {
        DB::beginTransaction();
        try {
            $pass = Hash::make('password');
            $user = $this->user->create(array_merge($data, ['password' => $pass]));
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }

        try {
            $role = $this->role->find($data['role']);
            $user->assignRole($role);
        } catch (\Throwable $th) {
            throw $th;
        }
        DB::commit();
        // return $this->user->create($data);
    }

    public function show()
    {
        return $this->user->all();
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->find($id);
            if (!empty($data)) {
                $filtered = collect($data)->except('role')->toArray();
                if (!empty($filtered)) {
                    $user->update($filtered);
                }
            }
            if (isset($data['role'])) {
                $role = $this->role->findById($data['role']);
                $user->syncRoles([$role]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        DB::commit();
    }

    public function updatePermission($data, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->user->find($id);
            $user->syncPermissions($data['permission']);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        DB::commit();
    }
    public function delete($id)
    {
        return $this->user->find($id)->delete();
    }



    public function datatable()
    {
        $startDate = request()->start_date;
        $endDate = request()->end_date;
        $status = request()->status;
        $role = request()->role;

        $query = DB::table('users')
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone_number',
                'users.is_banned',
                'users.is_active',
                'roles.name as role_name',
                'users.created_at'
            );


        if ($status) {
            if ($status === 'aktif') {
                $query->where('users.is_banned', 'none')->where('users.is_active', true);
            } elseif ($status === 'nonaktif') {
                $query->where('users.is_banned', 'none')->where('users.is_active', false);
            } elseif ($status === 'sementara') {
                $query->where('users.is_banned', 'temporary');
            } elseif ($status === 'permanen') {
                $query->where('users.is_banned', 'permanent');
            }
        }

        if ($role) {
            $query->where('roles.name', $role);
        }

        if ($startDate && $endDate) {
            if ($startDate == $endDate) {
                $query->whereDate('users.created_at', $startDate);
            } else {
                $query->whereBetween('users.created_at', [
                    $startDate . ' 00:00:00',
                    $endDate . ' 23:59:59'
                ]);
            }
        }

        return $query->orderBy('users.created_at', 'desc')->get();
    }

    public function banUser($id)
    {

        DB::beginTransaction();
        try {
            $data = $this->user->find($id);

            $data->update([
                'is_banned' => 'permanent',
                'poin' => 0
            ]);
            BanHistories::create([
                'user_id' => $data->id,
                'banned_at' => now(),
                'is_permanent_ban' => true
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }

    public function unBanUser($id)
    {
        DB::beginTransaction();
        try {
            $data = $this->user->find($id);

            $data->update([
                'is_banned' => 'none',
                'poin' => 100

            ]);
            BanHistories::where('user_id', $data->id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
    }
}
