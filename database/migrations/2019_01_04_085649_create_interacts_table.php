<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInteractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('interacts')) {
            Schema::create('interacts', function (Blueprint $table) {
                $table->increments('id')->comment('主键自增');
                $table->integer('post_id')->unsigned()->default(0)->comment('帖子id');
                $table->string('type')->default('')->comment('互动类型,digg点赞,comment评论,share分享');
                $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
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
