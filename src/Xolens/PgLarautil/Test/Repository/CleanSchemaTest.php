<?php

namespace Xolens\PgLarautil\Test\Repository;

use Xolens\PgLarautil\App\Repository\LogRepository;
use Xolens\PgLarautil\App\Model\Log;
use Carbon\Carbon;
use Xolens\PgLarautil\Test\CleanSchemaBase;
use Xolens\PgLarautil\Test\RepositoryTrait\ReadableRepositoryTestTrait;
use Xolens\PgLarautil\Test\RepositoryTrait\WritableRepositoryTestTrait;
use Xolens\PgLarautil\Test\RepositoryTrait\SoftDeletableRepositoryTestTrait;
use Xolens\PgLarautil\App\Util\Model\Sorter;
use Xolens\PgLarautil\App\Util\Model\Filterer;

final class CleanSchemaTest extends CleanSchemaBase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void{
        parent::setUp();
        $this->artisan('migrate');
    }

    protected function getPackageProviders($app): array{
        return ['Xolens\PgLarautil\PgLarautilServiceProvider'];
    }

}