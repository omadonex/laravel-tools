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
        Schema::create('support_history_event', function (Blueprint $table) {
            $table->tinyIncrements('id');
            UtilsDb::addProtectedGenerateField($table);

            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_history_event');
    }
};
