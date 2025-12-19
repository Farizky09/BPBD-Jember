<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\NewsInterfaces;
use App\Models\ImageNews;
use App\Models\News;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NewsRepository implements NewsInterfaces
{
    private $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public function get()
    {
        return $this->news->get();
    }

    public function getById($id)
    {
        return $this->news->find($id);
    }
    public function show($id)
    {
        return $this->news->with(['imageNews', 'confirmReports'])->find($id);
    }
    public function store($data)
    {
        return $this->news->create($data);
    }
    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $news = $this->news->where('id', $id);
            $news->update($data);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        DB::commit();
    }
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $news = $this->news->find($id);

            foreach ($news->imageNews as $image) {
                $imageData = ImageNews::find($image->id);
                if ($imageData) {
                    Storage::disk('public')->delete($imageData->image_path);
                    $imageData->delete();
                }
            }
            $news->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function datatable()
    {
        $startDate = request()->start_date;
        $endDate = request()->end_date;
        $status = request()->status;

        $data = $this->news->with(['imageNews', 'confirmReports'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
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
        return $data;
    }
    public function publish($id)
    {
        $news = $this->news->find($id);
        return $news->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
    public function takedown($id)
    {
        $news = $this->news->find($id);
        return $news->update([
            'status' => 'takedown',
            'takedown_at' => now(),
        ]);
    }
}
