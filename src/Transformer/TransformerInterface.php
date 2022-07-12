<?php

namespace App\Transformer;

use App\Model\Response\ResponseModelInterface;
use App\Model\Response\ThrottleResponseModel;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @codeCoverageIgnore
 */
interface TransformerInterface
{
    public function apply(ResponseModelInterface $model);
}
