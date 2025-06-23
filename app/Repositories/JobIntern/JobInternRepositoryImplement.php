<?php

namespace App\Repositories\JobIntern;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\JobIntern;
use Illuminate\Database\Eloquent\Model;

class JobInternRepositoryImplement extends Eloquent implements JobInternRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected JobIntern $model;
    private $search;

    public function __construct(JobIntern $model)
    {
        $this->model = $model;
        $this->search = request('search');
    }

    public function getDataInternJob()
    {
        return $this->model->latest('created_at')->with(['user'])->where(function ($q) {
            if ($this->search) {
                $q->where('task', 'like', "%$this->search%")
                    ->orWhere('created', 'like', "%$this->search%")
                    ->orWhere('description', 'like', "%$this->search%")
                    ->orWhere('status', 'like', "%$this->search%")
                    ->orWhereRelation('user', 'name', 'like', "%$this->search%");
            }
        })->get();
    }

    public function updateInternJob(array $attributes)
    {
        return $this->model->update($attributes);
    }
}
