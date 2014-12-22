<?php namespace Lahaxearnaud\LaravelToken\Security;

use Crypt as Crypt;
use Illuminate\Encryption\DecryptException;

/**
 * Class TokenCrypt
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * @package Lahaxearnaud\LaravelToken\Security
 */
class TokenCrypt implements CryptInterface
{

    /**
     * @param $uncrypt
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function crypt ($uncrypt)
    {

        return Crypt::encrypt($uncrypt);
    }

    /**
     * @param $crypt
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function unCrypt ($crypt)
    {

        try {
            return Crypt::decrypt($crypt);
        } catch (DecryptException $e) {
            return '';
        }
    }
}
