<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\DisasterCategoryInterfaces;

use App\Models\DisasterCategory;

class DisasterCategoryRepository implements DisasterCategoryInterfaces
{
    private $disasterCategory;

    public function __construct(DisasterCategory $disasterCategory)
    {
        $this->disasterCategory = $disasterCategory;
    }

    public function get()
    {
        return $this->disasterCategory->get();
    }

    public function getByid($id)
    {
        return $this->disasterCategory->find($id);
    }

    public function store($data)
    {
        return $this->disasterCategory->create($data);
    }

    public function update($data, $id)
    {
        $category = $this->disasterCategory->find($id);
        return $category->update($data);
    }

    public function delete($id)
    {
        return $this->disasterCategory->destroy($id);
    }

    public function datatable()
    {
        return $this->disasterCategory->orderBy('created_at', 'desc')->get();
    }
}
