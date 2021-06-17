<?php

declare(strict_types=1);

namespace App\Core\User\Document;

use App\Core\Common\Document\AbstractDocument;
use App\Core\User\Repository\ContactRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\ReferenceOne;

/**
 * @MongoDB\Document(repositoryClass=ContactRepository::class, collection="contacts")
 */
class Contact extends AbstractDocument
{
    /**
     * @MongoDB\Id
     */
    protected ?string $id = null;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\UniqueIndex(name="contact_phone")
     */
    protected string $phone;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $name;

    /**
     * @ReferenceOne(targetDocument=User::class)
     */
    protected User $user;

    public function __construct(string $phone, string $name, User $user)
    {
        $this->phone = $phone;
        $this->name  = $name;
        $this->user  = $user;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
