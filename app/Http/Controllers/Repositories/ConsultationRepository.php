<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\ConsultationInterfaces;

use App\Models\Consultation;
use App\Models\DisasterCategory;
use Illuminate\Support\Facades\Storage;

class ConsultationRepository implements ConsultationInterfaces
{
    private $consultation;

    public function __construct(Consultation $consultation)
    {
        $this->consultation = $consultation;
    }

    public function get()
    {
        return $this->consultation->get();
    }
    public function getById($id)
    {
        return $this->consultation->find($id);
    }
    public function store($data)
    {
        return $this->consultation->create($data);
    }
    public function update($id, $data)
    {
        $consultation = $this->consultation->findOrFail($id);
        $consultation->update($data);
    }
    public function delete($id)
    {
        $consultation = $this->consultation->find($id);
        if ($consultation->video_path) {
            Storage::disk('public')->delete($consultation->video_path);
        }

        $consultation->delete();
    }
    public function datatable()
    {
        return $this->consultation->with('consultations')->get();
    }
}
