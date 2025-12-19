<?php

namespace App\Repositories;
use App\Interfaces\InfografisInterface;
use App\Models\Infografis;
class InfografisRepository implements InfografisInterface
{
    private $infografis;
    public function __construct(Infografis $infografis)
    {
        $this->infografis = $infografis  ;
    }

    public function get(){
        return $this->infografis->all()->sortBy('id');
    }
    public function getById($id) {
        return $this->infografis->find($id);
    }

    public function store($data) {
        $this->infografis->create($data);
    }

    public function update($id, $data) {
        $infografis = $this->infografis->find($id);
        $infografis->update($data);
    }

    public function delete($id) {
        $infografis = $this->infografis->find($id);
        $infografis->delete();
    }
}
