<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTokensTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('tokens', function (Blueprint $table) {
			$table->increments('id');
			$table->text('token')->uniq();
			$table->integer('user_id')->unsigned()->index();
			$table->timestamp('expire_at');
			$table->timestamps();

			//$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::drop('tokens');
	}
}