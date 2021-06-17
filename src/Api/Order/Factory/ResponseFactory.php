<?php

declare(strict_types=1);

namespace App\Api\Order\Factory;

use App\Api\Order\Dto\ContactResponseDto;
use App\Api\Order\Dto\OrderResponseDto;
use App\Core\Order\Document\Order;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    /**
     * @param Order         $order
     *
     * @return OrderResponseDto
     */
    public function createOrderResponse(Order $order): OrderResponseDto
    {
        $dto = new OrderResponseDto();

        $dto->id                = $order->getId();
        $dto->title             = $order->getTitle();
        $dto->about             = $order->getAbout();
        $dto->status            = $order->getStatus();

        return $dto;
    }
}
