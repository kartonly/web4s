<?php

declare(strict_types=1);

namespace App\Core\User\Service;

use App\Api\User\Dto\UserCreateRequestDto;
use App\Api\User\Dto\UserUpdateRequestDto;
use App\Core\User\Document\User;
use App\Core\User\Factory\UserFactory;
use App\Core\User\Repository\UserRepository;
use Psr\Log\LoggerInterface;


class UserService
{

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserFactory
     */
    private UserFactory $userFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(UserRepository $userRepository, UserFactory $userFactory, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->userFactory    = $userFactory;
        $this->logger         = $logger;
    }

    public function findOneBy(array $criteria): ?User
    {
        return $this->userRepository->findOneBy($criteria);
    }

    public function find(string $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function updateUser(string $id, UserUpdateRequestDto $requestDto)
    {
        $user = $this->find($id);
        $user->setLastName($requestDto->lastName);
        $user->setFirstName($requestDto->firstName);

        $user = $this->userRepository->save($user);

        $this->logger->info('User updated successfully', [
            'user_id' => $user->getId(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName()
        ]);

        return $user;
    }

    public function createUser(UserCreateRequestDto $requestDto): User
    {
        $user = $this->userFactory->create(
            $requestDto->phone,
            $requestDto->roles,
            $requestDto->apiToken
        );

        $user->setLastName($requestDto->lastName);
        $user->setFirstName($requestDto->firstName);

        $user = $this->userRepository->save($user);

        $this->logger->info('User created successfully', [
            'user_id' => $user->getId(),
            'first_name' => $user->getFirstName(),
        ]);

        return $user;
    }
}
