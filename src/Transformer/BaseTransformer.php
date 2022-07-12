<?php

namespace App\Transformer;

use App\Model\Response\BaseResponseModel;
use App\Model\Response\ResponseModelInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseTransformer implements TransformerInterface
{
    /**
     * @Assert\NotNull()
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     * @Assert\Type("string")
     *
     * @var string
     */
    private $contentType = 'application/json';

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setContentType(string $contentType)
    {
        $this->contentType = $contentType;
    }

    public function apply(ResponseModelInterface $model): JsonResponse
    {
        $baseResponseModel = new BaseResponseModel();
        $baseResponseModel->setData($model->toArray());
        $baseResponseModel->setSuccess(true);
        $response = new JsonResponse($baseResponseModel->toArray(), $this->getStatusCode($model));
        $response->headers->add(['Content-Type' => $this->getContentType()]);

        return $response;
    }

    // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
    protected function getStatusCode(ResponseModelInterface $model): int
    {
        return !empty($model) ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
    }
}
