<?php

namespace App\Tests\Unit\Service;

use App\Exceptions\InvalidArgumentsException;
use App\Model\Request\BaseReqModel;
use App\Model\Request\RequestCollectionModel;
use App\Service\ValidatorService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @coversDefaultClass \App\Service\ValidatorService
 */
class ValidationServiceTest extends TestCase
{

    private $validator;
    private $validationService;

    protected function setUp()
    {
        $this->validator = $this->getMockForAbstractClass(ValidatorInterface::class);
        $this->validationService = new ValidatorService($this->validator);
    }

    /**
     * @covers ::__construct
     * @covers ::validateRequestModel
     * @covers ::getErrorsFromValidation
     * @covers \App\Exceptions\InvalidArgumentsException
    */
    public function testValidateRequestModelThrowsExceptionWhenModelHasErrors()
    {
        $model = new BaseReqModel();
        $errorReturned = new ConstraintViolation("Error", null, [], null, null, null);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($model)
            ->willReturn([$errorReturned]);

        $this->expectException(InvalidArgumentsException::class);
        $this->validationService->validateRequestModel($model);
    }


    /**
     * @covers ::__construct
     * @covers ::validateRequestModels
     * @covers ::getErrorsFromValidation
     * @covers \App\Exceptions\InvalidArgumentsException
     */
    public function testValidateRequestModelsThrowsExceptionWhenModelHasErrors()
    {
        $model = new BaseReqModel();
        $errorReturned = new ConstraintViolation("Error", null, [], null, null, null);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($model)
            ->willReturn([$errorReturned]);

        $this->expectException(InvalidArgumentsException::class);
        $this->validationService->validateRequestModels(new RequestCollectionModel($model));
    }

    /**
     * @covers ::__construct
     * @covers ::validateRequestModel
     * @covers ::getErrorsFromValidation
     */
    public function testValidateRequestModelSuccess()
    {
        $model = new BaseReqModel();

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($model)
            ->willReturn([]);

        $this->validationService->validateRequestModel($model);
    }

    /**
     * @covers ::__construct
     * @covers ::validateRequestModels
     * @covers ::getErrorsFromValidation
     * @covers \App\Exceptions\InvalidArgumentsException
     */
    public function testValidateRequestModelsSuccess()
    {
        $model = new BaseReqModel();

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with($model)
            ->willReturn([]);

        $this->validationService->validateRequestModels(new RequestCollectionModel($model));
    }
}
