<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushnotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notification', function(Blueprint $table){
        	$table->bigIncrements('ID');
        	$table->string('TITLE', 255)->nullable();
        	$table->text('CONTENTS')->nullable();
        	$table->tinyInteger('TYPE')->default(2);
        	$table->integer('REGTIME')->nullable();
        	$table->integer('UPDTIME')->nullable();
        	$table->string('DEL_YN', 2)->default('N');
        	$table->bigInteger('ADMIN_ID', false, true)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('push_notification');
    }
}
