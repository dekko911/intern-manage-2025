<?php

namespace App\Repositories\InternAttend;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\InternAttend;
use Illuminate\Database\Eloquent\Model;

class InternAttendRepositoryImplement extends Eloquent implements InternAttendRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected InternAttend $model;
    private $search;

    public function __construct(InternAttend $model)
    {
        $this->model = $model;
        $this->search = request('search');
    }

    public function getDataAttend()
    {
        return $this->model->latest('created_at')->with(['user'])->where(function ($q) {
            if ($this->search) {
                $q->where('status', 'like', "%$this->search%")
                    ->orWhere('tanggal', 'like', "%$this->search%")
                    ->orWhere('jam_masuk', 'like', "%$this->search%")
                    ->orWhere('jam_keluar', 'like', "%$this->search")
                    ->orWhereRelation('user', 'name', 'like', "%$this->search%");
            }
        })->get();
    }

    public function updateAttend(array $attributes)
    {
        return $this->model->update($attributes);
    }
}