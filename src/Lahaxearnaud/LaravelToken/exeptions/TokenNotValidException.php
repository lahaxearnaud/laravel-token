<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 30/12/14
 * Time: 22:52
 */

namespace Lahaxearnaud\LaravelToken\exeptions;


use Lahaxearnaud\LaravelToken\Models\Token;

class TokenNotValidException extends TokenException {

    public function __construct (Token $token, \Exception $previous = NULL)
    {
        parent::__construct('Not a valid token', 0, $previous);
    }
}
