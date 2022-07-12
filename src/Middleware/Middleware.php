<?php

namespace App\Middleware;

use App\Model\Request\RequestCollectionModel;
use App\Model\Request\RequestModelInterface;
use App\Service\ValidatorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class Middleware implements ParamConverterInterface
{
    private $validatorService;
    /* @var RequestCollectionModel */
    protected $requestCollection;
    const PARAM_CONVERTER_NAME = "";

    public function __construct(ValidatorService $validatorService)
    {
        $this->validatorService = $validatorService;
        $this->requestCollection = new RequestCollectionModel();
    }

    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $this->createRequestObject($request);

        // Now, let's validate the request
        $this->validateRequestModels($this->requestCollection);

        // no errors then add request model to request
        $request->attributes->set($configuration->getName(), $this->requestCollection[0]);

        return true;
    }

    public function addRequestToValidate(RequestModelInterface $reqModel): void
    {
        $this->requestCollection[] = $reqModel;
    }

    abstract protected function createRequestObject(Request $request): void;

    public function supports(ParamConverter $configuration): bool
    {
        if ($configuration->getName() == static::PARAM_CONVERTER_NAME) {
            return true;
        }

        return false;
    }

    protected function validateRequestModel(RequestModelInterface $requestModel): void
    {
        $this->validatorService->validateRequestModel($requestModel);
    }

    protected function validateRequestModels(RequestCollectionModel $requestModels): void
    {
        $this->validatorService->validateRequestModels($requestModels);
    }
}
