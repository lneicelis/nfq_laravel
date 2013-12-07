<?php

use Illuminate\Database\Migrations\Migration;

class PhotoTags extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('photo_tags', function($table)
        {
            $table->increments('id');
            $table->integer('photo_id');
            $table->string('title', 100);
            $table->string('description', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('color', 15)->nullable();
            $table->string('size', 10)->nullable();
            $table->string('x', 8);
            $table->string('y', 8);
            $table->timestamps();

            $table->index('photo_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('photo_tags');
	}

}