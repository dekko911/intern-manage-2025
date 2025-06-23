<?php

namespace App\Services\Role;

use LaravelEasyRepository\BaseService;

interface RoleService extends BaseService
{
    public function getDataRole();

    public function getRoleById($id);

    public function createRole();

    public function deleteRoleById($id);
}
