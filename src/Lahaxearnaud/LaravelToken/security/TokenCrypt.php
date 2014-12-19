<?php namespace Lahaxearnaud\LaravelToken\Security;

use Illuminate\Database\Eloquent\Collection;
use Lahaxearnaud\LaravelToken\Models\Token;
use \Crypt as Crypt;

class TokenCrypt implements CryptInterface
{
    public function crypt($uncrypt)
    {

		return Crypt::encrypt($uncrypt);
    }

    public function unCrypt($crypt)
    {

		return Crypt::decrypt($uncrypt);
    }
}