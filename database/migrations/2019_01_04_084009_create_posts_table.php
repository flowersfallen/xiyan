<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->increments('id')->comment('主键自增');
                $table->integer('topic_id')->unsigned()->default(0)->comment('话题id');
                $table->string('title')->default('')->comment('标题');
                $table->text('content')->nullable()->comment('内容');
                $table->string('attachment')->default('')->comment('附件');
                $table->tinyInteger('type')->unsigned()->default(0)->comment('附件类型，0图片,1视频');
                $table->tinyInteger('created_from')->unsigned()->default(0)->comment('0前台1后台');
                $table->integer('created_by')->unsigned()->default(0)->comment('创建者id');
                $table->tinyInteger('status')->unsigned()->default(0)->comment('0待审,1审核通过,2驳回,3删除');
                $table->integer('digg')->unsigned()->default(0)->comment('点赞数');
                $table->integer('comment')->unsigned()->default(0)->comment('评论数');
                $table->integer('share')->unsigned()->default(0)->comment('分享数');
                $table->integer('pv')->unsigned()->default(0)->comment('浏览数');
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
