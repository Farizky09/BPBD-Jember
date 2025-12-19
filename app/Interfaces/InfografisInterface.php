<?php

namespace App\Interfaces;

interface InfografisInterface
{
    public function get();
    public function getById($id);
    public function store($data);
    public function update($id,$data);
    public function delete($id);
}
