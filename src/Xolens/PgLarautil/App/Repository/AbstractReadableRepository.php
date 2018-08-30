<?php

namespace Xolens\PgLarautil\App\Repository;

use Illuminate\Support\Collection;

use Xolens\LarautilContract\App\Repository\RepositoryResponse;
use Xolens\LarautilContract\App\Repository\Contract\ReadableRepositoryContract;

use Xolens\LarautilContract\App\Util\Model\Sorter;
use Xolens\LarautilContract\App\Util\Model\Filterer;

abstract class AbstractReadableRepository implements ReadableRepositoryContract{
    
    public abstract function model();

    public function make($data){
        $model = $this->model();
        $response = new $model($data);
        return $this->returnResponse($response);
    }

    public function find($toFind){
        $response = $this->model()::find($toFind);
        return $this->returnResponse($response);
    }

    public function paginate($perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response = $this->model()::paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }

    public function paginateArray(array $items, $page, $perPage=50){
        $collection = collect($items);
        $chunk = $collection->forPage($page, $perPage);
        return $this->returnResponse($chunk->all());
    }

    public function paginateSorted(Sorter $sorter, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response = $sorter->sortModel($this->model())->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }
    
    public function paginateFiltered(Filterer $filter, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response = $filter->filterModel($this->model())->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }

    public function paginateSortedFiltered(Sorter $sorter, Filterer $filter, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response =  $sorter->sortModel($filter->filterModel($this->model()))->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }

    public function count(){
        $response = $this->model()::count();
        return $this->returnResponse($response);
    }
    
    protected function returnResponse($response){
        $resp = new RepositoryResponse();
        $resp->setSuccess(true);
        $resp->setResponse($response);
        return $resp;
    }
    
    protected function returnErrors($errors){
        $err = new RepositoryResponse();
        $err->setSuccess(false);
        $err->setErrors($errors);
        return $err;
    }
}