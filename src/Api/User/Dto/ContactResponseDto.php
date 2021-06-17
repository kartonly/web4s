<?php

declare(strict_types=1);

namespace App\Api\User\Dto;

class ContactResponseDto
{
    public ?string $id = null;

    public string $phone;

    public string $name;
}
