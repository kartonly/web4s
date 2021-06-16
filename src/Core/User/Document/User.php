<?php

declare(strict_types=1);

namespace App\Core\User\Document;

use App\Core\Common\Document\AbstractDocument;
use App\Core\User\Enum\Role;
use App\Core\User\Enum\UserStatus;
use App\Core\User\Repository\UserRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @MongoDB\Document(repositoryClass=UserRepository::class, collection="users")
 */
class User extends AbstractDocument implements UserInterface
{
    /**
     * @MongoDB\Id
     */
    protected ?string $id = null;

    /**
     * @MongoDB\Field(type="string")
     */
    protected ?string $firstName = null;

    /**
     * @MongoDB\Field(type="string")
     */
    protected ?string $lastName = null;

    /**
     * @MongoDB\Field(type="string")
     * @MongoDB\UniqueIndex(name="user_phone")
     */
    protected string $phone;

    /**
     * @MongoDB\Field(type="string")
     */
    protected string $status;

    /**
     * @MongoDB\Field(type="string")
     */
    protected ?string $apiToken = null;

    /**
     * @var string[]
     *
     * @MongoDB\Field(type="collection")
     */
    protected array $roles = [];

    public function __construct(
        string $phone,
        array $roles,
        ?string $apiToken = null
    ) {
        $this->phone  = $phone;
        $this->apiToken = $apiToken;
        $this->status = UserStatus::ACTIVE;

        array_map([$this, 'addRole'], $roles);

        $this->addDefaultRole();
    }

    public function getUsername(): string
    {
        return $this->phone;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array<string> $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;

        $this->addDefaultRole();
    }

    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    public function getPassword(): string
    {
        return $this->apiToken;
    }

    public function getSalt(): string
    {
        return md5($this->apiToken);
    }

    public function eraseCredentials(): void
    {
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    private function addDefaultRole(): void
    {
        $this->addRole(Role::USER);
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }
}
