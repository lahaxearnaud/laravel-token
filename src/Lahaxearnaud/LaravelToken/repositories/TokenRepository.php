<?php namespace Lahaxearnaud\LaravelToken\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Lahaxearnaud\LaravelToken\Generator\TokenGenerator;
use Lahaxearnaud\LaravelToken\Models\Token;

/**
 * Class TokenRepository
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * @package Lahaxearnaud\LaravelToken\Repositories
 */
class TokenRepository implements RepositoryInterface {
	/**
	 * @var Token
	 */
	protected $model;

	/**
	 * @var TokenGenerator
	 */
	protected $generator;

	public function __construct(Token $model, TokenGenerator $generator) {
		$this->model = $model;
		$this->generator = $generator;
	}

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
	public function create($userId = null, $allowLogin = false, $lifetime = 3600, $length = 50) {
		$token = new Token();
		$token->token = $this->generator->generateToken($length);
		$token->user_id = $userId;
		$token->expire_at = time() + $lifetime;
		$token->login = $allowLogin;

		return $token;
	}

	/**
	 * returns the model found
	 *
	 * @param int $id
	 *
	 * @return Token
	 */
	public function find($id) {

		return $this->model->findOrFail($id);
	}

	/**
	 * returns the model found
	 *
	 *
	 * @return Collection
	 */
	public function findByUser($userId) {

		return $this->model->whereUserId($userId)->get();
	}

	/**
	 * returns the Token found
	 *
	 * @param int $token
	 *
	 * @return Token
	 */
	public function findByToken($token, $userId = null) {

        $query = $this->model->whereToken($token);

        if($userId !== null) {
            $query->whereUserId($userId);
        }

        return $query->firstOrFail();
	}

	/**
	 * @param Token $token
	 *
	 * @return boolean|null
	 */
	public function delete(Token $token) {

		return $token->delete();
	}

	/**
	 * @param Token $token
	 *
	 * @return boolean
	 */
	public function save(Token $token) {

		return $token->save();
	}

	public function exists($tokenStr) {

		return $this->model->whereToken($tokenStr)->count() > 0;
	}

	/**
	 * @return Token
	 */
	public function getModel() {

		return $this->model;
	}
}

