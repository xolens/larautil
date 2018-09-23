<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Xolens\PgLarautil\App\Util\PgLarautilMigration;

class PgLarautilCreateDatabaseLogTable extends PgLarautilMigration
{
    const TYPE_INSERT = "INSERT";
    const TYPE_UPDATE = "UPDATE";
    const TYPE_DELETE = "DELETE";
    /**
     * Return table name
     *
     * @return string
     */
    public static function tableName(){
        return 'database_log_table';
    }    

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::table(), function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_schema');
            $table->string('log_table');
            $table->enum('log_type',[self::TYPE_INSERT,self::TYPE_UPDATE,self::TYPE_DELETE]);
            $table->text('last_value')->nullable();
            $table->timestamp('log_at');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::table());
    }
}
