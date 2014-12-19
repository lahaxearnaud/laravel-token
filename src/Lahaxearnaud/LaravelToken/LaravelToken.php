<?php namespace Lahaxearnaud\LaravelToken;

use Illuminate\Support\ServiceProvider;
use Lahaxearnaud\LaravelToken\Repositories\RepositoryInterface;
use Lahaxearnaud\LaravelToken\Security\CryptInterface;

class LaravelToken {

	/**
	 * @var RepositoryInterface
	 */
	protected $repository;

	/**
	 * @var CryptInterface
	 */
	protected $crypt;

	public function __construct(RepositoryInterface $repository,  CryptInterface $crypt)
	{
		$this->repository = $repository;
		$this->crypt = $crypt;
	}

    public function cryptToken($uncrypt)
    {

		return $this->crypt->crypt($uncrypt);
	}

    public function uncryptToken($crypt)
    {

		return $this->crypt->uncrypt($crypt);
    }

    public function create($userId, $lifetime = 3600, $length = 50)
    {

		return $this->repository->create($userId, $lifetime, $length);
    }

    public function find($id)
    {

		return $this->repository->find($id);
    }

    public function findByToken($token, $userId)
    {

		return $this->repository->findByToken($token, $userId);
    }

    public function findByUser($idUser)
    {

		return $this->repository->findByUser($idUser);
    }

    public function burn($id)
    {

		return $this->repository->delete($id);
    }

    public function isValid(Token $token)
    {

        return $token->expire_at->diffInMinutes(time()) > 0;
    }
}