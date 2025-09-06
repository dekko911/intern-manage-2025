<?php

namespace App\Repositories\CoD;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\CallOfDuty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CoDRepositoryImplement extends Eloquent implements CoDRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected CallOfDuty $model;

    protected $search;

    protected string $searchByDay;

    public function __construct(CallOfDuty $model)
    {
        $this->model = $model;
        $this->search = request('search');
        $this->searchByDay = request('day', today('Asia/Kuala_Lumpur')->translatedFormat('l'));
    }

    public function getDataCoD()
    {
        return $this->model->latest('created_at')->with(['user:id,name,photo'])
            ->when(
                $this->searchByDay,
                fn($d) =>
                $d->whereDay('days', $this->searchByDay)
            )
            ->when(
                $this->search,
                fn($q) =>
                $q->where('days', 'like', "%$this->search%")
                    ->orWhereRelation('user', 'name', 'like', "%$this->search%")
            )->get();
    }

    public function checkDataDoubleCoDIfExists(): bool
    {
        return $this->model->where('user_id', Auth::user()->id)
            ->where('days', today('Asia/Kuala_Lumpur')->translatedFormat('l'))
            ->exists();
    }
}
