<?php namespace Lahaxearnaud\LaravelToken;

use Illuminate\Support\ServiceProvider;
use Lahaxearnaud\LaravelToken\Security\TokenCrypt;
use Lahaxearnaud\LaravelToken\Generator\TokenGenerator;
use Lahaxearnaud\LaravelToken\Repositories\TokenRepository;
use Lahaxearnaud\LaravelToken\Models\Token;

class LaravelTokenServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
		$this->package('lahaxearnaud/laravel-token');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$app = $this->app;

		$app->bind('tokenrepository', function()
        {
            return new TokenRepository(new Token(), new TokenGenerator());
        });


		$app->bind('token', function() use ($app)
        {
            return new LaravelToken($app->make('tokenrepository'), new TokenCrypt());
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('token');
	}

}
