<?php

declare(strict_types=1);

namespace App\Core\Order\Enum;

use App\Core\Common\Enum\AbstractEnum;

class OrderStatus extends AbstractEnum
{
    public const OPEN   = 'в работе';
    public const DONE = 'готов';
}