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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedSmallInteger('phone_code')->nullable();
            $table->unsignedBigInteger('phone')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('display_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('opt_name')->nullable();
            $table->string('avatar')->nullable();

            $table->unique(['phone_code', 'phone'], 'phone_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_code');
            $table->dropColumn('phone');
            $table->dropColumn('phone_verified_at');
            $table->dropColumn('display_name');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('opt_name');
            $table->dropColumn('avatar');
        });
    }
};
