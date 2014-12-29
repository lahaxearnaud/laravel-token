<?php
use Orchestra\Testbench\TestCase as TestBenchTestCase;

class TestCase extends TestBenchTestCase {

	protected function getPackageProviders() {

		return array('Lahaxearnaud\LaravelToken\LaravelTokenServiceProvider');
	}

	protected function getPackagePath() {

		return realpath(implode(DIRECTORY_SEPARATOR, array(
			__DIR__,
			'..',
			'src',
			'LahaxeArnaud',
			'LaravelToken',
		)));
	}

	/**
	 * Define environment setup.
	 *
	 * @param  \Illuminate\Foundation\Application  $app
	 * @return void
	 */
	protected function getEnvironmentSetUp($app) {
		$app['config']->set('database.default', 'testbench');
		$app['config']->set('database.connections.testbench', array(
			'driver' => 'sqlite',
			'database' => ':memory:',
			'prefix' => '',
		));
        $app['config']->set('app.key', md5_file(__FILE__));

		Route::get('/token/auth', array('before' => 'token.auth', function () {
			echo "auth";
		}));

		Route::get('/token/simple', array('before' => 'token', function () {
			echo "token";
		}));

		Route::enableFilters();
	}

	public function setUp() {

		parent::setUp();

		$this->app['path.database'] = __DIR__;


        $migration = new CreateTokensTable();
        $migration->up();

		Schema::create('users', function (\Illuminate\Database\Schema\Blueprint $table) {
			$table->increments('id');
			$table->string('username', 128)->unique();
			$table->timestamps();
		});

		$user = new User();
		$user->username = 'jerry';
		$user->save();

		$user = new User();
		$user->username = 'khan';
		$user->save();
	}
}