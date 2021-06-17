<?php

declare(strict_types=1);

namespace App\Core\User\Validator;

use App\Core\User\Repository\UserRepository;
use App\Core\User\Service\UserService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UserExistsValidator extends ConstraintValidator
{
    /**
     * @var UserService
     */
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UserExists) {
            throw new UnexpectedTypeException($constraint, UserExists::class);
        }

        $user = $this->userService->findOneBy(['phone' => $value->phone]);

        if ($user) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ userId }}', $user->getId())
                ->addViolation();
        }
    }
}
