<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        Schema::create('acl_role', function (Blueprint $table) {
            UtilsDb::addPrimaryStr($table);
            UtilsDb::addProtectedGenerateField($table);

            $table->boolean('is_staff')->default(false)->index();
            $table->boolean('is_hidden')->default(false)->index();
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
        Schema::dropIfExists('acl_role');
    }
};
