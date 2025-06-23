<?php

namespace App\Repositories\Role;

use LaravelEasyRepository\Repository;

interface RoleRepository extends Repository
{
    public function getDataRole();

    public function createRole(array $attributes);
}
