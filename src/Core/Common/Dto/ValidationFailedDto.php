<?php

declare(strict_types=1);

namespace App\Core\Common\Dto;

final class ValidationFailedDto
{
    public string $message;

    public ?string $path;

    public ?string $code;
}
