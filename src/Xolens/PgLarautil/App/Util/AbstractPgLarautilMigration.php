<?php

namespace Xolens\PgLarautil\App\Util;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use PgLarautilCreateDatabaseLogTriggerFunction;

abstract class AbstractPgLarautilMigration extends Migration 
{
    public static abstract function tableName();

    public static function tablePrefix(){
        return null;
    }
    
    public static function triggerPrefix(){
        return null;
    }
    
    public static function logEnabled(){
        return false;
    }
    
    public static function table(){
        return static::tablePrefix().static::tableName();
    }

    public static function trigger(){
        return static::triggerPrefix().static::tableName();
    }

    public static function registerForLog(){
        DB::statement("
            CREATE TRIGGER ".static::trigger()." AFTER INSERT OR UPDATE OR DELETE ON ".static::table()."
            FOR EACH ROW EXECUTE PROCEDURE ".PgLarautilCreateDatabaseLogTriggerFunction::table()."();
        ");
        return;
    }

    public static function unregisterFromLog(){
        DB::statement("
            DROP TRIGGER ".static::trigger()." ON ".static::table().";
        ");
        return;
    }
}
