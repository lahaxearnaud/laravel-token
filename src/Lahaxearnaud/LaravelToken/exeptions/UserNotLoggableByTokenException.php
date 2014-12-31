<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 30/12/14
 * Time: 22:52
 */

namespace Lahaxearnaud\LaravelToken\exeptions;


class UserNotLoggableByTokenException extends TokenException {

    public function __construct ($message = "", $code = 0, Exception $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }
}
