<?php

namespace Xolens\PgLarautil\App\Repository;

use Xolens\LarautilContract\App\Repository\RepositoryResponse;
use Xolens\LarautilContract\App\Repository\Contract\WritableRepositoryContract;

abstract class AbstractWritableRepository extends AbstractReadableRepository implements WritableRepositoryContract{
    
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

    public function validationRules(array $data){
        return [];
    }
}