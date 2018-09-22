<?php

namespace Xolens\PgLarautil\Test;

use DB;

class CleanSchemaBase extends TestCase
{
    /**
     * @test
     */
    public function clearSchema(){
        $schema = $this->schemaName();
        if($this->hasSchema()){
            DB::statement("DROP SCHEMA IF EXISTS ".$schema." CASCADE");
            DB::statement("CREATE SCHEMA ".$schema.";");
        }
        $this->assertTrue(true);
    }
}