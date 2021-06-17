<?php

declare(strict_types=1);

namespace App\Core\Common\Repository;

use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;
use Doctrine\Bundle\MongoDBBundle\Repository\ServiceDocumentRepository;
use Doctrine\ODM\MongoDB\DocumentNotFoundException;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\LockMode;
use Doctrine\ODM\MongoDB\Mapping\MappingException;

abstract class AbstractRepository extends ServiceDocumentRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->getDocumentClassName());
    }

    /**
     * @return string
     */
    abstract public function getDocumentClassName(): string;

    /**
     * @param mixed    $id
     * @param int      $lockMode
     * @param int|null $lockVersion
     *
     * @return object
     * @throws DocumentNotFoundException
     * @throws LockException
     * @throws MappingException
     */
    public function getOne($id, int $lockMode = LockMode::NONE, ?int $lockVersion = null): object
    {
        $object = $this->find($id, $lockMode, $lockVersion);

        if ($object === null) {
            throw DocumentNotFoundException::documentNotFound($this->getDocumentClassName(), $id);
        }

        return $object;
    }

    /**
     * @param array $criteria
     *
     * @return object
     * @throws DocumentNotFoundException
     */
    public function getOneBy(array $criteria): object
    {
        $object = $this->findOneBy($criteria);

        if ($object === null) {
            throw DocumentNotFoundException::documentNotFound($this->getDocumentClassName(), $criteria);
        }

        return $object;
    }

    public function save($document, bool $flush = true): object
    {
        $this->dm->persist($document);

        if ($flush) {
            $this->flush();
        }

        return $document;
    }

    public function remove($document): void
    {
        $this->dm->remove($document);
        $this->flush();
    }

    public function flush(): void
    {
        $this->dm->flush();
    }
}
