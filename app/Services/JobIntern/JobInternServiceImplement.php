<?php

namespace App\Services\JobIntern;

use App\Enums\CheckJobStatus;
use App\Models\TempJobIntern;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\JobIntern\JobInternRepository;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobInternServiceImplement extends ServiceApi implements JobInternService
{
    /**
     * set title message api for CRUD
     * @param string $title
     */
    private string $title_job = "Data";
    private string $create_message_job = "berhasil dibuat";
    private string $update_message_job = "berhasil diubah";
    private string $delete_message_job = "berhasil dihapus";

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected JobInternRepository $mainRepository;

    private Request $request;

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
                'task' => ['required'],
                'description' => ['required'],
                'deadline' => ['nullable'],
            ]);

            $status = $this->request->enum('status', CheckJobStatus::class);

            switch (Auth::user()->role) {
                case 'admin':
                    $data = $this->mainRepository->create([
                        'user_id' => $this->request->user_id,
                        'created' => today('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y'),
                        'task' => $this->request->task,
                        'description' => $this->request->description,
                        'deadline' => $this->request->deadline ?? CarbonImmutable::createFromDate(0001, 1, 1, 'Asia/Kuala_Lumpur'),
                        'status' => $status,
                    ]);

                    TempJobIntern::create([
                        'job_intern_id' => $data->id,
                        'user_id' => $data->user_id,
                        'created' => $data->created,
                        'task' => $data->task,
                        'description' => $data->description,
                        'deadline' => $data->deadline,
                        'status' => $data->status,
                        'expired_at' => now('Asia/Kuala_Lumpur')->addWeek(),
                    ]);
                    break;
                case 'staff':
                    $data = $this->mainRepository->create([
                        'user_id' => $this->request->user_id, // ingat ubah
                        'created' => today('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y'),
                        'task' => $this->request->task,
                        'description' => $this->request->description,
                        'deadline' => $this->request->deadline ?? CarbonImmutable::createFromDate(0001, 1, 1, 'Asia/Kuala_Lumpur'),
                        'status' => CheckJobStatus::PENDING, // jaga" line ini, bakal diganti pakai enum PROGRESS
                    ]);

                    TempJobIntern::create([
                        'job_intern_id' => $data->id,
                        'user_id' => $data->user_id,
                        'created' => $data->created,
                        'task' => $data->task,
                        'description' => $data->description,
                        'deadline' => $data->deadline,
                        'status' => $data->status,
                        'expired_at' => now('Asia/Kuala_Lumpur')->addWeek(),
                    ]);
                    break;
                case 'intern':
                    $data = $this->mainRepository->create([
                        'user_id' => Auth::id(), // ingat ubah
                        'created' => today('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y'),
                        'task' => $this->request->task,
                        'description' => $this->request->description,
                        'deadline' => CarbonImmutable::createFromDate(0001, 1, 1, 'Asia/Kuala_Lumpur'),
                        'status' => CheckJobStatus::PENDING, // jaga" line ini, bakal diganti pakai enum PROGRESS
                    ]);

                    TempJobIntern::create([
                        'job_intern_id' => $data->id,
                        'user_id' => $data->user_id,
                        'created' => $data->created,
                        'task' => $data->task,
                        'description' => $data->description,
                        'deadline' => $data->deadline,
                        'status' => $data->status,
                        'expired_at' => now('Asia/Kuala_Lumpur')->addWeek(),
                    ]);
                    break;
                default;
            }

            return $this->setCode(200)
                ->setMessage("$this->title_job $this->create_message_job & ditandai di temp_job_interns!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function updateInternJob($id)
    {
        try {
            $this->request->validate([
                'task' => ['required'],
                'description' => ['required'],
                'deadline' => ['required'],
            ]);

            $status = $this->request->enum('status', CheckJobStatus::class);

            $data = $this->mainRepository->update($id, [
                'user_id' => $this->request->user_id,
                'created' => today('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y'),
                'task' => $this->request->task,
                'description' => $this->request->description,
                'deadline' => $this->request->deadline,
            ]);

            if ($this->request->status) {
                $data = $this->mainRepository->update($id, ['status' => $status]);
            }

            if ($id) {
                TempJobIntern::where('job_intern_id', $id)->delete();

                TempJobIntern::create([
                    'job_intern_id' => $id,
                    'user_id' => $this->request->user_id,
                    'created' => today('Asia/Kuala_Lumpur')->isoFormat('dddd, DD MMMM Y'),
                    'task' => $this->request->task,
                    'description' => $this->request->description,
                    'deadline' => $this->request->deadline,
                    'status' => $status,
                ]);
            }

            return $this->setCode(200)
                ->setMessage("$this->title_job $this->update_message_job & ditandai di temp_job_interns!")
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
                ->setMessage("$this->title_job $this->delete_message_job!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
