<?php

declare(strict_types=1);

namespace App\Core\Order\Factory;

use App\Core\Order\Document\Order;

class OrderFactory
{
    public function create(
        string $title,
        string $about,
        string $status
    ): Order {
        $order = new Order(
            $title,
            $about,
            $status
        );

        return $order;
    }

    public function update(
        $order,
        string $title,
        string $about,
        string $status
    ){
        $order->setTitle($title);
        $order->setAbout($about);
        $order->setStatus($status);
    }
}
