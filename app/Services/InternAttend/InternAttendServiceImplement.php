<?php

namespace App\Services\InternAttend;

use App\Enums\CheckAttendStatus;
use App\Models\TempInternAttend;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\InternAttend\InternAttendRepository;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class InternAttendServiceImplement extends ServiceApi implements InternAttendService
{
    /**
     * set title message api for CRUD
     * @param string $title
     */
    private string $title_intern = "Absen";
    private string $create_message_intern = "berhasil dibuat";
    private string $update_message_intern = "berhasil diubah";
    private string $delete_message_intern = "berhasil dihapus";

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected InternAttendRepository $mainRepository;

    private Request $request;

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
            if ($this->mainRepository->checkDataDoubleAttendIfExists()) {
                throw new \Exception("Tidak Bisa Absensi Dua Kali dalam Sehari!");
            }

            $this->request->validate([
                'user_id' => ['sometimes', 'required'],
                'status' => ['sometimes', 'required', new Enum(CheckAttendStatus::class)],
                'tanggal' => ['sometimes', 'required'],
                'jam_masuk' => ['sometimes', 'required'],
                'jam_keluar' => ['sometimes', 'required'],
            ]);

            $status = $this->request->enum('status', CheckAttendStatus::class);

            switch (Auth::user()->role) {
                case 'admin':
                case 'staff':
                    $data = $this->mainRepository->create([
                        'user_id' => $this->request->user_id,
                        'status' => $status,
                        'tanggal' => $this->request->tanggal ?? today('Asia/Kuala_Lumpur')->toDateString(),
                        'jam_masuk' => $this->request->jam_masuk,
                        'jam_keluar' => $this->request->jam_keluar ?? "-",
                    ]);

                    TempInternAttend::create([
                        'intern_attend_id' => $data->id,
                        'user_id' => $data->user_id,
                        'status' => $data->status,
                        'tanggal' => $data->tanggal,
                        'jam_masuk' => $data->jam_masuk,
                        'jam_keluar' => $data->jam_keluar,
                        'expired_at' => now('Asia/Kuala_Lumpur')->addWeek()->startOfWeek(CarbonInterface::MONDAY),
                    ]);
                    break;
                case 'intern':
                    if ($status === CheckAttendStatus::SAKIT or $status === CheckAttendStatus::IJIN or $status === CheckAttendStatus::ALPA) {
                        $data = $this->mainRepository->create([
                            'user_id' => Auth::id(),
                            'status' => $status,
                            'tanggal' => today('Asia/Kuala_Lumpur')->toDateString(),
                            'jam_masuk' => "-",
                            'jam_keluar' => "-",
                        ]);

                        TempInternAttend::create([
                            'intern_attend_id' => $data->id,
                            'user_id' => $data->user_id,
                            'status' => $data->status,
                            'tanggal' => $data->tanggal,
                            'jam_masuk' => $data->jam_masuk,
                            'jam_keluar' => $data->jam_keluar,
                            'expired_at' => now('Asia/Kuala_Lumpur')->addWeek()->startOfWeek(CarbonInterface::MONDAY),
                        ]);
                    } else {
                        $data = $this->mainRepository->create([
                            'user_id' => Auth::id(),
                            'status' => $status,
                            'tanggal' => today('Asia/Kuala_Lumpur')->toDateString(),
                            'jam_masuk' => now('Asia/Kuala_Lumpur')->toTimeString(),
                            'jam_keluar' => $this->request->jam_keluar ?? "-",
                        ]);

                        TempInternAttend::create([
                            'intern_attend_id' => $data->id,
                            'user_id' => $data->user_id,
                            'status' => $data->status,
                            'tanggal' => $data->tanggal,
                            'jam_masuk' => $data->jam_masuk,
                            'jam_keluar' => $data->jam_keluar,
                            'expired_at' => now('Asia/Kuala_Lumpur')->addWeek()->startOfWeek(CarbonInterface::MONDAY),
                        ]);
                    }
                    break;
                default;
            }

            return $this->setCode(200)
                ->setMessage("$this->title_intern $this->create_message_intern & ditandai di temp_intern_attends!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function updateAttend($id)
    {
        try {
            $getInternAttendId = $this->mainRepository->findOrFail($id);

            $this->request->validate([
                'user_id' => ['sometimes', 'required'],
                'status' => ['sometimes', 'required', new Enum(CheckAttendStatus::class)],
                'tanggal' => ['sometimes', 'required'],
                'jam_masuk' => ['sometimes', 'required'],
                'jam_keluar' => ['sometimes', 'required'],
            ]);

            $status = $this->request->enum('status', CheckAttendStatus::class);

            switch (Auth::user()->role) {
                case 'admin':
                case 'staff':
                    $data = $this->mainRepository->update($id, [
                        'user_id' => $this->request->user_id,
                        'status' => $status,
                        'tanggal' => $this->request->tanggal,
                        'jam_masuk' => $this->request->jam_masuk,
                        'jam_keluar' => $this->request->jam_keluar,
                    ]);

                    if ($id) {
                        TempInternAttend::where('intern_attend_id', $id)->delete();

                        TempInternAttend::create([
                            'intern_attend_id' => $id,
                            'user_id' => $this->request->user_id,
                            'status' => $status,
                            'tanggal' => $this->request->tanggal,
                            'jam_masuk' => $this->request->jam_masuk,
                            'jam_keluar' => $this->request->jam_keluar,
                            'expired_at' => now('Asia/Kuala_Lumpur')->addWeek()->startOfWeek(CarbonInterface::MONDAY),
                        ]);
                    }
                    break;
                case 'intern':
                    $data = $this->mainRepository->update($id, [
                        'user_id' => Auth::id(),
                        'status' => $getInternAttendId->status,
                        'tanggal' => $getInternAttendId->tanggal,
                        'jam_masuk' => $getInternAttendId->jam_masuk,
                        'jam_keluar' => now('Asia/Kuala_Lumpur')->toTimeString(),
                    ]);
                    if ($id) {
                        TempInternAttend::where('intern_attend_id', $id)->delete();

                        TempInternAttend::create([
                            'intern_attend_id' => $id,
                            'user_id' => Auth::id(),
                            'status' => $getInternAttendId->status,
                            'tanggal' => $getInternAttendId->tanggal,
                            'jam_masuk' => $getInternAttendId->jam_masuk,
                            'jam_keluar' => now('Asia/Kuala_Lumpur')->toTimeString(),
                            'expired_at' => now('Asia/Kuala_Lumpur')->addWeek()->startOfWeek(CarbonInterface::MONDAY),
                        ]);
                    }
                    break;
                default;
            }

            return $this->setCode(200)
                ->setMessage("$this->title_intern $this->update_message_intern & ditandai di temp_intern_attends!")
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
