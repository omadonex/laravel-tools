<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsDb;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_permission', function (Blueprint $table) {
            UtilsDb::addPrimaryStr($table);

            $table->string('permission_group_id', ConstCustom::DB_FIELD_LEN_STR_KEY)->index();
            $table->smallInteger('sort_index')->default(0)->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acl_permission');
    }
};
