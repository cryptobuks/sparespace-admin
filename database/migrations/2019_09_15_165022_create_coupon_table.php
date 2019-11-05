<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->string('NAME', 255)->nullable();
        	$table->decimal('DISCOUNT', 20, 3)->nullable();
        	$table->integer('USE_DATE')->nullable();
        	$table->string('DEL_YN', 2)->default('N');
        	$table->integer('CREATE_DATE')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('coupon');
    }
}
