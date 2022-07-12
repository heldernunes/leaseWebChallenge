<?php

namespace App\Service;

use App\EventSubscriber\Strategy\AbstractErrorCodes;
use App\Exceptions\InvalidArgumentsException;
use App\Model\Request\RequestCollectionModel;
use App\Model\Request\RequestModelInterface;
use App\Model\Response\ErrorResponseModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validateRequestModel(RequestModelInterface $requestModel): void
    {
        $errors = $this->getErrorsFromValidation($requestModel);

        if (!empty($errors)) {
            $exception = new InvalidArgumentsException('Invalid arguments', JsonResponse::HTTP_BAD_REQUEST);
            $exception->setErrors($errors);
            throw $exception;
        }
    }

    public function validateRequestModels(RequestCollectionModel $requestModels): void
    {
        $errors = [];

        foreach ($requestModels as $requestModel) {
            if ($requestModel instanceof RequestModelInterface) {
                $errors += $this->getErrorsFromValidation($requestModel);
            }
        }

        if (!empty($errors)) {
            $exception = new InvalidArgumentsException('Invalid arguments', JsonResponse::HTTP_BAD_REQUEST);
            $exception->setErrors($errors);
            throw $exception;
        }
    }

    protected function getErrorsFromValidation(RequestModelInterface $requestModel): array
    {
        $violations = $this->validator->validate($requestModel);
        $errors = [];

        $errorResponseModel = new ErrorResponseModel();
        foreach ($violations as $violation) {
            $errorResponseModel->setDescription(sprintf($violation->getMessage(), $violation->getPropertyPath()));
            $errorResponseModel->setDescription(
                sprintf(
                    "[%s]: %s",
                    $violation->getPropertyPath(),
                    $violation->getMessage()
                )
            );
            $errorResponseModel->setCode(AbstractErrorCodes::INVALID_PARAM_CODE);

            $errors[] = $errorResponseModel->toArray();
        }
        return $errors;
    }
}
