<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->bigInteger('USER_ID',false, true)->nullable();
        	$table->string('ADDRESS', 255)->nullable();
        	$table->string('TRANS_NUMBER', 255)->nullable();
        	$table->string('DEL_YN', 2)->default('N');
        	$table->string('STATUS', 100)->default('PROGRESS');
        	$table->string('SDETAIL', 100)->nullable();
        	$table->string('CONDITION', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('request');
    }
}
