<?php

namespace Validator;

use BaseExceptions\ValidException;
use Services\CheckRules\CheckRules;
use Symfony\Component\Form\Form;

class ValidErrors
{
    /**
     * The method renders the error to the client and throws exception
     *
     * @param Form $form
     * @throws ValidException
     */
    public static function render(Form $form)
    {
        $errors = [];

        $formErrors = $form->getErrors(true);

        if($formErrors->count() > 0) {
            while($error = $formErrors->getChildren()) {
                $key = $error->getOrigin()->getConfig()->getName();

                if(array_key_exists($key, $errors)) {
                    array_push($errors[$key], $error->getMessage());
                } else {
                    $errors[$key] = [$error->getMessage()];
                }

                $formErrors->next();
            }
        }

        unset($formErrors, $error, $key);

        throw new ValidException(serialize(['error' => $errors]), 406);
    }

    /**
     * The method returns general error without dubbing
     *
     * @param array $errors1
     * @param array $errors2
     * @return array
     */
    private static function arrayMerge(array $errors1, array $errors2) :array
    {
        return array_merge($errors1, $errors2);
    }

}