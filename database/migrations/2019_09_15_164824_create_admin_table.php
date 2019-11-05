<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->integer('LAST_LOGIN')->nullable();
        	$table->string('ADMIN_NAME', 255)->nullable();
        	$table->string('ADMIN_ID', 255)->nullable();
        	$table->string('ADMIN_PWD', 255)->nullable();
        	$table->tinyInteger('STATUS')->nullable();
        	$table->tinyInteger('TYPE')->nullable();
        	$table->bigInteger('PRIVILEGE_ID',false, true)->nullable();
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
        Schema::drop('admin');
    }
}
