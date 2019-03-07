<?php

namespace Xolens\PgLarautil\App\Util\Model;

class Filterer extends Querable{
    public const EQUALS = "==";
    public const SMALLER = "<";
    public const GREATER = ">";
    public const IN = "IN";
    public const BETWEEN = "BETWEEN";
    public const LIKE = "LIKE";

    public const NOT_EQUALS = "!=";
    public const NOT_SMALLER = ">=";
    public const NOT_GREATER = "<=";
    public const NOT_LIKE = "NOT LIKE";
    public const NOT_IN = "NOT_IN";
    public const NOT_BETWEEN = "NOT_BETWEEN";
    
    public const NOT_NULL = "NOT_NULL";
    public const IS_NULL = "IS_NULL";

    public const AND_FILTEFING = "AND_FILTEFING";
    public const OR_FILTEFING = "OR_FILTEFING";

    public const AND = "AND";
    public const OR = "OR";

    private $filterers = [];
    private $type = self::AND_FILTEFING;
        
    public function __construct (array $filters = [] ) {
        foreach ($filters as $item){
            if(array_key_exists("property",$item)){
                $property = $item["property"];
                $operator = null;
                $value = null;
                if(array_key_exists("operator",$item)){
                    $operator = $item["operator"];
                    $operator = strtoupper($item["operator"]);
                    if($operator == "LIKE"){
                        if(array_key_exists("value",$item)){
                            if(strpos($item["value"], "%") == false){
                                $item["value"] = "%".$item["value"]."%";
                            }
                        }else{
                            $item["value"] = "%";
                        }
                    }
                }
                if(array_key_exists("value",$item)){
                    $value = $item["value"];
                }
                $this->addFilterer($property, $operator, $value);
            }
        }
    }

    public function orFiltering(){
        $this->type = self::OR_FILTEFING;
        return $this;
    }
        
    public function andFiltering(){
        $this->type = self::AND_FILTEFING;
        return $this;
    }
    
    public function isOrFiltering(){
        return $this->type == self::OR_FILTEFING;;
    }
        
    public function isAndFiltering(){
        return $this->type == self::AND_FILTEFING;;
    }
    
    public function and(Filterer $filterer){
        return $this->addFilterer($filterer, self::AND, null);
    }
    
    public function or(Filterer $filterer){
        return $this->addFilterer($filterery, self::OR, null);
    }
    
    public function equals($key, $value){
        return $this->addFilterer($key, self::EQUALS, $value);
    }
    
    public function smaller($key, $value){
        return $this->addFilterer($key, self::SMALLER, $value);
    }
    
    public function greater($key, $value){
        return $this->addFilterer($key, self::GREATER, $value);
    }
    
    public function like($key, $value){
        return $this->addFilterer($key, self::LIKE, $value);
    }

    public function in($key, array $value){
        return $this->addFilterer($key, self::IN, $value);
    }
    
    public function between($key, array $value){
        return $this->addFilterer($key, self::BETWEEN, $value);
    }
    
    public function isNull($key){
        return $this->addFilterer($key, self::IS_NULL, null);
    }

    public function notEquals($key, $value){
        return $this->addFilterer($key, self::NOT_EQUALS, $value);
    }
    
    public function notSmaller($key, $value){
        return $this->addFilterer($key, self::NOT_SMALLER, $value);
    }
    
    public function notGreater($key, $value){
        return $this->addFilterer($key, self::NOT_GREATER, $value);
    }
    
    public function notLike($key, $value){
        return $this->addFilterer($key, self::NOT_LIKE, $value);
    }

    public function notIn($key, array $value){
        return $this->addFilterer($key, self::NOT_IN, $value);
    }
    
    public function notBetween($key, array $value){
        return $this->addFilterer($key, self::NOT_BETWEEN, $value);
    }
    
    public function isNotNull($key){
        return $this->addFilterer($key, self::NOT_NULL, null);
    }

    public function addFilterer($property, $operator, $value){
        $this->filterers[] = ["property" => $property, "operator" => $operator, "value" => $value];
        return $this;
    }

    public function filterModel($model){
        if($this->isOrFiltering()){
            return $this->orFilterModel($model, $this->filterers);
        }
        if($this->isAndFiltering()){
            return $this->andFilterModel($model, $this->filterers);
        }
    }
    
    private function orFilterModel($model){
        $i=0;
        $filterers = $this->filterers;
        $limit = count($filterers);
        $query = self::query($model);
        while($i < $limit){
            $operator = $filterers[$i]['operator'];
            switch($operator){
                case self::AND:
                    $query = $query->where(function($subquery) use ($filterers, $i) {
                        $filterers[$i]['property']->filterModel($subquery);
                    });
                    break;
                case self::OR:
                    $query = $query->orWhere(function($subquery) use ($filterers, $i) {
                        $filterers[$i]['property']->filterModel($subquery);
                    });
                    break;
                case self::IN:
                    $query = $query->orWhereIn($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::BETWEEN:
                    $query = $query->orWhereBetween($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::NOT_IN:
                    $query = $query->orWhereNotIn($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::NOT_BETWEEN:
                    $query = $query->orWhereNotBetween($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::EQUALS:
                    $query = $query->orWhere($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::IS_NULL:
                        $query = $query->orWhereNull($filterers[$i]['property']);
                        break;
                case self::NOT_NULL:
                    $query = $query->orWhereNotNull($filterers[$i]['property']);
                    break;
                case self::SMALLER:
                case self::NOT_SMALLER:
                case self::GREATER:
                case self::NOT_GREATER:
                case self::LIKE:
                case self::NOT_LIKE:
                case self::NOT_EQUALS:
                    $query = $query->orWhere($filterers[$i]['property'], $operator, $filterers[$i]['value']);
                    break;
            }
            $i++;
        }
        return $query;
    }
    
    private function andFilterModel($model){
        $i=0;
        $filterers = $this->filterers;
        $limit = count($filterers);
        $query = self::query($model);
        while($i < $limit){
            $operator = $filterers[$i]['operator'];
            switch($operator){
                case self::AND:
                    $query = $query->where(function($subquery) use ($filterers, $i) {
                        $filterers[$i]['property']->filterModel($subquery);
                    });
                    break;
                case self::OR:
                    $query = $query->orWhere(function($subquery) use ($filterers, $i) {
                        $filterers[$i]['property']->filterModel($subquery);
                    });
                    break;
                case self::IN:
                    $query = $query->whereIn($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::BETWEEN:
                    $query = $query->whereBetween($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::NOT_IN:
                    $query = $query->whereNotIn($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::NOT_BETWEEN:
                    $query = $query->whereNotBetween($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::EQUALS:
                    $query = $query->where($filterers[$i]['property'], $filterers[$i]['value']);
                    break;
                case self::IS_NULL:
                        $query = $query->whereNull($filterers[$i]['property']);
                        break;
                case self::NOT_NULL:
                    $query = $query->whereNotNull($filterers[$i]['property']);
                    break;
                case self::SMALLER:
                case self::NOT_SMALLER:
                case self::GREATER:
                case self::NOT_GREATER:
                case self::LIKE:
                case self::NOT_LIKE:
                case self::NOT_EQUALS:
                    $query = $query->where($filterers[$i]['property'], $operator, $filterers[$i]['value']);
                    break;
            }
            $i++;
        }
        return $query;
    }
}