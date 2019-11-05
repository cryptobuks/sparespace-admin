<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailgoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_goods', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->string('GOODSID', 40)->nullable();
        	$table->string('GOODS_NAME', 255)->nullable();
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->string('DEL_YN', 2)->default('N');
        	$table->string('IMAGE', 255)->nullable();
        	$table->tinyInteger('STATUS')->nullable();
        	$table->string('TRANS_NUMBER', 40)->nullable();
        	$table->string('BOXID', 40)->nullable();
        	$table->string('RESET_YN', 2)->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('detail_goods');
    }
}
