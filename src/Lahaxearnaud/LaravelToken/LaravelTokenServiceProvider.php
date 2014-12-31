<?php namespace Lahaxearnaud\LaravelToken;

use \Event as Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\ServiceProvider;
use Lahaxearnaud\LaravelToken\commands\ClearTokenCommand;
use Lahaxearnaud\LaravelToken\exeptions\NotLoginTokenException;
use Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException;
use Lahaxearnaud\LaravelToken\exeptions\TokenNotValidException;
use Lahaxearnaud\LaravelToken\exeptions\UserNotLoggableByTokenException;
use Lahaxearnaud\LaravelToken\Generator\TokenGenerator;
use Lahaxearnaud\LaravelToken\Repositories\TokenRepository;
use Lahaxearnaud\LaravelToken\Security\TokenCrypt;
use \Response as Response;
use \Route as Route;
use \Auth as Auth;

class LaravelTokenServiceProvider extends ServiceProvider
{

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

        $this->app->bind('tokenrepository', function () {
            return new TokenRepository(new \Lahaxearnaud\LaravelToken\Models\Token(), new TokenGenerator());
        });

        $app = $this->app;

        $this->app->singleton('token', function () use ($app) {
            return new LaravelToken($app->make('tokenrepository'), new TokenCrypt());
        });

        Route::filter('token', function () use ($app) {
            $tokenManager = $app->make('token');

            $strToken = $tokenManager->getTokenValueFromRequest();
            $strToken = $tokenManager->uncryptToken($strToken);
            try {
                $token = $tokenManager->findByToken($strToken);

                $tokenManager->setCurrentToken($token);

                if (!$tokenManager->isValid($token)) {

                    throw new TokenNotValidException($token);
                }

            } catch (ModelNotFoundException $e) {

                throw new TokenNotFoundException($e);
            }
        });

        Route::filter('token.auth', function () use ($app) {
            $tokenManager = $app->make('token');

            $strToken = $tokenManager->getTokenValueFromRequest();
            $strToken = $tokenManager->uncryptToken($strToken);
            try {
                $token = $tokenManager->findByToken($strToken);

                $tokenManager->setCurrentToken($token);

                if (!$tokenManager->isValid($token)) {

                    throw new TokenNotValidException($token);
                }

                $user = $token->user;

                if ($user === null) {

                    throw new NotLoginTokenException($token);
                }

                if ($user->loggableByToken()) {
                    Auth::login($user);

                    Event::fire('token.logged', array($token, $user));
                } else {

                    throw new UserNotLoggableByTokenException($token, $user);
                }

            } catch (ModelNotFoundException $e) {

                throw new TokenNotFoundException($e);
            }
        });

        $this->app['token.clear'] = $this->app->share(function ($app) {
            return new ClearTokenCommand();
        });

        $this->commands('token.clear');
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

