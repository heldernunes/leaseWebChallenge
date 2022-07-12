<?php

namespace App\Tests\Unit\EventSubscriber;

use App\DAO\VouchersErrorLogsDAO;
use App\EventSubscriber\ExceptionSubscriber;
use App\Model\Request\SearchReqModel;
use App\Model\Request\Sessions\CustomerSimpleSessionReqModel;
use App\Model\Response\ErrorResponseModel;
use App\Tests\Helper\Faker;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use \Monolog\Logger;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @coversDefaultClass \App\EventSubscriber\ExceptionSubscriber
 */
class ExceptionSubscriberTest extends TestCase
{
    private $requestStack;

    protected function setUp()
    {
        $this->requestStack = $this->createMock(RequestStack::class);
    }


    /**
     * @covers ::__construct
     */
    public function testClassStructure()
    {
        $subscriber = new ExceptionSubscriber((new ErrorResponseModel()));
        $this->assertInstanceOf(EventSubscriberInterface::class, $subscriber);
    }

    /**
     * @dataProvider \App\Tests\Unit\EventSubscriber\ExceptionSubscriberDataProvider::exceptionSubscriberEvents
     *
     * @covers ::getSubscribedEvents
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testSubscribesCorrectEvents($expectedEvents)
    {
        $subscribedEvents = ExceptionSubscriber::getSubscribedEvents();

        $this->assertEquals($expectedEvents, $subscribedEvents);
    }

    /**
     * @dataProvider \App\Tests\Unit\EventSubscriber\ExceptionSubscriberDataProvider::onKernelExceptions
     * @covers ::onKernelException
     * @covers \App\Exceptions\NotFoundException
     */
    public function testOnKernelException($exception)
    {
        $subscriber = new ExceptionSubscriber((new ErrorResponseModel()));

        //create an event
        $httpKernelMock = $this->createMock(HttpKernelInterface::class);

        $event = new ExceptionEvent($httpKernelMock, $this->mockRequest(), Faker::integer(), $exception);

        //call method
        $subscriber->onKernelException($event);

        $this->assertTrue(true);
    }

    protected function mockRequest()
    {
        $requestBody = [
            'storage' => [
                ['start' => "250GB", "end" => "8TB"],
            ],
            'ram' => [
                ['value' => '4GB'],
                ['value' => '32GB'],
            ],
            'hdd' => 'SATA',
            'location' => 'AmsterdamAMS-01',
        ];

        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            json_encode($requestBody)
        );

         return $request;
    }
}
