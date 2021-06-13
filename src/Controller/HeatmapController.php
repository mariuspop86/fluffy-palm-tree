<?php

namespace App\Controller;

use App\Entity\Heatmap;
use App\Model\CustomerJourneyModel;
use App\Model\HeatmapModel;
use App\Model\HitModel;
use App\Services\HeatmapService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class HeatmapController extends BaseController
{
    /**
     * @Route("/heatmaps", name="get_heatmap", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getHeatmap(): Response
    {
        $heatmap = $this->getDoctrine()
            ->getRepository(Heatmap::class)
            ->findAll();
        $result = [];
        
        foreach ($heatmap as $item) {
            $result[] = [
                'id' => $item->getId(),
                'link' => $item->getLink(),
                'customer' => $item->getCustomer()->getName(),                
                'time' => $item->getCreatedAt(),                
            ];
        }
        
        return $this->json(['customers' => $result]);
    }

    /**
     * @Route("/heatmaps/hits/link", name="get_hits_link", methods={"GET"})
     *
     * @param Request                      $request
     * @param \App\Services\HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getHitsLink(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(HitModel::class, $request);
            $result = $heatmapService->getHitsLink($model);
        } catch (Exception $error) {
            var_dump($error->getMessage());
            var_dump($error->getLine());
            var_dump($error->getFile());
            return $this->json(json_decode($error->getMessage()), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }

    /**
     * @Route("/heatmaps/hits/linkType", name="get_hits_link_type", methods={"GET"})
     *
     * @param Request                      $request
     * @param \App\Services\HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getHitsLinkTypes(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(HitModel::class, $request);
            $result = $heatmapService->getHitsLinkTypes($model);
        } catch (Exception $error) {
            return $this->json(json_decode($error->getMessage()), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }
    
    /**
     * @Route("/heatmaps/journey/customer/{id}", name="get_heatmap_by_customer", methods={"GET"}, requirements={"customer_id"="\d+"})
     *
     * @param Request                      $request
     * @param \App\Services\HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getJourneyByCustomer(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(CustomerJourneyModel::class, $request);
            $result = $heatmapService->getJourneyByCustomer($model);
        } catch (Exception $error) {
            return $this->json(json_decode($error->getMessage()), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }

    /**
     * @Route("/heatmaps/similar-journey/customer/{id}", name="get_similar_heatmap_by_customer", methods={"GET"}, requirements={"customer_id"="\d+"})
     *
     * @param Request                      $request
     * @param \App\Services\HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getSimilarJourneyByCustomer(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(CustomerJourneyModel::class, $request);
            $result = $heatmapService->getSimilarJourneyByCustomer($model);
        } catch (Exception $error) {
            return $this->json(json_decode($error->getMessage()), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }
    
    /**
     * @Route("/heatmaps", name="save_heatmap", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Services\HeatmapService              $heatmapService
     *
     * @return Response
     */
    public function saveHeatmap(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(HeatmapModel::class, $request);
            $heatmapService->saveHeatmap($model);
        } catch (Exception $error) {
            return $this->json(json_decode($error->getMessage()), Response::HTTP_BAD_REQUEST);
        }
            
        return $this->json('', Response::HTTP_CREATED);
    }
}
