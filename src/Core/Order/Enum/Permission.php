<?php

declare(strict_types=1);

namespace App\Core\Order\Enum;

use App\Core\Common\Enum\AbstractEnum;

class Permission extends AbstractEnum
{
    public const ORDER_SHOW           = 'ROLE_ORDER_SHOW';
    public const ORDER_INDEX          = 'ROLE_ORDER_INDEX';
    public const ORDER_CREATE         = 'ROLE_ORDER_CREATE';
    public const ORDER_UPDATE         = 'ROLE_ORDER_UPDATE';
    public const ORDER_DELETE         = 'ROLE_ORDER_DELETE';
    public const ORDER_VALIDATION     = 'ROLE_ORDER_VALIDATION';
}