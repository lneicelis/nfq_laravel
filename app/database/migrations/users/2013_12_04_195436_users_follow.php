<?php

use Illuminate\Database\Migrations\Migration;

class UsersFollow extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('users_follow', function($table)
        {
            $table->increments('id');
            $table->integer('following_id');
            $table->integer('follower_id');
            $table->timestamps();

            $table->unique(array('following_id', 'follower_id'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('users_follow');
	}

}