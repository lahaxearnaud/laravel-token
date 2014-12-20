<?php namespace Lahaxearnaud\LaravelToken;

use Illuminate\Support\Facades\Facade;

class LaravelTokenFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'token'; }
}
