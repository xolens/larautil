<?php

namespace Xolens\Larautil\App\Repository;

use Xolens\LarautilContract\App\Repository\RepositoryResponse;
use Xolens\LarautilContract\App\Repository\Contract\BaseRepositoryContract;

abstract class AbstractBaseRepository extends AbstractReadOnlyRepository implements BaseRepositoryContract{
    
    public function create($data){
        $response = $this->model()::create($data);
        return $this->returnResponse($response);
    }

    public function update($toUpdate, $data){
        $model = $this->model();
        $count=0;
        if (is_array($toUpdate)){
            foreach ($model::find($toUpdate) as $item) {
                $item->update($data);
                $count++;
            }
        }else{
            $response =  $model::find($toUpdate)->update($data);
            $count++;
        }
        return $this->returnResponse($count);
    }

    public function delete($toDelete){
        $response = $this->model()::destroy($toDelete);
        return $this->returnResponse($response);
    }
}