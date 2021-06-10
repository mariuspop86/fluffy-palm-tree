<?php

namespace App\Controller;

use App\Services\HeatmapService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class HeatmapController extends AbstractFOSRestController
{
    /**
     * @Route("/hits/link", name="get_hits_link", methods={"GET"})
     *
     * @param Request                      $request
     * @param \App\Services\HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getHitsLink(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $result = $heatmapService->getHitsLink($request);
        } catch (\Exception $error) {
            return $this->json(json_decode($error->getMessage()), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }

    /**
     * @Route("/hits/linkType", name="get_hits_link_type", methods={"GET"})
     *
     * @param Request                      $request
     * @param \App\Services\HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getHitsLinkTypes(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $result = $heatmapService->getHitsLinkTypes($request);
        } catch (\Exception $error) {
            return $this->json(json_decode($error->getMessage()), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }
    
    /**
     * @Route("/heatmaps/customer/{customer_id}", name="get_heatmap_by_customer", methods={"GET"}, requirements={"customer_id"="\d+"})
     *
     * @param Request                      $request
     * @param \App\Services\HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getHeatmapByCustomer(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $result = $heatmapService->getHeatmapByCustomer($request);
        } catch (\Exception $error) {
            return $this->json(json_decode($error->getMessage()), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }
    
    /**
     * @Route("/heatmap", name="save_heatmap", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \App\Services\HeatmapService              $heatmapService
     *
     * @return Response
     */
    public function saveHeatmap(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $heatmapService->saveHeatmap($request);
        } catch (\Exception $error) {
            return $this->json(json_decode($error->getMessage()), Response::HTTP_BAD_REQUEST);
        }
            
        return $this->json('', Response::HTTP_CREATED);
    }
}
