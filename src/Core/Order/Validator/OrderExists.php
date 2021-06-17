<?php

declare(strict_types=1);

namespace App\Core\Order\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class OrderExists extends Constraint
{
    public $message = 'Order already exists, id: {{ orderId }}';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
