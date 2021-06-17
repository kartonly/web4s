<?php

declare(strict_types=1);

namespace App\Core\User\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserExists extends Constraint
{
    public $message = 'User already exists, id: {{ userId }}';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
