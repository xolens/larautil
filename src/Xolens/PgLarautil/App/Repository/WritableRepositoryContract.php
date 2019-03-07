<?php

namespace Xolens\PgLarautil\App\Repository;

use Xolens\PgLarautil\App\Util\RepositoryResponse;

interface WritableRepositoryContract extends ReadableRepositoryContract{
    
    public function create($data);
    public function update($toUpdate, $data);
    public function delete($toDelete);
    public function validationRules(array $data);
}