<?php namespace Lahaxearnaud\LaravelToken\Security;

interface CryptInterface
{
    public function crypt($uncrypt);

    public function unCrypt($crypt);
}
