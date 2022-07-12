<?php

namespace App\Tests\Unit\Middleware;

use App\Exceptions\InvalidArgumentsException;
use App\Middleware\GetSearchParamConverter;
use App\Service\ValidatorService;
use App\Tests\Helper\Faker;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validation;

/**
 * @coversDefaultClass \App\Middleware\GetSearchParamConverter
 */
class GetSearchParamConverterTest extends TestCase
{
    private $validator;

    public function setUp(): void
    {
        $this->validator = new ValidatorService(
            Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator()
        );
    }

    /**
     * @dataProvider successDataProvider
     * @covers ::apply
     * @covers ::validateRequestModels
     * @covers ::addRequestToValidate
     * @covers ::createRequestObject
     */
    public function testApplySuccessFunction(Request $request)
    {
        $paramConverter = new GetSearchParamConverter($this->validator);
        $configuration = new ParamConverter(['name' => GetSearchParamConverter::PARAM_CONVERTER_NAME]);

        $this->assertTrue($paramConverter->apply($request, $configuration));
    }

    public function successDataProvider(): array
    {
        $request1 = [
            'storage' => [],
            'ram' => [],
            'hdd' => 'SATA',
            'location' => 'AmsterdamAMS-01',
        ];
        $request2 = $request1;
        $request2['storage'] = [
                ['start' => "250GB", "end" => "8TB"],
            ];
        $request2['ram'] = [
                ['value' => '4GB'],
                ['value' => '32GB'],
            ];
        return [
            [new Request([],[],[],[],[],[], json_encode($request1))],
            [new Request([],[],[],[],[],[], json_encode($request2))],
        ];
    }

//    /**
//     * @dataProvider getApplyFailDataProvider
//     * @covers ::apply
//     * @covers ::createRequestObject
//     * @covers ::setPaginationParameters
//     * @covers ::validateRequestModels
//     */
//    public function testApplyFailedFunction(Request $request)
//    {
//        $paramConverter = new GetVouchersErrorLogsParamConverter($this->validator);
//        $configuration = new ParamConverter(['name' => GetVouchersErrorLogsParamConverter::PARAM_CONVERTER_NAME]);
//        $this->expectException(InvalidArgumentsException::class);
//
//        $paramConverter->apply($request, $configuration);
//    }
//
//    public function getApplyFailDataProvider(): array
//    {
//        return [
//            [new Request(['limit' => 30, 'dateStart' => Faker::dateString()], [], [])],
//            [new Request(['limit' => Faker::word(), 'dateStart' => Faker::dateString()], [], [])],
//            [new Request(['page' => Faker::word(), 'dateStart' => Faker::dateString()], [], [])],
//        ];
//    }
//
//    /**
//     * @dataProvider requestParamConverterDataProvider
//     *
//     * @covers ::supports
//     */
//    public function testSupportsFunction($name, $expected)
//    {
//        $paramConverter = new GetVouchersErrorLogsParamConverter($this->validator);
//        $configuration = new ParamConverter(['name' => $name]);
//
//        $supports = $paramConverter->supports($configuration);
//
//        $this->assertEquals($expected, $supports);
//    }
//
//    public function requestParamConverterDataProvider(): array
//    {
//        return [
//            [
//                'name' => 'nonExistentModel',
//                'expected' => false,
//            ],
//            [
//                'name' => GetVouchersErrorLogsParamConverter::PARAM_CONVERTER_NAME,
//                'expected' => true,
//            ],
//        ];
//    }
}
