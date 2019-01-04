<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notices')) {
            Schema::create('notices', function (Blueprint $table) {
                $table->increments('id')->comment('主键自增');
                $table->integer('from')->unsigned()->default(0)->comment('来源,0系统,其它用户id');
                $table->integer('to')->unsigned()->default(0)->comment('用户id');
                $table->string('title')->default('')->comment('标题');
                $table->string('message')->default('')->comment('内容');
                $table->tinyInteger('state')->unsigned()->default(0)->comment('0未读,1已读');
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
