<?php

namespace Xolens\Larautil\App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LarautilCreateDatabaseLogTable;


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
        $this->table = LarautilCreateDatabaseLogTable::table();
        parent::__construct($attributes);
    }
}