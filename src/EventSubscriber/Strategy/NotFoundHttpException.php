<?php

namespace App\EventSubscriber\Strategy;

/**
 * @codeCoverageIgnore - this class is used to cover 404 when URL does not exist
*/
class NotFoundHttpException extends NotFoundException
{
}
