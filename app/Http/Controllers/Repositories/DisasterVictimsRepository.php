<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\DisasterVictimsInterfaces;
use App\Models\DisasterVictims;
use App\Models\ImpactType;

class DisasterVictimsRepository implements DisasterVictimsInterfaces
{
    private $disasterVictims;
    private $impactType;
    public function __construct(DisasterVictims $disasterVictims, ImpactType $impactType)
    {
        $this->disasterVictims = $disasterVictims;
        $this->impactType = $impactType;
    }

    public function get()
    {
        return $this->disasterVictims->all();
    }
    public function datatable()
    {
        return $this->disasterVictims->with('disasterImpact')->orderByDesc('created_at')->get();
    }
    public function getById($id)
    {
        return $this->disasterVictims->findOrFail($id);
    }
    // public function store($data)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $role = $this->role->create(['name' => $data['name']]);
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         throw $th;
    //     }
    //     try {
    //         foreach ($data['permissions'] as $permission) {
    //             $role->givePermissionTo($permission);
    //         }
    //     } catch (\Throwable $th) {
    //         throw $th;
    //         DB::rollBack();
    //     }
    //     DB::commit();
    // }

    public function store($data)
    {
        $dataVictims = $this->disasterVictims->create($data);
        try {
            if (isset($data['impact_types']) && is_array($data['impact_types'])) {

                $impactTypeIds = $this->impactType
                    ->whereIn('name', $data['impact_types'])
                    ->pluck('id')
                    ->toArray();


                $dataVictims->impactTypes()->sync($impactTypeIds);
            }
            return $dataVictims;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function update($data, $id)
    {
        $dataVictims = $this->disasterVictims->findOrFail($id);
        $dataAwal = array_diff_key($data, ['impact_types' => []]);
        $dataVictims->update($dataAwal);


        if (isset($data['impact_types']) && is_array($data['impact_types'])) {
            $impactTypeIds = $this->impactType
                ->whereIn('name', $data['impact_types'])
                ->pluck('id')
                ->toArray();

            $dataVictims->impactTypes()->sync($impactTypeIds);
        }

        return $dataVictims;
    }
    public function delete($id)
    {
        $victim = $this->disasterVictims->findOrFail($id);
        return $victim->delete();
    }


}
