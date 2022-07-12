<?php

namespace App\Tests\Unit\EventSubscriber\Strategy;

use App\EventSubscriber\Strategy\GeneralException;
use App\Tests\Unit\EventSubscriber\ExceptionSubscriberDataProvider;
use Exception;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass \App\EventSubscriber\Strategy\GeneralException
 */
class GeneralExceptionTest extends TestCase
{
    /**
     * @dataProvider getResponseDataProvider
     * @covers ::getResponse
     * @covers ::__construct
     */
    public function testGetResponse(Request $request, Exception $exception, JsonResponse $expected)
    {
        $class = new GeneralException($exception, $request);

        $result = $class->getResponse();

        $this->assertInstanceOf(JsonResponse::class, $result);

        $this->assertEquals($expected->getStatusCode(), $result->getStatusCode());
        $this->assertEquals($expected->getContent(), $result->getContent());
    }

    public function getResponseDataProvider(): array
    {
        $provider = new ExceptionSubscriberDataProvider();

        $data = [
            'success' => false,
            'data' => null,
            'message' => [
                (object) [
                    'code' => 900,
                    'description' => 'test message',
                ],
            ],
        ];
        $response = new JsonResponse(
            $data,
            JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
        );


        return [
            'created exception for getResponse' => [
                'request' => $provider->mockRequest(false),
                'exception' => new Exception('test message'),
                'expected' => $response,
            ],
        ];
    }
}
