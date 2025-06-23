<?php

namespace App\Services\Role;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Role\RoleRepository;
use Illuminate\Http\Request;

class RoleServiceImplement extends ServiceApi implements RoleService
{
    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected string $title_role = "Role";
    protected string $create_message_role = "berhasil dibuat";
    protected string $update_message_role = "berhasil diubah";
    protected string $delete_message_role = "berhasil dihapus";


    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected RoleRepository $mainRepository;

    protected Request $request;

    public function __construct(RoleRepository $mainRepository, Request $request)
    {
        $this->mainRepository = $mainRepository;
        $this->request = $request;
    }

    public function getDataRole()
    {
        try {
            $data = $this->mainRepository->getDataRole();
            return $this->setCode(200)
                ->setMessage("OK")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function getRoleById($id)
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

    public function createRole()
    {
        try {
            $this->request->validate([
                'user_id' => ['required'],
                'name' => ['required'],
            ]);

            $data = $this->mainRepository->createRole([
                'user_id' => $this->request->user_id,
                'name' => $this->request->name,
            ]);

            return $this->setCode(200)
                ->setMessage("$this->title_role $this->create_message_role!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    public function deleteRoleById($id)
    {
        try {
            $data = $this->mainRepository->delete($id);
            return $this->setCode(200)
                ->setMessage("$this->title_role $this->delete_message_role!")
                ->setData($data);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
