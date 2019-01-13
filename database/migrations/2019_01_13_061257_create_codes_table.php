<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('codes')) {
            Schema::create('codes', function (Blueprint $table) {
                $table->increments('id')->comment('主键自增');
                $table->string('code')->default('')->comment('邀请码');
                $table->integer('created_by')->unsigned()->default(0)->comment('用户id');
                $table->integer('used_by')->unsigned()->default(0)->comment('用户id');
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
        Schema::dropIfExists('codes');
    }
}
