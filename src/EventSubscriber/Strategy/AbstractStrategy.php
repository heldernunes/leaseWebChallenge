<?php

namespace App\EventSubscriber\Strategy;

use App\Model\Response\BaseResponseModel;
use App\Model\Response\ErrorResponseModel;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Throwable;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractStrategy implements ExceptionStrategyInterface
{
    const TYPE_ERROR = 'error';

    const TYPE_WARNING = 'warning';

    private $throwable;

    private $responseModel;

    protected $errors = [];

    protected $request;

    protected $statusCode = JsonResponse::HTTP_BAD_REQUEST;

    protected $errorCode = AbstractErrorCodes::GENERAL_EXCEPTION_CODE;

    public function __construct(Throwable $exception, Request $request)
    {
        $this->throwable = $exception;
        $this->responseModel = new BaseResponseModel();
        $this->request = clone $request;
    }

    public function generateJsonResponse() : JsonResponse
    {
        $this->responseModel->setSuccess(false);
        $this->responseModel->setData(null);
        $this->responseModel->setMessage($this->errors);

        return new JsonResponse($this->responseModel->toArray(), $this->statusCode);
    }

    public function getThrowable(): Throwable
    {
        return $this->throwable;
    }

    public function setErrors(ErrorResponseModel $error)
    {
        $this->errors[] = $error->toArray();
    }
}
