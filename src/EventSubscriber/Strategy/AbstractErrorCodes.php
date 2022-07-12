<?php

namespace App\EventSubscriber\Strategy;

abstract class AbstractErrorCodes
{
    const ROUTE_NOT_FOUND = 100;
    const INVALID_PARAM_CODE = 200;
    const REMOTE_PROVIDER_ERROR = 300;
    const API_EXCEPTION_CODE = 400;
    const GENERAL_EXCEPTION_CODE = 900;
}
