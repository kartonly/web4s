<?php

declare(strict_types=1);

namespace App\Core\User\Repository;

use App\Core\Common\Repository\AbstractRepository;
use App\Core\User\Document\User;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;

/**
 * @method User save(User $user)
 * @method User|null find(string $id)
 * @method User|null findOneBy(array $criteria)
 * @method User getOne(string $id)
 */
class UserRepository extends AbstractRepository
{
    public function getDocumentClassName(): string
    {
        return User::class;
    }

    /**
     * @throws LockException
     * @throws MappingException
     */
    public function getUserById(string $id): ?User
    {
        return $this->find($id);
    }
}
