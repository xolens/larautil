<?php

namespace Xolens\PgLarautil\App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PgLarautilCreateDatabaseLogTable;


class Log extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'log_schema',
        'log_table',
        'log_type',
        'last_value',
        'log_at',
    ];
        
    public $timestamps = false;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;
    
    function __construct(array $attributes = []) {
        $this->table = PgLarautilCreateDatabaseLogTable::table();
        parent::__construct($attributes);
    }
}