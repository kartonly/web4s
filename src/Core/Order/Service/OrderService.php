<?php

declare(strict_types=1);

namespace App\Core\Order\Service;

use App\Api\Order\Dto\OrderCreateRequestDto;
use App\Api\Order\Dto\OrderUpdateRequestDto;
use App\Core\Order\Document\Order;
use App\Core\Order\Factory\OrderFactory;
use App\Core\Order\Repository\OrderRepository;
use Psr\Log\LoggerInterface;

class OrderService
{
    /**
     * @var OrderRepository
     */
    private OrderRepository $orderRepository;

    /**
     * @var OrderFactory
     */
    private OrderFactory $orderFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    public function __construct(OrderRepository $orderRepository, OrderFactory $orderFactory, LoggerInterface $logger)
    {
        $this->orderRepository = $orderRepository;
        $this->orderFactory    = $orderFactory;
        $this->logger          = $logger;
    }

    public function findOneBy(array $criteria): ?Order
    {
        return $this->orderRepository->findOneBy($criteria);
    }

    public function find(string $id): ?Order
    {
        return $this->orderRepository->find($id);
    }

    public function updateOrder(string $id, OrderUpdateRequestDto $requestDto)
    {
        $order = $this->find($id);

        $order = $this->orderFactory->update( $order,
            $requestDto->title,
            $requestDto->about,
            $requestDto->status
        );

        $order = $this->orderRepository->save($order);

        $this->logger->info('Order updated successfully', [
            'id' => $order->getId(),
            'title' => $order->getTitle(),
            'about' => $order->getAbout(),
            'status' => $order->getStatus(),
        ]);

        return $order;
    }

    public function createOrder(OrderCreateRequestDto $requestDto): Order
    {
        $order = $this->orderFactory->create(
            $requestDto->title,
            $requestDto->about,
            $requestDto->status
        );

        $order->setTitle($requestDto->title);
        $order->setAbout($requestDto->about);
        $order->setStatus($requestDto->status);

        $order = $this->orderRepository->save($order);

        $this->logger->info('Order created successfully', [
            'order_id' => $order->getId(),
            'title' => $order->getTitle(),
            'about' => $order->getAbout(),
            'status' => $order->getStatus()
        ]);

        return $order;
    }
}
