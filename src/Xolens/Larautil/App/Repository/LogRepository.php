<?php

namespace Xolens\Larautil\App\Repository;

use Xolens\Larautil\App\Model\Log;
use Xolens\Larautil\App\Repository\ReadOnlyRepositoryContract;

class LogRepository extends AbstractSoftDeletableRepository
{
    public function model(){
        return Log::class;
    }

}