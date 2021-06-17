<?php

declare(strict_types=1);

namespace App\Api\Order\Dto;

class OrderListResponseDto
{
    public array $data;

    public function __construct(OrderResponseDto ... $data)
    {
        $this->data = $data;
    }
}