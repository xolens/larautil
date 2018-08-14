<?php

namespace Xolens\Larautil\App\Model;

use Illuminate\Database\Eloquent\Model;
use LarautilCreateDatabaseLogTable;


class Log extends Model
{
    protected $fillable = [
        'log_schema',
        'log_table',
        'log_type',
        'last_value',
        'log_at',
    ];
        
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;
    
    function __construct() {
        $this->table = LarautilCreateDatabaseLogTable::table();
        parent::__construct();
    }
}