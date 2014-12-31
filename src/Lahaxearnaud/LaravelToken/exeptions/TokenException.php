<?php
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 30/12/14
 * Time: 22:35
 */

namespace Lahaxearnaud\LaravelToken\Exeptions;

use Exception;

/**
 * Class TokenException
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 *
 * @package Lahaxearnaud\LaravelToken\Exeptions
 */
abstract class TokenException extends \Exception {

    public function __construct ($message = "", $code = 0, Exception $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
    }
}
