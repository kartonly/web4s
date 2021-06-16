<?php

declare(strict_types=1);

namespace App\Api\User\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateRequestDto
{
    /**
     * @Assert\Length(max=30, min=3)
     */
    public ?string $firstName = null;

    public ?string $lastName = null;
}
