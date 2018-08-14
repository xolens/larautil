<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Xolens\Larautil\App\Util\LarautilMigration;
use Illuminate\Support\Facades\DB;

class LarautilCreateDatabaseLogTriggerFunction extends LarautilMigration
{
    /**
     * Return table name
     *
     * @return string
     */
    public static function tableName(){
        return 'database_log_trigger_function';
    }    

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE OR REPLACE FUNCTION ".self::table()."() RETURNS trigger
            LANGUAGE plpgsql AS $$
            DECLARE
                __log_type varchar(20);
                __last_value json;
            BEGIN
                IF TG_OP = 'INSERT' THEN
                    __log_type = '".LarautilCreateDatabaseLogTable::TYPE_INSERT."';
                    __last_value = row_to_json(NEW);

                ELSEIF TG_OP = 'UPDATE' THEN
                    __log_type = '".LarautilCreateDatabaseLogTable::TYPE_UPDATE."';
                    __last_value = row_to_json(NEW);
                
                ELSEIF TG_OP = 'DELETE' THEN
                    __log_type = '".LarautilCreateDatabaseLogTable::TYPE_DELETE."';
                    __last_value = NULL;
                END IF;

                INSERT INTO ".LarautilCreateDatabaseLogTable::table()." (log_schema, log_table, log_type, last_value,log_at) 
                VALUES (TG_TABLE_SCHEMA, TG_TABLE_NAME, __log_type, __last_value, now());

                RETURN NULL;
            END;
        $$;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            DROP FUNCTION IF EXISTS ".self::table()."();
        ");
    }
}
