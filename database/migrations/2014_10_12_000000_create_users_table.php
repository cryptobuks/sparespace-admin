<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('USER_EMAIL', 255)->nullable();
            $table->string('USER_CELL', 255)->nullable();
            $table->tinyInteger('USER_TYPE');
            $table->integer('REGTIME')->nullable();
            $table->integer('UPDTIME')->nullable();
            $table->char('DEL_YN')->default('N');
            $table->text('ADDR1');
            $table->string('PWD', 255)->nullable();
            $table->string('USERID', 255)->nullable();
            $table->string('USERNAME', 255)->nullable();
            $table->text('ADDR2');
            $table->string('STATUS', 255)->nullable();
            $table->string('UNIQUE_ID', 255)->nullable();
            $table->string('BIRTH_NO', 255)->nullable();
            $table->string('EXIT_YN', 2)->defaut('N');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
