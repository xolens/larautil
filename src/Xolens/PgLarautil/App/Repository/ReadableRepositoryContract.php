<?php

namespace Xolens\PgLarautil\App\Repository;

use Xolens\PgLarautil\App\Util\RepositoryResponse;
use Xolens\PgLarautil\App\Util\Model\Sorter;
use Xolens\PgLarautil\App\Util\Model\Filterer;
interface ReadableRepositoryContract{
    
    public function model();
    public function make($data);
    public function find($toFind);

    public function all($columns = ['*']);
    public function allSorted(Sorter $sorter, $columns = ['*']);
    public function allFiltered(Filterer $filterer, $columns = ['*']);
    public function allSortedFiltered(Sorter $sorter, Filterer $filterer, $columns = ['*']);
    
    public function paginate($perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    public function paginateArray(array $items, $page, $perPage=50);
    public function paginateSorted(Sorter $sorter, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    public function paginateFiltered(Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    public function paginateSortedFiltered(Sorter $sorter, Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    
    public function count();

}