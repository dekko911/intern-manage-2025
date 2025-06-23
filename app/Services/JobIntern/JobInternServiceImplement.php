<?php

namespace App\Services\JobIntern;

use App\Enums\CheckJobStatus;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\JobIntern\JobInternRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobInternServiceImplement extends ServiceApi implements JobInternService
{
    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title_job = "data";
    protected string $create_message_job = "berhasil dibuat";
    protected string $update_message_job = "berhasil diubah";
    protected string $delete_message_job = "berhasil dihapus";


    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected JobInternRepository $mainRepository;

    protected Request $request;

    public function __construct(JobInternRepository $mainRepository, Request $request)
    {
        $this->mainRepository = $mainRepository;
        $this->request = $request;
    }

    public function getDataInternJob()
    {
        try {
            $data = $this->mainRepository->getDataInternJob();
            return $this->setCode(200)
                ->setMessage("OK")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getDataJobInternById($id)
    {
        try {
            $data = $this->mainRepository->findOrFail($id);
            return $this->setCode(200)
                ->setMessage("OK")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function createInternJob()
    {
        try {
            $this->request->validate([
                'created' => ['required'],
                'task' => ['required'],
                'description' => ['required'],
                'deadline' => ['nullable'],
            ]);

            $data = $this->mainRepository->create([
                'user_id' => Auth::id(),
                'created' => $this->request->created,
                'task' => $this->request->task,
                'description' => $this->request->description,
                'deadline' => $this->request->deadline ?? null, // cari alternative nya, jangan pakai null.
                'status' => CheckJobStatus::PENDING,
            ]);

            return $this->setCode(200)
                ->setMessage("$this->title $this->create_message_job!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function updateInternJob()
    {
        try {
            $this->request->validate([
                'created' => ['required'],
                'task' => ['required'],
                'description' => ['required'],
                'deadline' => ['required'],
            ]);

            $status = $this->request->enum('status', CheckJobStatus::class);

            $data = $this->mainRepository->updateInternJob([
                'user_id' => $this->request->user_id,
                'created' => $this->request->created,
                'task' => $this->request->task,
                'description' => $this->request->description,
                'deadline' => $this->request->deadline,
            ]);

            if ($this->request->status) {
                $data = $this->mainRepository->updateInternJob(['status' => $status]);
            }

            return $this->setCode(200)
                ->setMessage("$this->title $this->update_message_job!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function deleteJobInternById($id)
    {
        try {
            $data = $this->mainRepository->delete($id);
            return $this->setCode(200)
                ->setMessage("$this->title $this->delete_message_job!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
