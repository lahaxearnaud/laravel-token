<?php namespace Lahaxearnaud\LaravelToken\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Lahaxearnaud\LaravelToken\Models\Token;

interface RepositoryInterface
{

    /**
     * Create a token
     *
     * @param  integer  $userId   user id
     * @param  integer $lifetime  lifetime in second
     * @param  integer $length    length of the token
     *
     * @return Token              instance of token
     */
    public function create($userId, $lifetime = 3600, $length = 50);

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
     * @param int $id
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
     * @param integer $id
     *
     * @return bool
     */
    public function delete($id);
}