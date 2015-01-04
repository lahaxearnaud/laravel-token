<?php namespace Lahaxearnaud\LaravelToken;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException;
use Lahaxearnaud\LaravelToken\Models\Token;
use Lahaxearnaud\LaravelToken\Repositories\RepositoryInterface;
use Lahaxearnaud\LaravelToken\Security\CryptInterface;
use \Config as Config;
use \Input as Input;
use \Request as Request;

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

    /**
     * @var Token
     */
    protected $currentToken;

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
    public function isValidCryptToken ($token, $userId = NULL)
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
    public function isValidToken ($token, $userId = NULL)
    {
        try {
            $token = $this->findByToken($token, $userId);

            return $this->isValid($token);
        } catch (ModelNotFoundException $e) {

            throw new TokenNotFoundException($e);
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
     * @param $allowLogin
     * @param int $lifetime
     * @param int $length
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return Token
     */
    public function create ($userId = NULL, $allowLogin = false, $lifetime = 3600, $length = 100)
    {
        $token      = NULL;

        do {
            $token = $this->repository->create($userId, $allowLogin, $lifetime, $length);
        } while ($this->repository->exists($token->token));

        \Event::fire('token.created', array($token));

        $this->persist($token);

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
        try {
            return $this->repository->find($id);
        } catch (ModelNotFoundException $e) {

            throw new TokenNotFoundException($e);
        }
    }

    /**
     * @param $token
     * @param $userId
     *
     * @author LAHAXE Arnaud <lahaxe.arnaud@gmail.com>
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByToken ($token, $userId = NULL)
    {
        try {
            return $this->repository->findByToken($token, $userId);
        } catch (ModelNotFoundException $e) {

            throw new TokenNotFoundException($e);
        }
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

        $isValid = $token->expire_at->isFuture();

        if (!$isValid) {
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

        $result = $this->repository->save($token);

        \Event::fire('token.saved', array($token));

        return $result;
    }

    /**
     * Get the token from the request. We try to get it from GET/POST then headers
     *
     * @author LAHAXE Arnaud <arnaud.lahaxe@gmail.com>
     *
     * @return mixed
     */
    public function getTokenValueFromRequest()
    {
        $tokenFieldsName = Config::get('lahaxearnaud/laravel-token:tokenFieldName');

        if (!is_string($tokenFieldsName)) {
            $tokenFieldsName = 'token';
        }

        $token = Input::get($tokenFieldsName);

        if (empty($token)) {
            $token = Request::header($tokenFieldsName);
        }

        return $token;
    }

    /**
     * @return Token
     */
    public function getCurrentToken()
    {
        return $this->currentToken;
    }

    /**
     * @param Token $currentToken
     *
     * @return self
     */
    public function setCurrentToken($currentToken)
    {
        $this->currentToken = $currentToken;

        return $this;
    }
}
