<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('friends')) {
            Schema::create('friends', function (Blueprint $table) {
                $table->increments('id')->comment('主键自增');
                $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
                $table->integer('follow_id')->unsigned()->default(0)->comment('关注id');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
