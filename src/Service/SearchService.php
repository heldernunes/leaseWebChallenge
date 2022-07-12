<?php

namespace App\Service;

use App\Model\Response\SearchProductResponseModel;
use App\Repository\SearchRepository;
use App\Transformer\BaseTransformer;

class SearchService
{
    protected $searchRepository;

    public function __construct(SearchRepository $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }

    public function getSearchProduct(array $params)
    {
        $results = $this->searchRepository->getSearchProduct($params);

        return $this->handleResponse($results);
    }

    protected function handleResponse(array $response)
    {
        $transformer = new BaseTransformer();
        $transformer->setContentType("application/json");

        return $transformer->apply(new SearchProductResponseModel($response));
    }
}
