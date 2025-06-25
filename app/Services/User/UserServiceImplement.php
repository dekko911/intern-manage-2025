<?php

namespace App\Services\User;

use Illuminate\Support\Str;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserServiceImplement extends ServiceApi implements UserService
{
    /**
     * Set title message api for CRUD.
     * @param string $title
     */
    protected string $title_user = "User";
    protected string $create_message_user = "berhasil dibuat";
    protected string $update_message_user = "berhasil diubah";
    protected string $delete_message_user = "berhasil dihapus";

    /**
     * Don't change $this->mainRepository variable name,
     * because used in extends service class.
     */
    protected UserRepository $mainRepository;

    protected Request $request;

    protected $file;

    public function __construct(UserRepository $mainRepository, Request $request)
    {
        $this->mainRepository = $mainRepository;
        $this->request = $request;
        $this->file = $request->file('photo');
    }

    // SEMUA LOGIC YANG BIASA DIGUNAKAN DENGAN BAHASA PEMROGRAMAN YAITU BUSINESS LOGIC, IMPLEMENTASIKAN DISINI !!!!!!!!

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
            //'password_confirmation' => clue for input password confirmation.
            $this->request->validate([
                'name' => ['required'],
                'email' => ['required'],
                'password' => ['required', 'confirmed', 'min:6'],
                'role' => ['required'],
                'photo' => ['nullable', 'mimes:png,jpg,webp', 'max:1024'],
            ]);

            if ($this->file) {
                $extension = $this->file->extension();
                $fileName = Str::random(10) . '.' . $extension;

                $this->file->storeAs('images/profile', $fileName, 'public');
            }

            $data = $this->mainRepository->create([
                'name' => $this->request->name,
                'email' => $this->request->email,
                'password' => $this->request->password,
                'role' => $this->request->role,
                'photo' => $fileName ?? "-",
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
            // METHOD SPOOFING like: "_method = PUT || PATCH" <- in params / parameter.
            $this->request->validate([
                'name' => ['required'],
                'email' => ['required'],
                'password' => ['confirmed', 'min:6'],
                'role' => ['required'],
                'photo' => ['mimes:png,jpg,webp', 'max:1024'],
            ]);

            $updateFile = $this->mainRepository->findOrFail($id);

            if ($this->file) {
                // delete the old file when is available at directory,
                if ($updateFile->photo) {
                    Storage::disk('public')->delete("images/profile/$updateFile->photo");
                }

                // and store the new one.
                $extension = $this->file->extension();
                $fileName = Str::random(10) . '.' . $extension;

                $this->file->storeAs('images/profile', $fileName, 'public');
            }

            $data = $this->mainRepository->update($id, [
                'name' => $this->request->name,
                'email' => $this->request->email,
            ]);

            if ($this->request->password) {
                $data = $this->mainRepository->update($id, ['password' => $this->request->password]);
            }

            if ($this->request->role) {
                $data = $this->mainRepository->update($id, ['role' => $this->request->role]);
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
            $deleteFile = $this->mainRepository->findOrFail($id);

            if ($deleteFile->photo) {
                Storage::disk('public')->delete("images/profile/$deleteFile->photo");
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
