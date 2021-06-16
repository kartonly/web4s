<?php

declare(strict_types=1);

namespace App\Api\User\Dto;


class UserResponseDto
{
    public ?string $id;

    public ?string $firstName;

    public ?string $lastName;

    public string $phone;

    public ?string $roleHumanReadable;

    public ?string $token;

    public ?ContactResponseDto $contact;
}
