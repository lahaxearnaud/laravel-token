<?php namespace Lahaxearnaud\LaravelToken\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use UserFilterableInterface;
use Lahaxearnaud\LaravelToken\Models\Token;

class TokenRepository implements RepositoryInterface
{
    /**
     * @var Token
     */
    protected $model;

    /**
     * @var TokenGenerator
     */
    protected $generator;

    public function __construct(Token $model, TokenGenerator $generator)
    {
        $this->model = $model;
        $this->generator = $generator;
    }

    /**
     * Create a token
     *
     * @param  integer  $userId   user id
     * @param  integer $lifetime  lifetime in second
     * @param  integer $length    length of the token
     *
     * @return Token              instance of token
     */
    public function create($userId, $lifetime = 3600, $length = 50)
    {
        $token = new Token();
        $token->token = $this->generator->generateToken($length);
        $token->user_id = $userId;
        $token->expire_at = time() + $lifetime;

        return $token;
    }

    /**
     * returns the model found
     *
     * @param int $id
     *
     * @return Token
     */
    public function find($id)
    {

        return $this->model->findOrFail($id);
    }

    /**
     * returns the model found
     *
     * @param int $id
     *
     * @return Collection
     */
    public function findByUser($idUser)
    {

        return $this->model->where('user_id', $idUser)->get();
    }

    /**
     * returns the Token found
     *
     * @param int $token
     *
     * @return Collection
     */
    public function findByToken($token, $userId)
    {

        return $this->model->where('token', $token)
                            ->where('user_id', $idUser)
                            ->get();
    }

    /**
     * @param integer $id
     *
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->find($id);

        return $model->delete();
    }

    /**
     * @return Token
     */
    public function getModel()
    {

        return $this->model;
    }
}
