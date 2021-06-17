<?php

declare(strict_types=1);

namespace App\Core\Common\Factory;

use App\Core\Common\Dto\ValidationFailedResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class HTTPResponseFactory
{
    public function createBadRequestResponse(string $message): Response
    {
        return new Response($message, Response::HTTP_BAD_REQUEST);
    }

    public function createValidationFailedResponse(
        ConstraintViolationListInterface $validationErrors
    ): ValidationFailedResponse {
        return new ValidationFailedResponse($validationErrors);
    }
}
