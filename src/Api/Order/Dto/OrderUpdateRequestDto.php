<?php

declare(strict_types=1);

namespace App\Api\Order\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OrderUpdateRequestDto
{
    /**
     * @Assert\Length(max=30, min=3)
     */
    public ?string $title = null;

    public ?string $about = null;

    public ?string $status = null;
}
