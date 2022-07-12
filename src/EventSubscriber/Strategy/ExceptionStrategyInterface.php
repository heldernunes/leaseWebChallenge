<?php

namespace App\EventSubscriber\Strategy;

use Symfony\Component\HttpFoundation\Response;

interface ExceptionStrategyInterface
{
    public function getResponse(): Response;
}
