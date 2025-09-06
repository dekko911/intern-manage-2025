<?php

namespace App\Repositories\InternAttend;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\InternAttend;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InternAttendRepositoryImplement extends Eloquent implements InternAttendRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected InternAttend $model;

    private $search;

    private int $searchByMonth;

    public function __construct(InternAttend $model)
    {
        $this->model = $model;
        $this->search = request('search');
        $this->searchByMonth = request('month', today('Asia/Kuala_Lumpur')->month);
    }

    public function getDataAttend()
    {
        switch (Auth::user()->role) {
            case 'admin':
            case 'staff':
                return $this->model->latest('created_at')
                    ->with(['user:id,name'])
                    ->when(
                        $this->searchByMonth,
                        fn($m) =>
                        $m->whereMonth('created_at', $this->searchByMonth)
                    )
                    ->when(
                        $this->search,
                        fn($q) =>
                        $q->where('status', 'like', "%$this->search%")
                            ->orWhere('tanggal', 'like', "%$this->search%")
                            ->orWhere('jam_masuk', 'like', "%$this->search%")
                            ->orWhere('jam_keluar', 'like', "%$this->search")
                            ->orWhereRelation('user', 'name', 'like', "%$this->search%")
                    )->get();
            case 'intern':
                return $this->model->latest('created_at')
                    ->with(['user:id,name'])
                    ->when(
                        $this->searchByMonth,
                        fn($m) => $m->whereMonth('created_at', $this->searchByMonth)
                    )
                    ->when(
                        $this->search,
                        fn($q) =>
                        $q->where('status', 'like', "%$this->search%")
                            ->orWhere('tanggal', 'like', "%$this->search%")
                            ->orWhere('jam_masuk', 'like', "%$this->search%")
                            ->orWhere('jam_keluar', 'like', "%$this->search")
                            ->orWhereRelation('user', 'name', 'like', "%$this->search%")
                    )->where('user_id', Auth::id())->get();
            default;
        }
    }

    public function checkDataDoubleAttendIfExists(): bool
    {
        return $this->model->where('user_id', Auth::user()->id)
            ->where('tanggal', today('Asia/Kuala_Lumpur')->toDateString())
            ->exists();
    }
}
