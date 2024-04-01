<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_column_set', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('page_id');
            $table->string('table_id');
            $table->string('columns');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_column_set');
    }
};
