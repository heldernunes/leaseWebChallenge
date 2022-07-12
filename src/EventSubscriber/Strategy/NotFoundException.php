<?php

namespace App\EventSubscriber\Strategy;

use App\Model\Response\ErrorResponseModel;
use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends GeneralException
{
    public function getResponse(): Response
    {
        $exception = $this->getThrowable();

        $responseModel = new ErrorResponseModel();
        $responseModel->setCode(AbstractErrorCodes::GENERAL_EXCEPTION_CODE);
        $responseModel->setDescription($exception->getMessage());
        $this->setErrors($responseModel);

        $this->statusCode = $exception->getStatusCode();
        return $this->generateJsonResponse();
    }
}
