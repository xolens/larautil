<?php

namespace Xolens\PgLarautil\App\Repository;

use Xolens\PgLarautil\App\Util\RepositoryResponse;

use Xolens\PgLarautil\App\Util\Model\Sorter;
use Xolens\PgLarautil\App\Util\Model\Filterer;

interface SoftDeletableRepositoryContract extends WritableRepositoryContract{
    
    public function restore($toRestore);
    public function forceDelete($toDelete);
    public function findWithTrashed($toFind);
    public function findOnlyTrashed($toFind);

    public function paginateOnlyTrashed($perPage=50, $page = null, $columns = ['*'], $pageName = 'page');
    public function paginateSortedOnlyTrashed(Sorter $sorter, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    public function paginateFilteredOnlyTrashed(Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    public function paginateSortedFilteredOnlyTrashed(Sorter $sorter, Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    
    public function paginateWithTrashed($perPage=50, $page = null, $columns = ['*'], $pageName = 'page');
    public function paginateSortedWithTrashed(Sorter $sorter, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    public function paginateFilteredWithTrashed(Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    public function paginateSortedFilteredWithTrashed(Sorter $sorter, Filterer $filterer, $perPage=50, $page = null,  $columns = ['*'], $pageName = 'page');
    
    public function countWithTrashed();
    public function countOnlyTrashed();

}