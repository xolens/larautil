<?php

namespace Xolens\PgLarautil\Test\RepositoryTrait;

use Carbon\Carbon;

trait ReadOnlyRepositoryTestTrait 
{
    /**
     * Injected repository
     */
    abstract public function repository();
    
    /**
     * @test
     */
    public function test_find_with_no_result(){
        $res = $this->repository()->find(-1)->response();

        $this->assertNull($res);
    }
    
    /**
     * @test
     */
    public function test_find_single(){
        $this->repo->model()::inRandomOrder()->first();
      
        $this->assertTrue(true);
    }
    
    /**
     * @test
     */
    public function test_count(){
        $this->repository()->count();
        $this->assertTrue(true);
    }
    
    /**
     * @test
     * @depends test_count
     */
    public function test_paginate_with_random_params(){
        $limit = rand(25,100);
        $perPage = rand(1,$limit);
        $page = rand(1,($limit/$perPage));
        $page = $page>0?$page:1;
        $respone = $this->repository()->paginate($perPage, $page)->response();

        $this->assertEquals($respone->perPage(), $perPage);
        $this->assertEquals($respone->currentPage(), $page);
    }
    
    /**
     * @test
     */
    public function test_paginate_array(){
        $testItems = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
        $count = count($testItems);
        $perPage = rand(1,$count);
        $page = rand(1,($count/$perPage));
        $page = $page>0?$page:1;
        $respone = $this->repository()->paginateArray($testItems, $page, $perPage)->response();
        $this->assertTrue(count(array_intersect($respone, $testItems)) == count($respone));
    }
    
    /**
     * @test
     */
    public function test_paginate_filtered(){
        $filterer = $this->generateFilterer();
        $respone = $this->repository()->paginateFiltered($filterer)->response();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_paginate_sorter(){
        $sorter = $this->generateSorter();
        $respone = $this->repository()->paginateSorted($sorter)->response();
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function test_paginate_sorter_filtered(){
        $sorter = $this->generateSorter();
        $filterer = $this->generateFilterer();
        $respone = $this->repository()->paginateSortedFiltered($sorter, $filterer)->response();
        $this->assertTrue(true);
    }
    
    abstract public function generateSorter();

    abstract public function generateFilterer();
}