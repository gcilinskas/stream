<?php


namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEmail
 * @Annotation
 */
class UniqueEmail extends Constraint
{
    public $message = 'Vartotojas su tokiu el-paštu jau registruotas.';
}
