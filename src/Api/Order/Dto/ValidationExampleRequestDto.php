<?php

declare(strict_types=1);

namespace App\Api\Order\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ValidationExampleRequestDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(50)
     */
    public string $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(100)
     */
    public string $about;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(4)
     */
    public string $status;
}
