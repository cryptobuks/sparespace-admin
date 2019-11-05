<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrivilegeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privilege', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->tinyInteger('ADMIN_TYPE')->default(1);
        	$table->string('STATUS', 2)->default('N');
        	$table->string('STATUS_1', 2)->default('N');
        	$table->string('STATUS_2', 2)->default('N');
        	$table->string('MEMBER', 2)->default('N');
        	$table->string('MEMBER_1', 2)->default('N');
        	$table->string('MEMBER_2', 2)->default('N');
        	$table->string('MEMBER_3', 2)->default('N');
        	$table->string('MEMBER_4', 2)->default('N');
        	$table->string('GOODS', 2)->default('N');
        	$table->string('GOODS_1', 2)->default('N');
        	$table->string('GOODS_2', 2)->default('N');
        	$table->string('GOODS_3', 2)->default('N');
        	$table->string('MANAGE', 2)->default('N');
        	$table->string('MANAGE_1', 2)->default('N');
        	$table->string('MANAGE_2', 2)->default('N');
        	$table->string('ADMIN', 2)->default('N');
        	$table->string('ADMIN_1', 2)->default('N');
        	$table->string('ADMIN_2', 2)->default('N');
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
        Schema::drop('privilege');
    }
}
