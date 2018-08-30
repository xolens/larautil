<?php

namespace Xolens\PgLarautil\App\Util;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use PgLarautilCreateDatabaseLogTriggerFunction;

abstract class PgLarautilMigration extends AbstractPgLarautilMigration
{
    public static function tablePrefix(){
        return config('pglarautil.database_table_prefix');
    }

    public static function triggerPrefix(){
        return config('pglarautil.database_trigger_prefix');
    }
}
