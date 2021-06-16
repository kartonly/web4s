<?php

declare(strict_types=1);

namespace App\Core\Order\Enum;

use App\Core\Common\Enum\AbstractEnum;

class Status extends AbstractEnum
{
    public const DONE = 'DONE';
    public const OPEN  = 'OPEN';
}