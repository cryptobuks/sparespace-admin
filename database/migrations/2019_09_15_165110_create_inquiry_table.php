<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry', function(Blueprint $table){
        	$table->increments('ID');
        	$table->bigInteger('USER_ID', false, true)->nullable();
        	$table->string('CATEGORY', 255)->nullable();
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->tinyInteger('STATUS')->default(0);
        	$table->text('QUERY');
        	$table->text('ANSWER');
        	$table->integer('AWRTIME')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inquiry');
    }
}
