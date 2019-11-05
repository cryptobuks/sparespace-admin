<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestdetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_detail', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->bigInteger('REQUEST_ID', false, true)->nullable();
        	$table->string('BOXID', 40)->nullable();
        	$table->integer('PRICE')->nullable();
        	$table->integer('GOODS_COUNT')->nullable();
        	$table->date('START_DATE')->nullable();
        	$table->date('END_DATE')->nullable();
        	$table->tinyInteger('MANAGE_STATUS')->nullable();
        	$table->integer('STORAGE_MONTHS')->nullable();
        	$table->bigInteger('USER_ID', false, true)->nullable();
        	$table->text('NOTE');
        	$table->string('TRANS_NUMBER', 40)->nullable();
        	$table->string('DEL_YN', 2)->default('N');
        	$table->integer('CHECKTIME')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('request_detail');
    }
}
