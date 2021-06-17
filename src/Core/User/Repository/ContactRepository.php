<?php

declare(strict_types=1);

namespace App\Core\User\Repository;


use App\Core\Common\Repository\AbstractRepository;
use App\Core\User\Document\Contact;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;

/**
 * @method Contact save(Contact $user)
 * @method Contact|null find(string $id)
 * @method Contact|null findOneBy(array $criteria)
 * @method Contact getOne(string $id)
 */
class ContactRepository extends AbstractRepository
{
    public function getDocumentClassName(): string
    {
        return Contact::class;
    }

    /**
     * @throws LockException
     * @throws MappingException|MappingException
     */
    public function getContactById(string $id): ?Contact
    {
        return $this->find($id);
    }
}
