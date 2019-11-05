<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('box', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->string('NAME', 255)->nullable();
        	$table->integer('WIDTH')->nullable();
        	$table->integer('LENGTH')->nullable();
        	$table->integer('HEIGHT')->nullable();
        	$table->integer('TOTAL_COUNT')->nullable();
        	$table->integer('USING_COUNT')->nullable();
        	$table->integer('AVAILABLE_COUNT')->nullable();
        	$table->string('DEL_YN', 2)->default('N');
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->integer('MAX_WEIGHT')->nullable();
        	$table->text('EXPECT_QTY');
        	$table->integer('PRICE')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('box');
    }
}
