<?php

use Illuminate\Database\Migrations\Migration;

class Settings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('settings', function($table)
        {
            $table->increments('id');
            $table->string('type', 255);
            $table->string('name', 255);
            $table->string('value', 255);
            $table->timestamps();

            $table->unique(array('type', 'name'));
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('settings');
	}

}