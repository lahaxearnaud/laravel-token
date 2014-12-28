<?php namespace Lahaxearnaud\LaravelToken\Models;
/**
 * Created by PhpStorm.
 * Author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * Date: 24/12/14
 * Time: 13:42
 */

namespace Lahaxearnaud\LaravelToken\models;


interface UserTokenInterface {

    /**
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return boolean
     */
    public function loggableByToken();
}
