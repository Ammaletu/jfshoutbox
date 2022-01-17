<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Creates the table for the shoutbox answers.
 */
class CreateAnswersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('answers', function (Blueprint $table) {
			$table->id();
			$table->timestamps();
			$table->string('nickname');
			$table->string('answer');
			$table->enum('status', ['in_moderation', 'published', 'blocked']);
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('answers');
	}
}
