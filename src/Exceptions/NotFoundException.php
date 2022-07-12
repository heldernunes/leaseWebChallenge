<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\JsonResponse;

class NotFoundException extends GenericException
{
    private $statusCode = JsonResponse::HTTP_NOT_FOUND;

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
