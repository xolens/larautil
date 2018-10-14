<?php

namespace Xolens\PgLarautil\App\Repository;

use Xolens\LarautilContract\App\Repository\RepositoryResponse;
use Xolens\LarautilContract\App\Contract\Repository\SoftDeletableRepositoryContract;

use Xolens\LarautilContract\App\Util\Model\Sorter;
use Xolens\LarautilContract\App\Util\Model\Filterer;

    
abstract class AbstractSoftDeletableRepository extends AbstractWritableRepository implements SoftDeletableRepositoryContract{
    
    public function restore($torestore){
        $model = $this->model();
        $count=0;
        if (is_array($torestore)){
            foreach ($model::onlyTrashed()->find($torestore) as $item) {
                $item->restore();
                $count++;
            }
        }else{
            $item =  $model::onlyTrashed()->find($torestore);
            if($item!=null){
                $item->restore();
                $count++;
            }
        }
        return $this->returnResponse($count);
    }
    
    public function forceDelete($toDelete){
        $model = $this->model();
        $count=0;
        if (is_array($toDelete)){
            foreach ($model::withTrashed()->find($toDelete) as $item) {
                $item->forceDelete();
                $count++;
            }
        }else{
            $item =  $model::withTrashed()->find($toDelete);
            if($item!=null){
                $item->forceDelete();
                $count++;
            }
        }
        return $this->returnResponse($count);
    }

    public function findWithTrashed($toFind){
        $response = $this->model()::withTrashed()->find($toFind);
        return $this->returnResponse($response);
    }

    public function findOnlyTrashed($toFind){
        $response = $this->model()::onlyTrashed()->find($toFind);
        return $this->returnResponse($response);
    }

    public function paginateWithTrashed($perPage=50, $page = null, $columns = ['*'], $pageName = 'page'){
        $response = $this->model()::withTrashed()->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }

    public function paginateSortedWithTrashed(Sorter $sorter, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response = $sorter->sortModel($this->model()::withTrashed())->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }

    public function paginateFilteredWithTrashed(Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response = $filterer->filterModel($this->model()::withTrashed())->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }

    public function paginateSortedFilteredWithTrashed(Sorter $sorter, Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response =  $sorter->sortModel($filterer->filterModel($this->model()::withTrashed()))->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }
    
    public function paginateOnlyTrashed($perPage=50, $page = null, $columns = ['*'], $pageName = 'page'){
        $response = $this->model()::onlyTrashed()->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }
    
    public function paginateSortedOnlyTrashed(Sorter $sorter, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response = $sorter->sortModel($this->model()::onlyTrashed())->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }

    public function paginateFilteredOnlyTrashed(Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response = $filterer->filterModel($this->model()::onlyTrashed())->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }

    public function paginateSortedFilteredOnlyTrashed(Sorter $sorter, Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page'){
        $response =  $sorter->sortModel($filterer->filterModel($this->model()::onlyTrashed()))->paginate($perPage, $columns, $pageName, $page);
        return $this->returnResponse($response);
    }

    public function countWithTrashed(){
        $response = $this->model()::withTrashed()->count();
        return $this->returnResponse($response);
    }
    
    public function countOnlyTrashed(){
        $response = $this->model()::onlyTrashed()->count();
        return $this->returnResponse($response);
    }
}