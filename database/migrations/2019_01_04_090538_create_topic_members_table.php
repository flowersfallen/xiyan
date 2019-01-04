<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('topic_members')) {
            Schema::create('topic_members', function (Blueprint $table) {
                $table->increments('id')->comment('主键自增');
                $table->integer('topic_id')->unsigned()->default(0)->comment('话题id');
                $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
                $table->string('type')->default('')->comment('用户角色,1创建者');
                $table->tinyInteger('status')->unsigned()->default(0)->comment('0待审,1审核通过,2驳回,3删除');
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
