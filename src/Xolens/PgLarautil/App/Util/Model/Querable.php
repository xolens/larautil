<?php

namespace Xolens\PgLarautil\App\Util\Model;

use Xolens\PgLarautil\App\Exception\InvalidArgumentTypeException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Querable{
    public static function query($model){
        if(is_string($model)){
            return $model::query();
        }
        if($model instanceof Model){
            return $model->newQuery();
        }
        if($model instanceof Builder){
            return $model->newQuery();
        }
        throw new InvalidArgumentTypeException("Expected string, Illuminate\Database\Eloquent\Model or Illuminate\Database\Eloquent\Builder");
    }
}