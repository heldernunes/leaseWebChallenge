<?php

namespace App\Tests\Unit\Middleware;

use App\Exceptions\InvalidArgumentsException;
use App\Middleware\GetSearchParamConverter;
use App\Middleware\Middleware;
use App\Model\Request\RequestCollectionModel;
use App\Service\ValidatorService;
use App\Tests\Helper\ReflectionHelper;
use App\Tests\Unit\Middleware\TestObject\DummyRequestModel;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @coversDefaultClass \App\Middleware\Middleware
 */
class MiddlewareTest extends TestCase
{
    /** @var  ValidatorInterface */
    private $validator;

    protected function setUp()
    {
        $this->validator = $this->createMock(ValidatorService::class);
    }

    /**
     * @covers ::validateRequestModel
     * @covers ::__construct
     * @dataProvider providerValidateRequestModel
     */
    public function testValidateRequestModel($requestModel)
    {
        $stub = $this->getMockForAbstractClass(Middleware::class, [$this->validator]);

        $errors = ReflectionHelper::invokeMethod($stub, 'validateRequestModel', [$requestModel]);

        $this->assertNull($errors);
    }

    /**
     * @covers ::validateRequestModels
     * @covers ::__construct
     * @dataProvider providerValidateRequestModel
     */
    public function testValidateRequestModels($requestModel)
    {

        $stub = $this->getMockForAbstractClass(Middleware::class, [$this->validator]);

        $errors = ReflectionHelper::invokeMethod(
            $stub,
            'validateRequestModels',
            [new RequestCollectionModel($requestModel)]
        );

        $this->assertNull($errors);
    }

    public function providerValidateRequestModel(): array
    {
        return [
            'Validated with non active' => [
                'requestModel' =>
                    (new DummyRequestModel())->setId(1)->setName('Harry Potter'),
            ],
            'Validated with active' => [
                'requestModel' =>
                    (new DummyRequestModel())
                        ->setId(987876)
                        ->setName('Harry Potter')
                        ->setIsActive(1),
            ]
        ];
    }

    /**
     * @covers ::validateRequestModel
     * @covers ::validateRequestModels
     * @covers ::__construct
     * @dataProvider providerValidateRequestModelFail
     */
    public function testValidateRequestModelThrowExceptionWhenFail($requestModel)
    {

        $stub = $this->getMockForAbstractClass(Middleware::class, [$this->validator]);

        $this->validator
            ->expects($this->once())
            ->method('validateRequestModel')
            ->willThrowException(new InvalidArgumentsException());

        $this->expectException(InvalidArgumentsException::class);

        ReflectionHelper::invokeMethod($stub, '__construct', [$this->validator]);
        ReflectionHelper::invokeMethod($stub, 'validateRequestModel', [$requestModel]);
        ReflectionHelper::invokeMethod($stub, 'validateRequestModels', [$requestModel]);
    }

    /**
     * @covers ::validateRequestModels
     * @covers ::__construct
     * @dataProvider providerValidateRequestModelFail
     */
    public function testValidateRequestModelsThrowExceptionWhenFail($requestModel)
    {

        $stub = $this->getMockForAbstractClass(Middleware::class, [$this->validator]);

        $this->validator
            ->expects($this->once())
            ->method('validateRequestModels')
            ->willThrowException(new InvalidArgumentsException());

        $this->expectException(InvalidArgumentsException::class);

        ReflectionHelper::invokeMethod($stub, '__construct', [$this->validator]);
        ReflectionHelper::invokeMethod($stub, 'validateRequestModels', [new RequestCollectionModel($requestModel)]);
    }

    public function providerValidateRequestModelFail(): array
    {
        return [
            'Failed with wrong id' => [
                'requestModel' =>
                    (new DummyRequestModel())->setId(0)->setName('Dumbledor')
            ],
            'Failed with wrong id and short name' => [
                'requestModel' =>
                    (new DummyRequestModel())->setId(-2)->setName('HP')
            ],
        ];
    }

    /**
     * @dataProvider middlewareParamConverterDataProvider
     *
     * @covers ::supports
     */
    public function testSupportsFunction($name, $expected)
    {
        $paramConverter = new GetSearchParamConverter($this->validator);
        $configuration = new ParamConverter(['name' => $name]);

        $supports = $paramConverter->supports($configuration);

        $this->assertEquals($expected, $supports);
    }

    public function middlewareParamConverterDataProvider(): array
    {
        return [
            [
                'name' => 'nonExistentModel',
                'expected' => false,
            ],
            [
                'name' => GetSearchParamConverter::PARAM_CONVERTER_NAME,
                'expected' => true,
            ],
        ];
    }
}
