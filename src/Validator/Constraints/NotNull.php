<?php

namespace Validator\Constraints;

class NotNull extends \Symfony\Component\Validator\Constraints\NotNull
{
    public $message = 'not_null';

    public function validatedBy()
    {
        return 'Symfony\Component\Validator\Constraints\NotNullValidator';
    }
}