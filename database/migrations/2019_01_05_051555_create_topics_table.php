<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('topics')) {
            Schema::create('topics', function (Blueprint $table) {
                $table->increments('id')->comment('主键自增');
                $table->string('title')->default('')->comment('标题');
                $table->text('description')->nullable()->comment('简介');
                $table->string('thumb')->default('')->comment('图标');
                $table->tinyInteger('created_from')->unsigned()->default(0)->comment('0前台1后台');
                $table->integer('created_by')->unsigned()->default(0)->comment('创建者id');
                $table->tinyInteger('status')->unsigned()->default(0)->comment('0待审,1审核通过,2驳回,3删除');
                $table->tinyInteger('post_audit')->unsigned()->default(0)->comment('帖子是否审核,0不审核1审核');
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
