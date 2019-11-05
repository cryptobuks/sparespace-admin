<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->string('CATEGORY', 255)->nullable();
        	$table->string('TITLE', 255)->nullable();
        	$table->text('CONTENTS');
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->bigInteger('ADMIN_ID', false, true)->nullable();
        	$table->string('DEL_YN', 2)->default('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('faq');
    }
}
