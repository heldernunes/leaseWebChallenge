<?php

namespace App\Tests\Unit\EventSubscriber\Strategy;

use App\EventSubscriber\Strategy\InvalidArgumentsException;
use App\Model\Response\ErrorResponseModel;
use App\Tests\Unit\EventSubscriber\ExceptionSubscriberDataProvider;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass \App\EventSubscriber\Strategy\InvalidArgumentsException
 */
class InvalidArgumentExceptionTest extends TestCase
{
    /**
     * @dataProvider getResponseDataProvider
     * @covers ::getResponse
     * @covers ::__construct
     */
    public function testGetResponse(Request $request, Exception $exception, JsonResponse $expected)
    {
        $class = new InvalidArgumentsException($exception, $request);

        $result = $class->getResponse();

        $this->assertInstanceOf(JsonResponse::class, $result);

        $this->assertEquals($expected->getStatusCode(), $result->getStatusCode());
        $this->assertEquals($expected->getContent(), $result->getContent());
    }

    /**
     * @SuppressWarnings(PHPMD.MissingImport)
     */
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
            JsonResponse::HTTP_BAD_REQUEST,
        );

        $error = new ErrorResponseModel();
        $error->setCode(900);
        $error->setDescription('test message');
        $exception =  new \App\Exceptions\InvalidArgumentsException();
        $exception->setErrors([$error->toArray()]);

        return [
            'created exception for getResponse' => [
                'request' => $provider->mockRequest(false),
                'exception' => $exception,
                'expected' => $response,
            ],
        ];
    }
}
