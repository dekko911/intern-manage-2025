<?php

namespace App\Services\User;

use Illuminate\Support\Str;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserServiceImplement extends ServiceApi implements UserService
{
    /**
     * Set title message api for CRUD.
     * @param string $title
     */
    private string $title_user = "User";
    private string $create_message_user = "berhasil dibuat";
    private string $update_message_user = "berhasil diubah";
    private string $delete_message_user = "berhasil dihapus";

    /**
     * Don't change $this->mainRepository variable name,
     * because used in extends service class.
     */
    protected UserRepository $mainRepository;

    private Request $request;

    private $file;

    public function __construct(UserRepository $mainRepository, Request $request)
    {
        $this->mainRepository = $mainRepository;
        $this->request = $request;
        $this->file = $request->file('photo');
    }

    // SEMUA LOGIC YANG BIASA DIGUNAKAN DENGAN BAHASA PEMROGRAMAN YAITU BUSINESS LOGIC, IMPLEMENTASIKAN DI SINI !!!!!!!!

    public function getDataUser()
    {
        try {
            // variable name $data can be replace, example: $users || $roles && etc.
            $data = $this->mainRepository->getDataUser();

            return $this->setCode(200)
                ->setMessage("OK")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getUserById($id)
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

    public function createUser()
    {
        try {
            //'password_confirmation' => clue for input field password confirmation.
            $this->request->validate([
                'name' => ['required'],
                'email' => ['required', 'email'],
                'instansi' => ['sometimes', 'required'],
                'periode' => ['sometimes', 'required'],
                'password' => ['required', 'confirmed', 'min:6'],
                'role' => ['required'],
                'photo' => ['nullable', 'mimes:png,jpg,webp', 'max:1024'],
            ]);

            if ($this->request->role === 'admin') {
                if ($this->mainRepository->checkRoleDoubleAdminIfExists()) {
                    throw new \Exception("Tidak Bisa Tambah User Admin Lagi!");
                }
            }

            if ($this->file) {
                $fileName = Str::random(70);

                $this->file->storeAs('img/avt', $fileName, 'public');
            }

            $data = $this->mainRepository->create([
                'name' => $this->request->name,
                'email' => $this->request->email,
                'date' => today('Asia/Kuala_Lumpur')->isoFormat('DD MMMM YYYY'),
                'instansi' => $this->request->instansi ?? '-',
                'periode' => $this->request->periode ?? '-',
                'password' => $this->request->password,
                'role' => $this->request->role,
                'photo' => $fileName ?? '-',
            ]);

            return $this->setCode(200)
                ->setMessage("$this->title_user $this->create_message_user!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function updateUser($id)
    {
        try {
            $getUserId = $this->mainRepository->findOrFail($id);

            // mencari target user admin menggunakan param $id.
            if ($getUserId->role === 'admin') {
                // jika id yang login tidak sama dengan parameter $id, artinya hanya dia saja yang bisa me rubah dirinya sendiri. (si admin maksudnya)
                if (Auth::id() !== $id) {
                    throw new \Exception("Dilarang edit user admin selain si admin itu sendiri!");
                }
            }

            // METHOD SPOOFING like: "_method = PUT || PATCH" <- in params / parameter.
            $this->request->validate([
                'name' => ['required'],
                'email' => ['required', 'email'],
                'instansi' => ['required'],
                'periode' => ['required'],
                'password' => ['confirmed', 'min:6'],
                'role' => ['required'],
                'photo' => ['mimes:png,jpg,webp', 'max:1024'],
            ]);

            if ($getUserId->role === 'staff' or $getUserId->role === 'intern') {
                if ($this->request->role === 'admin') {
                    throw new \Exception("Admin hanya Satu saja, Tidak lebih.");
                }
            }

            if ($this->file) {
                // delete the old file when is available at directory,
                if ($getUserId->photo) {
                    Storage::disk('public')->delete("img/avt/$getUserId->photo");
                }

                // and store the new one.
                $fileName = Str::random(70);

                $this->file->storeAs('img/avt', $fileName, 'public');
            }

            $data = $this->mainRepository->update($id, [
                'name' => $this->request->name,
                'email' => $this->request->email,
                'date' => $getUserId->date,
                'instansi' => $this->request->instansi,
                'periode' => $this->request->periode,
                'role' => $this->request->role,
            ]);

            if ($this->request->password) {
                $data = $this->mainRepository->update($id, ['password' => $this->request->password]);
            }

            if ($this->request->photo) {
                $data = $this->mainRepository->update($id, ['photo' => $fileName]);
            }

            return $this->setCode(200)
                ->setMessage("$this->title_user $this->update_message_user!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function deleteUserById($id)
    {
        try {
            $getUserId = $this->mainRepository->findOrFail($id);

            if ($getUserId->role === "admin") {
                throw new \Exception("Dilarang menghapus user admin!");
            }

            if ($getUserId->photo) {
                Storage::disk('public')->delete("img/avt/$getUserId->photo");
            }

            $data = $this->mainRepository->delete($id);

            return $this->setCode(200)
                ->setMessage("$this->title_user $this->delete_message_user!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
