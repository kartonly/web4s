<?php

declare(strict_types=1);

namespace App\Api\User\Dto;

class UserListResponseDto
{
    public array $data;

    public function __construct(UserResponseDto ... $data)
    {
        $this->data = $data;
    }
}
