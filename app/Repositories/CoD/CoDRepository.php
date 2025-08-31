<?php

namespace App\Repositories\CoD;

use LaravelEasyRepository\Repository;

interface CoDRepository extends Repository
{
    public function getDataCoD();

    public function checkDataDoubleCoDIfExists(): bool;
}
