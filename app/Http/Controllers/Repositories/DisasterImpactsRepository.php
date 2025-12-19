<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\DisasterImpactsInterfaces;
use App\Models\DisasterImpacts;

class DisasterImpactsRepository implements DisasterImpactsInterfaces
{
    private $disasterImpacts;

    public function __construct(DisasterImpacts $disasterImpacts)
    {
        $this->disasterImpacts = $disasterImpacts;
    }

    public function get()
    {
        return $this->disasterImpacts->get();
    }

    public function getById($id)
    {
        return $this->disasterImpacts->find($id);
    }

    public function store($data)
    {
        return $this->disasterImpacts->create($data);
    }

    public function update($id, $data)
    {
        $impact = $this->disasterImpacts->find($id);
        return $impact->update($data);
    }

    public function delete($id)
    {
        return $this->disasterImpacts->destroy($id);
    }

    public function datatable()
    {
        return $this->disasterImpacts->with('confirmReport')->orderByDesc('created_at')->get();
    }
}
