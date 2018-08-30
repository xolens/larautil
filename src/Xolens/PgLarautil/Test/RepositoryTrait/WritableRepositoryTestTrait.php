<?php

namespace Xolens\PgLarautil\Test\RepositoryTrait;

use Xolens\PgLarautil\App\Model\Log;
use Carbon\Carbon;
use \Orchestra\Testbench\TestCase;

trait WritableRepositoryTestTrait 
{
    private $repo;

    /**
     * Injected repository
     */
    abstract public function repository();
    
    /**
     * @test
     */
    public function test_create(){
        $toGenerateCount = 5;
        $generatedItemsId = $this->generateItems($toGenerateCount);

        $this->assertEquals(count($generatedItemsId), $toGenerateCount);
        return $generatedItemsId;
    }
    
    /**
     * @test
     */
    public function test_update_single(){
        $toFind = $this->generateSingleItem();
        $response = $this->repository()->update($toFind,[
            "log_schema"=> "log_schema_".$toFind."_UPDATED",
        ])->response();

        $this->assertEquals($response, 1);
    }
    
    /**
     * @test
     */
    public function test_update_many(){
        $count = 3;
        $generatedItemsId = $this->generateItems($count);
        $response = $this->repository()->update($generatedItemsId,[
                "log_schema"=> "log_schema_UPDATED (".$count.")",
        ])->response();

        $this->assertEquals($response, $count);
    }
    
    /**
     * @test
     */
    public function test_delete_single(){
        $toFind = $this->generateSingleItem();
        $respone = $this->repository()->delete($toFind)->response();
        $this->assertEquals($respone, 1);
    }
    
    /**
     * @test
     */
    public function test_delete_many(){
        $count = 3;
        $generatedItemsId = $this->generateItems($count);
        $response = $this->repository()->delete($generatedItemsId)->response();
        $this->assertEquals($response, $count);
    }
    
    /**
     * @test
     */
    abstract public function test_count();

    abstract public function generateSorter();

    abstract public function generateFilterer();

    abstract public function generateSingleItem();

    abstract public function generateItems($toGenerateCount);
}