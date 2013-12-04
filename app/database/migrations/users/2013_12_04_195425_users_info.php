<?php

use Illuminate\Database\Migrations\Migration;

class UsersInfo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('users_info', function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('age')->nullable();
            $table->string('skype', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('picture', 255)->default('profile.jpg');
            $table->timestamps();

            $table->unique('user_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('users_info');
	}

}