<?php

namespace App\Validator;

use App\Service\UserService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UniqueEmailValidator
 */
class UniqueEmailValidator extends ConstraintValidator
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * UniqueEmailValidator constructor.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ($this->userService->getOneBy(['email' => $value])) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
