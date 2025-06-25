<?php

namespace App\Services\InternAttend;

use App\Enums\CheckAttendStatus;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\InternAttend\InternAttendRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class InternAttendServiceImplement extends ServiceApi implements InternAttendService
{
    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title_intern = "Absen";
    protected string $create_message_intern = "berhasil dibuat";
    protected string $update_message_intern = "berhasil diubah";
    protected string $delete_message_intern = "berhasil dihapus";

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected InternAttendRepository $mainRepository;
    protected Request $request;

    public function __construct(InternAttendRepository $mainRepository, Request $request)
    {
        $this->mainRepository = $mainRepository;
        $this->request = $request;
    }

    public function getDataAttend()
    {
        try {
            $data = $this->mainRepository->getDataAttend();
            return $this->setCode(200)
                ->setMessage("OK")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getInternAttendById($id)
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

    public function createAttend()
    {
        try {
            $this->request->validate([
                'status' => ['required', new Enum(CheckAttendStatus::class)],
                'tanggal' => ['required'],
                'jam_masuk' => ['required'],
                'jam_keluar' => ['required'],
            ]);

            $status = $this->request->enum('status', CheckAttendStatus::class);

            $data = $this->mainRepository->create([
                'user_id' => $this->request->user_id,
                'status' => $status,
                'tanggal' => $this->request->tanggal,
                'jam_masuk' => $this->request->jam_masuk ?? null, // alternative waktu.
                'jam_keluar' => $this->request->jam_keluar ?? null, // alternative waktu.
            ]);

            return $this->setCode(200)
                ->setMessage("$this->title_intern $this->create_message_intern!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function updateAttend()
    {
        try {
            $this->request->validate([
                'status' => ['required', new Enum(CheckAttendStatus::class)],
                'tanggal' => ['required'],
                'jam_masuk' => ['required'],
                'jam_keluar' => ['required'],
            ]);

            $status = $this->request->enum('status', CheckAttendStatus::class);

            $data = $this->mainRepository->updateAttend([
                'user_id' => $this->request->user_id,
                'status' => $status,
                'tanggal' => $this->request->tanggal,
                'jam_masuk' => $this->request->jam_masuk ?? null, // cari alternativenya.
                'jam_keluar' => $this->request->jam_keluar ?? null, // cari alternativenya, terkait dengan function waktu.
            ]);

            return $this->setCode(200)
                ->setMessage("$this->title_intern $this->update_message_intern!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function deleteInternAttendById($id)
    {
        try {
            $data = $this->mainRepository->delete($id);
            return $this->setCode(200)
                ->setMessage("$this->title_intern $this->delete_message_intern!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
