<?php

namespace Salberts\Bundle\Recaptcha2Bundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class IsTrue extends Constraint
{
    public $message = 'This value is not a valid captcha.';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return Constraint::PROPERTY_CONSTRAINT;
    }

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'salberts_recaptcha2.true';
    }
}
