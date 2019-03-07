<?php

namespace Xolens\PgLarautil\App\Util\Model;

class Sorter  extends Querable{
    
    public const ASC = "asc";
    public const DESC = "desc";

    private $sorters = [];
    
    public function __construct (array $sorts = [] ) {
        foreach ($sorts as $item){
            if(array_key_exists("property",$item)&&array_key_exists("direction",$item)){
                $this->addSorter($item["property"], $item["direction"]);
            }
        }
    }

    public function asc($property){
        return $this->addSorter($property, self::ASC);
    }
    
    public function desc($property){
        return $this->addSorter($property, self::DESC);
    }
    
    private function addSorter($property, $direction){
        $this->sorters[] = ["property" => $property, "direction" => $direction];
        return $this;
    }
    
    public function sortModel($model){
        $i = 0;
        $sorters = $this->sorters;
        $limit = count($sorters);
        $query = self::query($model);
        while($i < $limit){
            $query = $query->orderBy($sorters[$i]['property'], $sorters[$i]['direction']);
            $i++;
        }
        return $query;
    }
}