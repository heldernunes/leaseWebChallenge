<?php

namespace App\EventSubscriber\Strategy;

use App\Model\Response\ErrorResponseModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GeneralException extends AbstractStrategy
{
    public function getResponse(): Response
    {
        $exception = $this->getThrowable();

        $responseModel = new ErrorResponseModel();
        $responseModel->setCode(AbstractErrorCodes::GENERAL_EXCEPTION_CODE);
        $responseModel->setDescription($exception->getMessage());
        $this->setErrors($responseModel);

        $this->statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        return $this->generateJsonResponse();
    }
}
