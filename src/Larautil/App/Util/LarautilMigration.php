<?php

namespace Xolens\Larautil\App\Util;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use LarautilCreateDatabaseLogTriggerFunction;

abstract class LarautilMigration extends AbstractLarautilMigration 
{
    public static function tablePrefix(){
        return config('larautil.database_table_prefix');
    }

    public static function triggerPrefix(){
        return config('larautil.database_trigger_prefix');
    }
}
