<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('campaigns', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title',200);
			$table->text('link',200);
			$table->string('color',7);
			$table->string('background',7);
			$table->string('facebook',100);
			$table->string('twitter',100);
			$table->string('linkedin',100);
			$table->string('youtube',100);
			$table->text('menus');
			$table->text('logo');
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
		Schema::drop('campaigns');
	}

}
