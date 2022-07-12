<?php

namespace App\Controller;

use App\Model\Request\SearchReqModel;
use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="app_search")
     * @ParamConverter("searchReqModel")
     */
    public function search(SearchReqModel $searchReqModel, SearchService $searchService): JsonResponse
    {
        $params = $searchReqModel->toArray();
        return $searchService->getSearchProduct($params);
    }
}
