<?php

namespace App\Tests\Unit\EventSubscriber\Strategy;

use App\EventSubscriber\Strategy\AbstractStrategy;
use App\Exceptions\FetchRemoteDataException;
use App\Exceptions\InvalidArgumentsException;
use App\Model\Response\ErrorResponseModel;
use App\Tests\Unit\EventSubscriber\ExceptionSubscriberDataProvider;
use PHPUnit\Framework\TestCase;
use Monolog\Logger;
use \Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use \ReflectionClass;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * @coversDefaultClass \App\EventSubscriber\Strategy\AbstractStrategy
 */
class AbstractStrategyTest extends TestCase
{
    /**
     * @dataProvider dataProviderForGenerateJsonResponse
     * @covers ::__construct
     * @covers ::generateJsonResponse
     * @covers ::setErrors
     * @covers ::getThrowable
     */
    public function testGenerateJsonResponse(Request $request, ErrorResponseModel $errors, JsonResponse $expected)
    {
        $stub = $this->getMockForAbstractClass(
            AbstractStrategy::class,
            [new Exception(), $request]
        );

        $stub->setErrors($errors);

        $this->assertInstanceOf(AbstractStrategy::class, $stub);
        $this->assertInstanceOf(Throwable::class, $stub->getThrowable());

        $result = $stub->generateJsonResponse();

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($expected->getContent(), $result->getContent());
        $this->assertEquals($expected->getStatusCode(), $result->getStatusCode());
    }

    public function dataProviderForGenerateJsonResponse(): array
    {
        $provider = new ExceptionSubscriberDataProvider();

        $result = new JsonResponse();
        $result->setData('{"success":false,"data":null,"message":[{"code":123,"description":"test description"}]}');
        $result->setContent('{"success":false,"data":null,"message":[{"code":123,"description":"test description"}]}');
        $result->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);

        $errorResponse = new ErrorResponseModel();
        $errorResponse->setCode(123);
        $errorResponse->setDescription('test description');

        return [
            'all parameters for generateJsonResponse' => [
                'request' => $provider->mockRequest(false),
                'errors' => $errorResponse,
                'expected' => $result,
            ],
        ];
    }

    protected function mockResponse(bool $withAuthorization, int $line, int $code): array
    {
        $autoloaderReflector = new ReflectionClass(AbstractStrategyTest::class);

        $result = [
            'type' => 'error',
            'transaction_id' => null,
            'api_version' => '1.0',
            'environment' => 'test',
            'request' => [
                'http_method' => 'GET',
                'uri_schema' => 'GET_test_route',
                'uri' => '/',
                'data' => '',
                'headers' => [
                    'accept' => ['application/json'],
                    'accept-language' => ['en'],
                ],
                'server_name' => null,
                'server_ip' => null,
                'server_port' => null,
                'client_ip' => null,
                'client_port' => null,
                'user_agent' => null,
            ],
            'response' => ['code' => 400],
            'error' => [
                'message' => '',
                'file' => $autoloaderReflector->getFileName(),
                'line' => $line,
                'trace' => '#0 /var/www/html/vendor/phpunit/phpunit/src/Framework/TestCase.php(1154): '
                    . 'App\Tests\Unit\EventSubscriber\Strategy\AbstractStrategyTest->testGetLoggingContext()
#1 /var/www/html/vendor/phpunit/phpunit/src/Framework/TestCase.php(842): PHPUnit\Framework\TestCase->runTest()
#2 /var/www/html/vendor/phpunit/phpunit/src/Framework/TestResult.php(693): PHPUnit\Framework\TestCase->runBare()
#3 /var/www/html/vendor/phpunit/phpunit/src/Framework/TestCase.php(796): PHPUnit\Framework\TestResult->run()
#4 /var/www/html/vendor/phpunit/phpunit/src/Framework/TestSuite.php(746): PHPUnit\Framework\TestCase->run()
#5 /var/www/html/vendor/phpunit/phpunit/src/Framework/TestSuite.php(746): PHPUnit\Framework\TestSuite->run()
#6 /var/www/html/vendor/phpunit/phpunit/src/Framework/TestSuite.php(746): PHPUnit\Framework\TestSuite->run()
#7 /var/www/html/vendor/phpunit/phpunit/src/TextUI/TestRunner.php(652): PHPUnit\Framework\TestSuite->run()
#8 /var/www/html/vendor/phpunit/phpunit/src/TextUI/Command.php(206): PHPUnit\TextUI\TestRunner->doRun()
#9 /var/www/html/vendor/phpunit/phpunit/src/TextUI/Command.php(162): PHPUnit\TextUI\Command->run()
#10 /var/www/html/vendor/phpunit/phpunit/phpunit(61): PHPUnit\TextUI\Command::main()
#11 {main}',
                'code' => $code,
            ],
        ];

        if ($withAuthorization) {
            $result['request']['headers']['authorization'] = ['**********************'];
        }

        return $result;
    }
}
