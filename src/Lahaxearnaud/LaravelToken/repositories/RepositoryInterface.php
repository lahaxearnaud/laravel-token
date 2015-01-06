<?php namespace Lahaxearnaud\LaravelToken\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Lahaxearnaud\LaravelToken\Models\Token;

/**
 * Interface RepositoryInterface
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * @package Lahaxearnaud\LaravelToken\Repositories
 */
interface RepositoryInterface {

	/**
	 * Create a token
	 *
	 * @param  integer  $userId   user id
	 * @param  boolean  $allowLogin   is token usable for login
	 * @param  integer $lifetime  lifetime in second
	 * @param  integer $length    length of the token
	 *
	 * @return Token              instance of token
	 */
	public function create($userId, $allowLogin = false, $lifetime = 3600, $length = 50);

	/**
	 * returns the model found
	 *
	 * @param int $id
	 *
	 * @return Token
	 */
	public function find($id);

	/**
	 * returns the Token found
	 *
	 *
	 * @return Collection
	 */
	public function findByUser($idUser);

	/**
	 * returns the Token found
	 *
	 * @param int $token
	 *
	 * @return Collection
	 */
	public function findByToken($token, $userId);

	/**
	 * @param Token $token
	 *
	 * @return bool
	 */
	public function delete(Token $token);

	/**
	 * @param Token $token
	 *
	 * @return Token
	 */
	public function save(Token $token);
}
