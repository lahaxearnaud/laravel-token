<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 30/12/14
 * Time: 22:52
 */

namespace Lahaxearnaud\LaravelToken\exeptions;


use Lahaxearnaud\LaravelToken\Models\Token;

class UserNotLoggableByTokenException extends TokenException {

    public function __construct (Token $token, \User $user, \Exception $previous = NULL)
    {
        \Event::fire('token.notLoggableUser', array($token, $user));

        parent::__construct("User can't logged in with a token", 0, $previous);
    }
}
