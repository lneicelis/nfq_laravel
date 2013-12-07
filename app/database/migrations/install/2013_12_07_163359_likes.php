<?php

use Illuminate\Database\Migrations\Migration;

class Likes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('likes', function($table)
        {
            $table->increments('id');
            $table->string('type', 100);
            $table->integer('obj_id');
            $table->integer('user_id');
            $table->timestamps();

        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('likes');
	}

}