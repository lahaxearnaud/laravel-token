<?php namespace Lahaxearnaud\LaravelToken\Security;

/**
 * Interface CryptInterface
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * @package Lahaxearnaud\LaravelToken\Security
 */
interface CryptInterface
{
    /**
     * @param $uncrypt
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function crypt($uncrypt);

    /**
     * @param $crypt
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function unCrypt($crypt);
}
