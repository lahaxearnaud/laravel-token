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
			$table->string('token', 128)->unique();
			$table->integer('user_id')->unsigned()->index()->nullable();
			$table->boolean('login')->default(false);
			$table->timestamp('expire_at');
			$table->timestamps();
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
