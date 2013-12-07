<?php

use Illuminate\Database\Migrations\Migration;

class Comments extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('comments', function($table)
        {
            $table->increments('id');
            $table->string('type', 100);
            $table->integer('obj_id');
            $table->integer('user_id');
            $table->string('comment', 255)->nullable();
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
        Schema::drop('comments');
	}

}