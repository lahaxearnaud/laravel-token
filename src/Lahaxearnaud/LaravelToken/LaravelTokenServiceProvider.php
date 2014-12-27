<?php namespace Lahaxearnaud\LaravelToken;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\ServiceProvider;
use Lahaxearnaud\LaravelToken\Generator\TokenGenerator;
use Lahaxearnaud\LaravelToken\Models\Token;
use Lahaxearnaud\LaravelToken\Repositories\TokenRepository;
use Lahaxearnaud\LaravelToken\Security\TokenCrypt;

class LaravelTokenServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = FALSE;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot ()
    {
        $this->package('lahaxearnaud/laravel-token');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register ()
    {
        $app = $this->app;

        $instance = $this;

        $app->bind('tokenrepository', function () {
            return new TokenRepository(new Token(), new TokenGenerator());
        });


        $app->bind('token', function () use ($app) {
            return new LaravelToken($app->make('tokenrepository'), new TokenCrypt());
        });

        \Route::filter('auth.token', function () use ($instance) {
            try {
                $strToken = $instance->getTokenValueFromRequest();
                $strToken = \Token::uncryptToken($strToken);

                $token = \Token::findByToken($strToken);

                if (!\Token::isValid($token)) {

                    return \Response::make('Unauthorized (Token not valid)', 401);
                }

            } catch (ModelNotFoundException $e) {
                \Event::fire('token.notFound', array($e));

                return \Response::make('Unauthorized (Token not found)', 401);
            }
        });

        \Route::filter('auth.token.auth', function () use ($instance) {
            try {
                $strToken = $instance->getTokenValueFromRequest();

                $strToken = \Token::uncryptToken($strToken);

                $token = \Token::findByToken($strToken);

                if (!\Token::isValid($token)) {

                    return \Response::make('Unauthorized (Token not valid)', 401);
                }

                $user = $token->user;

                if ($user === NULL) {
                    Event::fire('token.notLoginToken', array($token));

                    return \Response::make('Unauthorized (Not a login Token)', 401);
                }

                if ($user->loggableByToken()) {
                    \Auth::login($user);

                    \Event::fire('token.logged', array($token, $user));
                } else {
                    \Event::fire('token.notLoggableUser', array($token, $user));

                    return \Response::make('Unauthorized (Logged by token forbidden)', 401);
                }

            } catch (ModelNotFoundException $e) {
                \Event::fire('token.notFound', array($e));

                return \Response::make('Unauthorized (Token not found)', 401);
            }
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides ()
    {
        return array('token');
    }


    public function getTokenValueFromRequest()
    {
        $tokenFieldsName = \Config::get('lahaxearnaud/laravel-token:tokenFieldName');

        if(!is_string($tokenFieldsName)) {
            $tokenFieldsName = 'token';
        }

        $token = \Input::get($tokenFieldsName);

        if(empty($token)) {
            $token = \Request::header($tokenFieldsName);
        }

        if(empty($token)) {
            $token = \Cookie::get($tokenFieldsName);
        }

        return $token;
    }
}

