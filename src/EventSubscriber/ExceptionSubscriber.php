<?php

namespace App\EventSubscriber;

use App\EventSubscriber\Strategy\ExceptionStrategyInterface;
use App\EventSubscriber\Strategy\GeneralException;
use App\Model\Request\BaseReqModel;
use App\Model\Request\RequestModelInterface;
use App\Model\Response\ErrorResponseModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private $errorResponseModel;

    public function __construct(
        ErrorResponseModel $errorResponseModel
    ) {
        $this->errorResponseModel = $errorResponseModel;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $strategyClass = sprintf(
            "%s\\Strategy\\%s",
            __NAMESPACE__,
            basename(str_replace('\\', '/', get_class($exception)))
        );

        $strategyClass = class_exists($strategyClass) ? $strategyClass : GeneralException::class;

        /** @var ExceptionStrategyInterface $implementation */
        $implementation = new $strategyClass(
            $exception,
            $event->getRequest(),
            $this->errorResponseModel
        );

        $event->setResponse($implementation->getResponse());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
