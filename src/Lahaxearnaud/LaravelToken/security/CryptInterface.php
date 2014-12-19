<?php namespace Lahaxearnaud\LaravelToken\Security;

use Illuminate\Database\Eloquent\Collection;
use Lahaxearnaud\LaravelToken\Models\Token;

interface CryptInterface
{
    public function crypt($uncrypt);

    public function unCrypt($crypt);
}