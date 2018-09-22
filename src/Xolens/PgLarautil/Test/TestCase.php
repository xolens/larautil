<?php

namespace Xolens\PgLarautil\Test;

use Xolens\PgLarautil\App\Repository\LogRepository;
use Xolens\PgLarautil\App\Model\Log;
use Carbon\Carbon;
use \Orchestra\Testbench\TestCase as OrchestraTestCase;
use Xolens\PgLarautil\Test\RepositoryTrait\ReadableRepositoryTestTrait;
use Xolens\PgLarautil\Test\RepositoryTrait\WritableRepositoryTestTrait;
use Xolens\PgLarautil\Test\RepositoryTrait\SoftDeletableRepositoryTestTrait;
use Xolens\LarautilContract\App\Util\Model\Sorter;
use Xolens\LarautilContract\App\Util\Model\Filterer;

class TestCase extends OrchestraTestCase
{
    
    protected function getEnvironmentSetUp($app){
        $app['config']->set('database.connections.pgsql.schema', env('DB_SCHEMA','public'));
    }

    public function schemaName(){
        return env('DB_SCHEMA','public');
    }

    public function hasSchema(){
        return env('DB_CONNECTION','mysql')=='pgsql';
    }
    
    /**
     * @test
     */
    public function initialized(){
        $this->assertTrue(true);
    }
}