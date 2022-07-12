<?php

namespace App\Exceptions;

class InvalidArgumentsException extends \InvalidArgumentException
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
