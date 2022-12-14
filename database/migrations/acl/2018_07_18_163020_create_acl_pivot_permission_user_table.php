<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_pivot_permission_user', function (Blueprint $table) {
            $table->string('permission_id', ConstCustom::DB_FIELD_LEN_STR_KEY)->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedTinyInteger('assign_type')->default(IAclService::ASSIGN_TYPE_SYSTEM)->index();
            $table->unsignedInteger('assign_user_id')->nullable()->index();
            $table->timestamp('assign_starting_at')->nullable()->index();
            $table->timestamp('assign_expires_at')->nullable()->index();

            $table->unique(['permission_id', 'user_id'], 'permission_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acl_pivot_permission_user');
    }
};
