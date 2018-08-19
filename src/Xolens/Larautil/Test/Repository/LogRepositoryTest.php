<?php

namespace Xolens\Larautil\Test\Repository;

use Xolens\Larautil\App\Repository\LogRepository;
use Xolens\Larautil\App\Model\Log;
use Carbon\Carbon;
use \Orchestra\Testbench\TestCase;

final class LogRepositoryTest extends TestCase
{
    public $repo;
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
        return ['Xolens\Larautil\LarautilServiceProvider'];
    }
    
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
    public function test_find_with_no_result(){
        $res = $this->repo->find(-1)->response();

        $this->assertNull($res);
    }
    
    /**
     * @test
     * @depends test_create
     */
    public function test_find_single(){
        $toFind = $this->generateSingleItem();
        $item = $this->repo->find($toFind)->response();

        $this->assertEquals($toFind, $item->id);
    }
    
    /**
     * @test
     * @depends test_create
     */
    public function test_find_many(){
        $generatedItemsId = $this->generateItems(7);
        $items = $this->repo->find($generatedItemsId)->response();

        $this->assertEquals(count($items), count($generatedItemsId));
    }
    
    /**
     * @test
     * @depends test_create
     */
    public function test_count(){
        $random = rand(0,50);
        $count1 = $this->repo->count()->response();
        $this->generateItems($random);
        $count2 = $this->repo->count()->response();

        $this->assertEquals($count2, ($count1+$random));
    }
    
    /**
     * @test
     * @depends test_count
     */
    public function test_paginate_with_random_params(){
        $count = $this->repo->count()->response();
        $perPage = rand(1,$count);
        $page = rand(1,($count/$perPage));
        $page = $page>0?$page:1;
        $respone = $this->repo->paginate($perPage, $page)->response();

        $this->assertEquals($respone->total(), $count);
        $this->assertEquals($respone->perPage(), $perPage);
        $this->assertEquals($respone->currentPage(), $page);
    }
    
    /**
     * @test
     */
    public function test_paginate_array(){
        $testItems = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];
        $count = count($testItems);
        $perPage = rand(1,$count);
        $page = rand(1,($count/$perPage));
        $page = $page>0?$page:1;
        $respone = $this->repo->paginateArray($testItems, $page, $perPage)->response();
        $this->assertTrue(count(array_intersect($respone, $testItems)) == count($respone));
    }
    
    /**
     * @test
     */
    public function test_make(){
        $types = ['DELETE', 'UPDATE', 'INSERT'];
        $index = rand(0,2);
        $i = rand(0, 10000);
        $item = $this->repo->make([
            "log_schema"=> "log_schema".$i,
            "log_table"=> "log_table".$i,
            "log_type"=> $types[$index],
            "last_value"=> "NULL",
            "log_at"=> Carbon::now(),
        ]);
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function test_update_single(){
        $toFind = $this->generateSingleItem();
        $response = $this->repo->update($toFind,[
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
        $response = $this->repo->update($generatedItemsId,[
                "log_schema"=> "log_schema_UPDATED (".$count.")",
        ])->response();

        $this->assertEquals($response, $count);
    }
    
    /**
     * @test
     */
    public function test_delete_single(){
        $toFind = $this->generateSingleItem();
        $respone = $this->repo->delete($toFind)->response();
        $this->assertEquals($respone, 1);
    }
    
    /**
     * @test
     */
    public function test_delete_many(){
        $count = 3;
        $generatedItemsId = $this->generateItems($count);
        $response = $this->repo->delete($generatedItemsId)->response();
        $this->assertEquals($response, $count);
    }
    
    /**
     * @test
     */
    public function test_restore_single_no_deleted(){
        $id = $this->generateSingleItem();
        $response = $this->repo->restore($id)->response();

        $this->assertEquals($response, 0);
    }
    
    
    /**
     * @test
     * @depends test_delete_single
     */
    public function test_restore_single_deleted(){
        $id = $this->generateSingleItem();
        $this->repo->delete($id);
        $response = $this->repo->restore($id)->response();

        $this->assertEquals($response, 1);
    }
    
    /**
     * @test
     */
    public function test_restore_many_no_deleted(){
        $count = 3;
        $generatedItemsId = $this->generateItems($count);
        $response = $this->repo->restore($generatedItemsId)->response();
        
        $this->assertEquals($response, 0);
    }
    
    /**
     * @test
     * @depends test_delete_many
     */
    public function test_restore_many_deleted(){
        $count = 3;
        $generatedItemsId = $this->generateItems($count);
        $this->repo->delete($generatedItemsId);
        $response = $this->repo->restore($generatedItemsId)->response();

        $this->assertEquals($response, $count);
    }

   /**
     * @test
     * @depends test_delete_single
     */
    public function test_force_delete_single(){
        $id = $this->generateSingleItem();
        $response = $this->repo->forceDelete($id)->response();

        $this->assertEquals($response, 1);
    }
    
    /**
     * @test
     * @depends test_delete_many
     */
    public function test_force_delete_many(){
        $count = 3;
        $generatedItemsId = $this->generateItems($count);
        $response = $this->repo->forceDelete($generatedItemsId)->response();

        $this->assertEquals($response, $count);
    }

    /**
     * @test
     */
    public function test_find_single_with_trashed_no_deleted(){
        $toFind = $this->generateSingleItem();
        $item = $this->repo->findWithTrashed($toFind)->response();

        $this->assertEquals($toFind, $item->id);
    }

    /**
     * @test
     */
    public function test_find_single_with_trashed_deleted(){
        $toFind = $this->generateSingleItem();
        $this->repo->delete($toFind);
        $item = $this->repo->findWithTrashed($toFind)->response();

        $this->assertEquals($toFind, $item->id);
    }

    /**
     * @test
     */
    public function test_find_many_with_trashed_no_deleted(){
        $generatedItemsId = $this->generateItems(7);
        $items = $this->repo->findWithTrashed($generatedItemsId)->response();

        $this->assertEquals(count($items), count($generatedItemsId));
    }

    /**
     * @test
     */
    public function test_find_many_with_trashed_deleted(){
        $generatedItemsId = $this->generateItems(7);
        $this->repo->delete($generatedItemsId);
        $items = $this->repo->findWithTrashed($generatedItemsId)->response();

        $this->assertEquals(count($items), count($generatedItemsId));
    }

    /**
     * @test
     */
    public function test_find_single_only_trashed_no_deleted(){
        $toFind = $this->generateSingleItem();
        $item = $this->repo->findOnlyTrashed($toFind)->response();

        $this->assertNull($item);
    }

    /**
     * @test
     */
    public function test_find_single_only_trashed_deleted(){
        $toFind = $this->generateSingleItem();
        $this->repo->delete($toFind);
        $item = $this->repo->findOnlyTrashed($toFind)->response();

        $this->assertEquals($toFind, $item->id);
    }

    /**
     * @test
     */
    public function test_find_many_only_trashed_no_deleted(){
        $generatedItemsId = $this->generateItems(7);
        $items = $this->repo->findOnlyTrashed($generatedItemsId)->response();

        $this->assertEquals(count($items), 0);
    }

    /**
     * @test
     */
    public function test_find_many_only_trashed_deleted(){
        $generatedItemsId = $this->generateItems(7);
        $this->repo->delete($generatedItemsId);
        $items = $this->repo->findOnlyTrashed($generatedItemsId)->response();

        $this->assertEquals(count($items), count($generatedItemsId));
    }


    /**
     * @test
     */
    public function test_paginate_with_trashed(){
       $count = $this->repo->countWithTrashed()->response();
        $perPage = rand(1,$count);
        $page = rand(1,($count/$perPage));
        $page = $page>0?$page:1;
        $respone = $this->repo->paginateWithTrashed($perPage, $page)->response();

        $this->assertEquals($respone->total(), $count);
        $this->assertEquals($respone->perPage(), $perPage);
        $this->assertEquals($respone->currentPage(), $page);
    }

    /**
     * @test
     */
    public function test_paginate_only_trashed(){
        $count = $this->repo->countOnlyTrashed()->response();
        $perPage = rand(1,$count);
        $page = rand(1,($count/$perPage));
        $page = $page>0?$page:1;
        $respone = $this->repo->paginateOnlyTrashed($perPage, $page)->response();

        $this->assertEquals($respone->total(), $count);
        $this->assertEquals($respone->perPage(), $perPage);
        $this->assertEquals($respone->currentPage(), $page);
    }

    /**
     * @test
     */
    public function test_count_with_trashed(){
        $randomGenerated = rand(5,20);
        $randomDeleted = rand(0,$randomGenerated);
        $count1 = $this->repo->countWithTrashed()->response();
        $generatedItemsId = $this->generateItems($randomGenerated);
        for($i=0; $i<$randomDeleted; $i++){
            $this->repo->delete($generatedItemsId[$i]);
        }
        $count2 = $this->repo->countWithTrashed()->response();

        $this->assertEquals($count2, ($count1+$randomGenerated));
    }

    /**
     * @test
     */
    public function test_count_only_trashed(){
        $randomGenerated = rand(5,20);
        $randomDeleted = rand(0,$randomGenerated);
        $count1 = $this->repo->countOnlyTrashed()->response();
        $generatedItemsId = $this->generateItems($randomGenerated);
        for($i=0; $i<$randomDeleted; $i++){
            $this->repo->delete($generatedItemsId[$i]);
        }
        $count2 = $this->repo->countOnlyTrashed()->response();

        $this->assertEquals($count2, ($count1+$randomDeleted));
    }
    
    /** HELPERS FUNCTIONS --------------------------------------------- **/

    public function generateItems($toGenerateCount){
        $types = ['DELETE', 'UPDATE', 'INSERT'];
        $generatedItemsId = [];

        for($i=0; $i<$toGenerateCount; $i++){
            $index = rand(0,2);
            $item = $this->repo->create([
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