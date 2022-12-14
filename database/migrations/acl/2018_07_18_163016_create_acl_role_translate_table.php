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
        Schema::create('acl_role_translate', function (Blueprint $table) {
            UtilsDb::addTransFields($table, true);
            UtilsDb::addProtectedGenerateField($table);

            $table->string('name');
            $table->text('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acl_role_translate');
    }
};
