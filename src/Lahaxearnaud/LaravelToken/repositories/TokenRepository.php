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
		$this->login = $allowLogin;

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
	 * @param int $id
	 *
	 * @return Collection
	 */
	public function findByUser($userId) {

		return $this->model->where('user_id', $userId)->get();
	}

	/**
	 * returns the Token found
	 *
	 * @param int $token
	 *
	 * @return Collection
	 */
	public function findByToken($token, $userId = null) {

        $query = $this->model->where('token', $token);

        if($userId !== null) {
            $query->where('user_id', $userId);
        }

        return $query->firstOrFail();
	}

	/**
	 * @param Token $token
	 *
	 * @return bool
	 */
	public function delete(Token $token) {

		return $token->delete();
	}

	/**
	 * @param Token $token
	 *
	 * @return Token
	 */
	public function save(Token $token) {

		return $token->save();
	}

	public function exists($tokenStr) {

		return $this->model->where('token', $tokenStr)->count() > 0;
	}

	/**
	 * @return Token
	 */
	public function getModel() {

		return $this->model;
	}
}

