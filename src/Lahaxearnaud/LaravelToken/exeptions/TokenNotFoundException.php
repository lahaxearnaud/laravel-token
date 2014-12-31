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
        \Event::fire('token.notFound', array($previous));

        parent::__construct("Token not found", 0, $previous);
    }
}
