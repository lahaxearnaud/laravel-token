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
	}

	public function setUp() {

		parent::setUp();

		$this->app['path.database'] = __DIR__;


        $migration = new CreateTokensTable();
        $migration->up();
	}
}