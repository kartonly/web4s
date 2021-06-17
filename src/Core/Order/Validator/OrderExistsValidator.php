<?php

declare(strict_types=1);

namespace App\Core\Order\Validator;

use App\Core\User\Repository\UserRepository;
use App\Core\Order\Service\OrderService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class OrderExistsValidator extends ConstraintValidator
{
    /**
     * @var OrderService
     */
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof OrderExists) {
            throw new UnexpectedTypeException($constraint, OrderExists::class);
        }

        $order = $this->orderService->findOneBy(['title' => $value->title]);

        if ($order) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ orderId }}', $order->getId())
                ->addViolation();
        }
    }
}
