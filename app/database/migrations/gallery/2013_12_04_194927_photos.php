<?php

use Illuminate\Database\Migrations\Migration;

class Photos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('photos', function($table)
        {
            $table->increments('id');
            $table->integer('album_id');
            $table->string('file_name', 22);
            $table->string('description', 255)->nullable();
            $table->integer('status')->default(1);
            $table->integer('no_tags')->default(0);
            $table->integer('no_comments')->default(0);
            $table->integer('no_likes')->default(0);
            $table->integer('no_views')->default(0);
            $table->timestamp('shoot_date')->nullable();
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
        Schema::drop('photos');
	}

}