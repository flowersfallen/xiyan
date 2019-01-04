<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->increments('id')->comment('主键自增');
                $table->integer('topic_id')->unsigned()->default(0)->comment('话题id');
                $table->text('content')->nullable()->comment('内容');
                $table->tinyInteger('created_from')->unsigned()->default(0)->comment('0前台1后台');
                $table->integer('created_by')->unsigned()->default(0)->comment('创建者id');
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
