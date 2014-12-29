<?php namespace Lahaxearnaud\LaravelToken;

use Config as Config;
use Cookie as Cookie;
use Event as Event;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\ServiceProvider;
use Input as Input;
use Lahaxearnaud\LaravelToken\commands\ClearTokenCommand;
use Lahaxearnaud\LaravelToken\Generator\TokenGenerator;
use Lahaxearnaud\LaravelToken\Repositories\TokenRepository;
use Lahaxearnaud\LaravelToken\Security\TokenCrypt;
use Request as Request;
use Response as Response;
use Route as Route;
use Token as Token;

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
        $app = $this->app;

        $instance = $this;

        $app->bind('tokenrepository', function () {
            return new TokenRepository(new \Lahaxearnaud\LaravelToken\Models\Token(), new TokenGenerator());
        });


        $app->bind('token', function () use ($app) {
            return new LaravelToken($app->make('tokenrepository'), new TokenCrypt());
        });

        Route::filter('token', function () use ($instance) {
            try {
                $strToken = $instance->getTokenValueFromRequest();
                $strToken = Token::uncryptToken($strToken);

                $token = Token::findByToken($strToken);

                Token::setCurrentToken($token);

                if (!Token::isValid($token)) {

                    return Response::make('Unauthorized (Token not valid)', 401);
                }

            } catch (ModelNotFoundException $e) {
                Event::fire('token.notFound', array($e));

                return Response::make('Unauthorized (Token not found)', 401);
            }
        });

        Route::filter('token.auth', function () use ($instance) {
            try {
                $strToken = $instance->getTokenValueFromRequest();

                $strToken = Token::uncryptToken($strToken);

                $token = Token::findByToken($strToken);

                Token::setCurrentToken($token);

                if (!Token::isValid($token)) {

                    return Response::make('Unauthorized (Token not valid)', 401);
                }

                $user = $token->user;

                if ($user === null) {
                    Event::fire('token.notLoginToken', array($token));

                    return Response::make('Unauthorized (Not a login Token)', 401);
                }

                if ($user->loggableByToken()) {
                    Auth::login($user);

                    Event::fire('token.logged', array($token, $user));
                } else {
                    Event::fire('token.notLoggableUser', array($token, $user));

                    return Response::make('Unauthorized (Logged by token forbidden)', 401);
                }

            } catch (ModelNotFoundException $e) {
                Event::fire('token.notFound', array($e));

                return Response::make('Unauthorized (Token not found)', 401);
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

    /**
     * Get the token from the request. We try to get it from GET/POST then headers then cookies
     *
     * @author LAHAXE Arnaud <alahaxe@boursorama.fr>
     *
     * @return mixed
     */
    public function getTokenValueFromRequest()
    {
        $tokenFieldsName = Config::get('lahaxearnaud/laravel-token:tokenFieldName');

        if (!is_string($tokenFieldsName)) {
            $tokenFieldsName = 'token';
        }

        $token = Input::get($tokenFieldsName);

        if (empty($token)) {
            $token = Request::header($tokenFieldsName);
        }

        if (empty($token)) {
            $token = Cookie::get($tokenFieldsName);
        }

        return $token;
    }
}

