<?php

namespace App\Exceptions;

use \Exception;

class GenericException extends Exception
{
    private $errors = [];

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }
}
