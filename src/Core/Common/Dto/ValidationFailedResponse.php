<?php

declare(strict_types=1);

namespace App\Core\Common\Dto;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class ValidationFailedResponse extends JsonResponse
{
    /**
     * @var ConstraintViolationListInterface
     */
    private $violation;

    public function __construct(ConstraintViolationListInterface $violation, array $headers = [])
    {
        $this->violation = $violation;

        parent::__construct(null, Response::HTTP_BAD_REQUEST, $headers);
    }

    private function processViolations(): array
    {
        return array_map(
            static function (ConstraintViolation $violation) {
                $dto = new ValidationFailedDto();

                $dto->message = $violation->getMessage();
                $dto->path    = $violation->getPropertyPath();
                $dto->code    = $violation->getCode();

                return $dto;
            },
            iterator_to_array($this->violation)
        );
    }

    /**
     * @param array $data
     */
    public function setData($data = [])
    {
        parent::setData($this->processViolations());
    }
}
