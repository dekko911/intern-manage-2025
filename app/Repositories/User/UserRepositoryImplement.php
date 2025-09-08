<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserRepositoryImplement extends Eloquent implements UserRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent.
     * Don't remove or change $this->model variable name.
     * @property Model|mixed $model;
     */
    protected User $model;

    protected $search;

    public function __construct(User $model)
    {
        $this->model = $model;
        $this->search = request('search');
    }

    public function getDataUser()
    {
        return $this->model->latest('created_at')->when(
            $this->search,
            fn($q) =>
            $q->where('name', 'like', "%$this->search%")
                ->orWhere('email', 'like', "%$this->search%")
                ->orWhere('instansi', 'like', "%$this->search%")
                ->orWhere('periode', 'like', "%$this->search%")
                ->orWhere('role', 'like', "%$this->search%")
        )->get();
    }

    public function checkRoleDoubleAdminIfExists(): bool
    {
        return $this->model->where('role', '=', "admin")->exists();
    }
}
