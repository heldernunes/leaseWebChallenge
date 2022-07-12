<?php

namespace App\Tests\Unit\EventSubscriber;

use App\Exceptions\InvalidArgumentsException;
use App\Exceptions\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use \Exception;

class ExceptionSubscriberDataProvider
{
    /**
     * Which events the ExceptionSubscriber has to subscribe.
     *
     * @return array
     */
    public function exceptionSubscriberEvents()
    {
        return [
            [
                'events' => [
                    'kernel.exception' => 'onKernelException'
                ],
            ]
        ];
    }

    public function onKernelExceptions()
    {
        return [
            [
                'exception' => new NotFoundException('Wally not found here!'),
                'expected' => [
                    'errorCode' => 404,
                    'strategyType' => \App\EventSubscriber\Strategy\NotFoundException::class,
                ],
            ],
            [
                'exception' => new InvalidArgumentsException('The guy found is not wally'),
                'excepted' => [
                    'errorCode' => 500,
                    'strategyType' => \App\EventSubscriber\Strategy\InvalidArgumentsException::class,
                ],
            ],
            [
                'exception' => new Exception('Wally don\'t wants to be found', 500),
                'expected' => [
                    'errorCode' => 500,
                    'strategyType' => \App\EventSubscriber\Strategy\GeneralException::class
                ]
            ],
            [
                'exception' => new Exception('Wally timed out!'),
                'expected' => [
                    'errorCode' => 500,
                    'strategyType' => \App\EventSubscriber\Strategy\GeneralException::class
                ]
            ],
            [
                'exception' => new Exception('cURL to Wally not found'),
                'expected' => [
                    'errorCode' => 500,
                    'strategyType' => \App\EventSubscriber\Strategy\GeneralException::class
                ]
            ],
        ];
    }

    public function mockRequest(bool $withAuthorization): Request
    {
        $query = [
            'site' => 'leaseWeb',
            'locationType' => 'city',
            'propertyType' => 'hostel',
        ];
        $attributes = [
            '_route' => 'test_route',
            '_controller' => 'App\Controller\SearchContorller::search',
            'id' => '3',
        ];
        $request = new Request($query, [], $attributes);
        $request->setMethod('GET');
        $request->attributes->add(['id' => 123]);
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Accept-Language', 'en');
        if ($withAuthorization) {
            $request->headers->set('Authorization', 'testAuthorizationToken');
        }

        return $request;
    }
}
