<?php

namespace App\Services\InternAttend;

use LaravelEasyRepository\BaseService;

interface InternAttendService extends BaseService
{
    public function getDataAttend();

    public function getInternAttendById($id);

    public function createAttend();

    public function updateAttend($id);

    public function deleteInternAttendById($id);
}
