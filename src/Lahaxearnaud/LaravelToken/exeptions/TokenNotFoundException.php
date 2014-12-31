<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 30/12/14
 * Time: 22:52
 */

namespace Lahaxearnaud\LaravelToken\exeptions;


class TokenNotFoundException extends TokenException {

    public function __construct (\Exception $previous = NULL)
    {
        parent::__construct("", 0, $previous);
    }
}
