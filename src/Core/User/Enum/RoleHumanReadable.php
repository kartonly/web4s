<?php

declare(strict_types=1);

namespace App\Core\User\Enum;

use App\Core\Common\Enum\AbstractEnum;

final class RoleHumanReadable extends AbstractEnum
{
    public const ADMIN = 'Администратор';
    public const USER  = 'Пользователь';
    public const OPERATOR  = 'Оператор';
    public const MANAGER  = 'Менеджер';
    public const CLIENT  = 'Клиент';
}
