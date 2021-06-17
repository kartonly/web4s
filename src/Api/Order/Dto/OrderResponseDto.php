<?php

declare(strict_types=1);

namespace App\Api\Order\Dto;


class OrderResponseDto
{
    public ?string $id;

    public ?string $title;

    public ?string $about;

    public ?string $status;
}