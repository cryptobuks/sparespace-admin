<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_log', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->bigInteger('USER_ID')->nullable();
        	$table->string('DEL_YN', 2)->default('N');
        	$table->string('STATUS', 100)->nullable();
        	$table->string('SDETAIL', 100)->nullable();
        	$table->string('PAY_DESC', 255)->nullable();
        	$table->integer('COST')->nullable();
        	$table->string('TRANS_NUM', 100)->nullable();
        	$table->string('AGREE_NUM', 100)->nullable();
        	$table->string('RES_CODE', 100)->nullable();
        	$table->text('NOTE');
        	$table->string('INSTALLMENT', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payment_log');
    }
}
