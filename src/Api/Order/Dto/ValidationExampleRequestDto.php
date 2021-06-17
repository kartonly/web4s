<?php

declare(strict_types=1);

namespace App\Api\Order\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ValidationExampleRequestDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(30)
     */
    public string $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(50)
     */
    public string $about;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(10)
     */
    public string $status;
}
