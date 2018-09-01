<?php

namespace Xolens\PgLarautil\Test\RepositoryTrait;

use Xolens\PgLarautil\App\Model\Log;
use Carbon\Carbon;
use \Orchestra\Testbench\TestCase;

trait SoftDeletableRepositoryTestTrait 
{
    /**
     * Injected repository
     */
    abstract public function repository();
    
    /**
     * @test
     */
    public function test_restore_single_no_deleted(){
        $id = $this->generateSingleItem();
        $response = $this->repository()->restore($id)->response();

        $this->assertEquals($response, 0);
    }
    
    
    /**
     * @test
     * @depends test_delete_single
     */
    public function test_restore_single_deleted(){
        $id = $this->generateSingleItem();
        $this->repository()->delete($id);
        $response = $this->repository()->restore($id)->response();

        $this->assertEquals($response, 1);
    }
    
    /**
     * @test
     */
    public function test_restore_many_no_deleted(){
        $count = 3;
        $generatedItemsId = $this->generateItems($count);
        $response = $this->repository()->restore($generatedItemsId)->response();
        
        $this->assertEquals($response, 0);
    }
    
    /**
     * @test
     * @depends test_delete_many
     */
    public function test_restore_many_deleted(){
        $count = 3;
        $generatedItemsId = $this->generateItems($count);
        $this->repository()->delete($generatedItemsId);
        $response = $this->repository()->restore($generatedItemsId)->response();

        $this->assertEquals($response, $count);
    }

   /**
     * @test
     * @depends test_delete_single
     */
    public function test_force_delete_single(){
        $id = $this->generateSingleItem();
        $response = $this->repository()->forceDelete($id)->response();

        $this->assertEquals($response, 1);
    }
    
    /**
     * @test
     * @depends test_delete_many
     */
    public function test_force_delete_many(){
        $count = 3;
        $generatedItemsId = $this->generateItems($count);
        $response = $this->repository()->forceDelete($generatedItemsId)->response();

        $this->assertEquals($response, $count);
    }

    /**
     * @test
     */
    public function test_find_single_with_trashed_no_deleted(){
        $toFind = $this->generateSingleItem();
        $item = $this->repository()->findWithTrashed($toFind)->response();

        $this->assertEquals($toFind, $item->id);
    }

    /**
     * @test
     */
    public function test_find_single_with_trashed_deleted(){
        $toFind = $this->generateSingleItem();
        $this->repository()->delete($toFind);
        $item = $this->repository()->findWithTrashed($toFind)->response();

        $this->assertEquals($toFind, $item->id);
    }

    /**
     * @test
     */
    public function test_find_many_with_trashed_no_deleted(){
        $generatedItemsId = $this->generateItems(7);
        $items = $this->repository()->findWithTrashed($generatedItemsId)->response();

        $this->assertEquals(count($items), count($generatedItemsId));
    }

    /**
     * @test
     */
    public function test_find_many_with_trashed_deleted(){
        $generatedItemsId = $this->generateItems(7);
        $this->repository()->delete($generatedItemsId);
        $items = $this->repository()->findWithTrashed($generatedItemsId)->response();

        $this->assertEquals(count($items), count($generatedItemsId));
    }

    /**
     * @test
     */
    public function test_find_single_only_trashed_no_deleted(){
        $toFind = $this->generateSingleItem();
        $item = $this->repository()->findOnlyTrashed($toFind)->response();

        $this->assertNull($item);
    }

    /**
     * @test
     */
    public function test_find_single_only_trashed_deleted(){
        $toFind = $this->generateSingleItem();
        $this->repository()->delete($toFind);
        $item = $this->repository()->findOnlyTrashed($toFind)->response();

        $this->assertEquals($toFind, $item->id);
    }

    /**
     * @test
     */
    public function test_find_many_only_trashed_no_deleted(){
        $generatedItemsId = $this->generateItems(7);
        $items = $this->repository()->findOnlyTrashed($generatedItemsId)->response();

        $this->assertEquals(count($items), 0);
    }

    /**
     * @test
     */
    public function test_find_many_only_trashed_deleted(){
        $generatedItemsId = $this->generateItems(7);
        $this->repository()->delete($generatedItemsId);
        $items = $this->repository()->findOnlyTrashed($generatedItemsId)->response();

        $this->assertEquals(count($items), count($generatedItemsId));
    }


    /**
     * @test
     */
    public function test_paginate_with_trashed(){
       $count = $this->repository()->countWithTrashed()->response();
        $perPage = rand(1,$count);
        $page = rand(1,($count/$perPage));
        $page = $page>0?$page:1;
        $respone = $this->repository()->paginateWithTrashed($perPage, $page)->response();

        $this->assertEquals($respone->total(), $count);
        $this->assertEquals($respone->perPage(), $perPage);
        $this->assertEquals($respone->currentPage(), $page);
    }
    
    /**
     * @test
     */
    public function test_paginate_sorter_with_trashed(){
        $sorter = $this->generateSorter();
        $respone = $this->repository()->paginateSortedWithTrashed($sorter)->response();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_paginate_filtered_with_trashed(){
        $filterer = $this->generateFilterer();
        $respone = $this->repository()->paginateFilteredWithTrashed($filterer)->response();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_paginate_sorter_filtered_with_trashed(){
        $sorter = $this->generateSorter();
        $filterer = $this->generateFilterer();
        $respone = $this->repository()->paginateSortedFilteredWithTrashed($sorter, $filterer)->response();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_paginate_only_trashed(){
        $count = $this->repository()->countOnlyTrashed()->response();
        $perPage = rand(1,$count);
        $page = rand(1,($count/$perPage));
        $page = $page>0?$page:1;
        $respone = $this->repository()->paginateOnlyTrashed($perPage, $page)->response();

        $this->assertEquals($respone->total(), $count);
        $this->assertEquals($respone->perPage(), $perPage);
        $this->assertEquals($respone->currentPage(), $page);
    }
    
    /**
     * @test
     */
    public function test_paginate_sorter_only_trashed(){
        $sorter = $this->generateSorter();
        $respone = $this->repository()->paginateSortedOnlyTrashed($sorter)->response();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_paginate_filtered_only_trashed(){
        $filterer = $this->generateFilterer();
        $respone = $this->repository()->paginateFilteredOnlyTrashed($filterer)->response();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_paginate_sorter_filtered_only_trashed(){
        $sorter = $this->generateSorter();
        $filterer = $this->generateFilterer();
        $respone = $this->repository()->paginateSortedFilteredOnlyTrashed($sorter, $filterer)->response();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_count_with_trashed(){
        $randomGenerated = rand(5,20);
        $randomDeleted = rand(0,$randomGenerated);
        $count1 = $this->repository()->countWithTrashed()->response();
        $generatedItemsId = $this->generateItems($randomGenerated);
        for($i=0; $i<$randomDeleted; $i++){
            $this->repository()->delete($generatedItemsId[$i]);
        }
        $count2 = $this->repository()->countWithTrashed()->response();

        $this->assertEquals($count2, ($count1+$randomGenerated));
    }

    /**
     * @test
     */
    public function test_count_only_trashed(){
        $randomGenerated = rand(5,20);
        $randomDeleted = rand(0,$randomGenerated);
        $count1 = $this->repository()->countOnlyTrashed()->response();
        $generatedItemsId = $this->generateItems($randomGenerated);
        for($i=0; $i<$randomDeleted; $i++){
            $this->repository()->delete($generatedItemsId[$i]);
        }
        $count2 = $this->repository()->countOnlyTrashed()->response();

        $this->assertEquals($count2, ($count1+$randomDeleted));
    }
    
     /**
     * @test
     */
    abstract public function test_count();
    
     /**
     * @test
     */
    abstract public function test_delete_single();
    
     /**
     * @test
     */
    abstract public function test_delete_many();

    abstract public function generateSorter();

    abstract public function generateFilterer();

    abstract public function generateSingleItem();

    abstract public function generateItems($toGenerateCount);
}