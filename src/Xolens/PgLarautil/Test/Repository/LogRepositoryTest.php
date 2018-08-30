<?php

namespace Xolens\PgLarautil\Test\Repository;

use Xolens\PgLarautil\App\Repository\LogRepository;
use Xolens\PgLarautil\App\Model\Log;
use Carbon\Carbon;
use \Orchestra\Testbench\TestCase;
use Xolens\PgLarautil\Test\RepositoryTrait\ReadableRepositoryTestTrait;
use Xolens\PgLarautil\Test\RepositoryTrait\WritableRepositoryTestTrait;
use Xolens\PgLarautil\Test\RepositoryTrait\SoftDeletableRepositoryTestTrait;
use Xolens\LarautilContract\App\Util\Model\Sorter;
use Xolens\LarautilContract\App\Util\Model\Filterer;

final class LogRepositoryTest extends TestCase
{
    use ReadableRepositoryTestTrait, WritableRepositoryTestTrait, SoftDeletableRepositoryTestTrait;
    
    private $repo;

    public function repository(){
        return $this->repo;
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void{
        parent::setUp();
        $this->artisan('migrate');
        $repo = new LogRepository();
        $this->repo = $repo;
    }

    protected function getPackageProviders($app): array{
        return ['Xolens\PgLarautil\PgLarautilServiceProvider'];
    }

    /**
     * @test
     */
    public function test_make(){
        $types = ['DELETE', 'UPDATE', 'INSERT'];
        $index = rand(0,2);
        $i = rand(0, 10000);
        $item = $this->repository()->make([
            "log_schema"=> "log_schema".$i,
            "log_table"=> "log_table".$i,
            "log_type"=> $types[$index],
            "last_value"=> "NULL",
            "log_at"=> Carbon::now(),
        ]);
        $this->assertTrue(true);
    }
    
    /** HELPERS FUNCTIONS --------------------------------------------- **/

    public function generateSorter(){
        $sorter = new Sorter();
        $sorter->asc('log_schema')
                ->desc('log_table')
                ->desc('log_type')
                ->asc('last_value');
        return $sorter;
    }

    public function generateFilterer(){
        $filterer = new Filterer();
        $filterer->between('id',[0,14])
                ->like('log_table','%tab%')
                ->in('log_schema',['public', 'minefopstat']);
        return $filterer;
    }

    public function generateItems($toGenerateCount){
        $types = ['DELETE', 'UPDATE', 'INSERT'];
        $generatedItemsId = [];

        for($i=0; $i<$toGenerateCount; $i++){
            $index = rand(0,2);
            $item = $this->repository()->create([
                "log_schema"=> "log_schema".$i,
                "log_table"=> "log_table".$i,
                "log_type"=> $types[$index],
                "last_value"=> "NULL",
                "log_at"=> Carbon::now(),
            ]);
            $generatedItemsId[] = $item->response()->id;
        }
        $this->assertEquals(count($generatedItemsId), $toGenerateCount);
        return $generatedItemsId;
    }
    
    public function generateSingleItem(){
        return $this->generateItems(1)[0];
    }
}