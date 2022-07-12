<?php

namespace App\Middleware;

use App\Model\Request\SearchReqModel;
use Symfony\Component\HttpFoundation\Request;

class GetSearchParamConverter extends Middleware
{
    const PARAM_CONVERTER_NAME = 'searchReqModel';

    protected function createRequestObject(Request $request): void
    {
        $requestObj = new SearchReqModel($request->toArray());

        $this->addRequestToValidate($requestObj);
    }
}
