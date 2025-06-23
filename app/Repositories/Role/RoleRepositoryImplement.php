<?php

namespace App\Repositories\Role;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class RoleRepositoryImplement extends Eloquent implements RoleRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected Role $model;
    private $search;

    public function __construct(Role $model)
    {
        $this->model = $model;
        $this->search = request('search');
    }

    public function getDataRole()
    {
        return $this->model->latest('created_at')->with(['user'])->where(function ($q) {
            if ($this->search) {
                $q->where('name', 'like', "%$this->search%")
                    ->orWhereRelation('user', 'name', 'like', "%$this->search%");
            }
        })->get();
    }
    public function createRole(array $attributes)
    {
        return $this->model->create($attributes);
    }
}
