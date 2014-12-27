<?php namespace Lahaxearnaud\LaravelToken;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lahaxearnaud\LaravelToken\Models\Token;
use Lahaxearnaud\LaravelToken\Repositories\RepositoryInterface;
use Lahaxearnaud\LaravelToken\Security\CryptInterface;

/**
 * Class LaravelToken
 *
 * @author  LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
 * @package Lahaxearnaud\LaravelToken
 */
class LaravelToken
{

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var CryptInterface
     */
    protected $crypt;

    function __construct (RepositoryInterface $repository, CryptInterface $crypt)
    {
        $this->repository = $repository;
        $this->crypt      = $crypt;
    }

    /**
     * @param $token
     * @param $userId
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return bool
     */
    public function isValidCryptToken ($token, $userId = null)
    {

        return $this->isValidToken($this->uncryptToken($token), $userId);
    }

    /**
     * @param $token
     * @param $userId
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return bool
     */
    public function isValidToken ($token, $userId = null)
    {
        try {
            $token = $this->findByToken($token, $userId);

            return $this->isValid($token);
        } catch (ModelNotFoundException $e) {

            return FALSE;
        }
    }

    /**
     * @param $uncrypt
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function cryptToken ($uncrypt)
    {

        return $this->crypt->crypt($uncrypt);
    }

    /**
     * @param $crypt
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return mixed
     */
    public function uncryptToken ($crypt)
    {

        return $this->crypt->uncrypt($crypt);
    }

    /**
     * @param $userId
     * @param int $lifetime
     * @param int $length
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return Token
     */
    public function create ($userId = null, $lifetime = 3600, $length = 100)
    {
        $token =  $this->repository->create($userId, $lifetime, $length);

        \Event::fire('token.created', array($token));

        return $token;
    }

    /**
     * @param $id
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return Token
     */
    public function find ($id)
    {

        return $this->repository->find($id);
    }

    /**
     * @param $token
     * @param $userId
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByToken ($token, $userId = null)
    {

        return $this->repository->findByToken($token, $userId);
    }

    /**
     * @param $idUser
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByUser ($idUser)
    {

        return $this->repository->findByUser($idUser);
    }

    /**
     * @param Token $token
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return bool
     */
    public function burn (Token $token)
    {

        \Event::fire('token.burn', array($token));

        return $this->repository->delete($token);
    }

    /**
     * @param Token $token
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return bool
     */
    public function isValid (Token $token)
    {

        $isValid =  $token->expire_at->isFuture();

        if(!$isValid) {
            \Event::fire('token.notValid', array($token));
        }

        return $isValid;
    }

    /**
     * @param Token $token
     *
     * @author LAHAXE Arnaud <arnaud.lahaxe@gmail.com>
     * @return Token
     */
    public function persist (Token $token)
    {

        \Event::fire('token.saved', array($token));

        return $this->repository->save($token);
    }
}
