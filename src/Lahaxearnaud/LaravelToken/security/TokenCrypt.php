<?php namespace Lahaxearnaud\LaravelToken\Security;

use \Crypt as Crypt;

class TokenCrypt implements CryptInterface {
	public function crypt($uncrypt) {

		return Crypt::encrypt($uncrypt);
	}

	public function unCrypt($crypt) {

		return Crypt::decrypt($crypt);
	}
}
