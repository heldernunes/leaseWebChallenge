<?php

namespace App\EventSubscriber\Strategy;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InvalidArgumentsException extends GeneralException
{
    public function getResponse(): Response
    {
        $exception = $this->getThrowable();

        $this->errorCode = JsonResponse::HTTP_BAD_REQUEST;
        $this->errors = $exception->getErrors();

        return $this->generateJsonResponse();
    }
}
