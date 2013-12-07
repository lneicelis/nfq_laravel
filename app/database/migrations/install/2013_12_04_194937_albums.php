<?php

use Illuminate\Database\Migrations\Migration;

class Albums extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('albums', function($table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title', 100);
            $table->string('short_description', 100)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('location', 100)->nullable();
            $table->integer('no_photos')->default(0);
            $table->integer('no_comments')->default(0);
            $table->integer('no_likes')->default(0);
            $table->integer('no_views')->default(0);
            $table->integer('cover_photo')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('albums');
	}

}