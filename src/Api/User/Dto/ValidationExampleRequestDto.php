<?php

declare(strict_types=1);

namespace App\Api\User\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ValidationExampleRequestDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(10)
     */
    public string $text;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(10)
     */
    public int $number;
}
