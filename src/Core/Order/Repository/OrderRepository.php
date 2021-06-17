<?php

declare(strict_types=1);

namespace App\Core\Order\Repository;

use App\Core\Common\Repository\AbstractRepository;
use App\Core\Order\Document\Order;
use Doctrine\ODM\MongoDB\LockException;
use Doctrine\ODM\MongoDB\Mapping\MappingException;

/**
 * @method Order save(Order $order)
 * @method Order|null find(string $id)
 * @method Order|null findOneBy(array $criteria)
 * @method Order getOne(string $id)
 */
class OrderRepository extends AbstractRepository
{
    public function getDocumentClassName(): string
    {
        return Order::class;
    }

    /**
     * @throws LockException
     * @throws MappingException
     */
    public function getOrderById(string $id): ?Order
    {
        return $this->find($id);
    }
}
