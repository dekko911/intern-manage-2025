<?php

namespace App\Repositories\JobIntern;

use LaravelEasyRepository\Repository;

interface JobInternRepository extends Repository
{
    public function getDataInternJob();

    public function updateInternJob(array $attributes);
}